import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import * as bcrypt from 'bcrypt';

@Injectable()
export class AgenciesService {
  constructor(private prisma: PrismaService) {}

 async create(data: any) {
  return await this.prisma.$transaction(async (prisma) => {

    // 1. Create agency
    const agency = await prisma.agency.create({
      data: {
        name: data.name,
        contact_email: data.contact_email,
        contact_number: data.contact_number || '9999999999',
        whatsapp_number: data.whatsapp_number || null,
        status: 'active',
      },
    });

    // 2. Generate temp password
    const tempPassword = Math.random().toString(36).slice(-8);
    const hashedPassword = await bcrypt.hash(tempPassword, 10);

    // 3. Create user
    const admin = await prisma.user.create({
      data: {
        name: data.name + ' Admin',
        email: data.contact_email,
        mobile: data.contact_number || '9999999999',
        password_hash: hashedPassword,
        role_id: 6,
        agency_id: agency.id,
        first_login: true,
      },
    });

    // ✅ RETURN INSIDE TRANSACTION
    return {
      agency,
      admin,
      tempPassword,
    };
  });
}

  // ================= READ =================
  async findAll() {
    return this.prisma.agency.findMany({
      orderBy: { id: 'desc' },
    });
  }

  // ================= DELETE =================
  async delete(id: number) {
    return this.prisma.agency.delete({
      where: { id },
    });
  }

  // ================= UPDATE =================
  async update(id: number, data: any) {
    return this.prisma.agency.update({
      where: { id },
      data: {
        name: data.name,
        contact_email: data.contact_email,
        contact_number: data.contact_number,
        whatsapp_number: data.whatsapp_number,
      },
    });
  }
}