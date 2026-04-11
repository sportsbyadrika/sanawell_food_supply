import { Injectable } from '@nestjs/common';
import { hash } from 'bcrypt';
import { PrismaService } from '../prisma/prisma.service';
import { CreateUserDto } from './dto/create-user.dto';
import { UpdateUserDto } from './dto/update-user.dto';

@Injectable()
export class UsersService {
  constructor(private readonly prisma: PrismaService) {}

  // ✅ FIXED
  findAll(roleId?: number) {
    return this.prisma.user.findMany({
      where: roleId ? { role_id: roleId } : undefined,
     
     
    });
  }

  // ✅ FIXED
  findOne(id: number) {
    return this.prisma.user.findUnique({
      where: { id },
     
    });
  }

  // ✅ FIXED
  async create(dto: CreateUserDto) {
  const passwordHash = await hash(dto.password, 10);

  return this.prisma.user.create({
    data: {
      agency_id: dto.agency_id ?? null,
      role_id: dto.role_id,
      name: dto.name,
      email: dto.email,
      password_hash: passwordHash, 
      mobile: dto.mobile ?? '',
    },
  });
}

  
  async update(id: number, dto: UpdateUserDto) {
    const data: any = { ...dto };

    if (dto.password) {
      data.passwordHash = await hash(dto.password, 10);
      delete data.password;
    }

    // map fields
  if (dto.agency_id !== undefined) data.agency_id = dto.agency_id;
if (dto.role_id !== undefined) data.role_id = dto.role_id;
if (dto.mobile !== undefined) data.mobile = dto.mobile;

    delete data.agencyId;
    delete data.roleId;
    delete data.phone;

    return this.prisma.user.update({
      where: { id },
      data,
    });
  }

  remove(id: number) {
    return this.prisma.user.delete({
      where: { id },
    });
  }
}