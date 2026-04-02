import { Injectable } from '@nestjs/common';
import { hash } from 'bcrypt';
import { PrismaService } from '../prisma/prisma.service';
import { CreateUserDto } from './dto/create-user.dto';
import { UpdateUserDto } from './dto/update-user.dto';

@Injectable()
export class UsersService {
  constructor(private readonly prisma: PrismaService) {}

  findAll(role?: string) {
    return this.prisma.user.findMany({
      where: role ? { role: { name: role } } : undefined,
      include: { role: true, agency: true },
    });
  }

  findOne(id: number) {
    return this.prisma.user.findUnique({ where: { id }, include: { role: true, agency: true } });
  }

  async create(dto: CreateUserDto) {
    const passwordHash = await hash(dto.password, 10);
    return this.prisma.user.create({
      data: {
        agencyId: dto.agencyId,
        roleId: dto.roleId,
        name: dto.name,
        email: dto.email,
        passwordHash,
        phone: dto.phone,
      },
    });
  }

  async update(id: number, dto: UpdateUserDto) {
    const data: Record<string, unknown> = { ...dto };
    if (dto.password) data.passwordHash = await hash(dto.password, 10);
    delete data.password;
    return this.prisma.user.update({ where: { id }, data });
  }

  remove(id: number) {
    return this.prisma.user.delete({ where: { id } });
  }
}
