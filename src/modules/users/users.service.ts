import {
  BadRequestException,
  Injectable,
  NotFoundException,
} from '@nestjs/common';
import * as bcrypt from 'bcrypt';
import { PrismaService } from '../../core/prisma/prisma.service';
import { CreateUserDto } from './dto/create-user.dto';

@Injectable()
export class UsersService {
  constructor(private readonly prisma: PrismaService) {}

  async create(dto: CreateUserDto) {
    const role = await this.prisma.role.findUnique({ where: { id: dto.role_id } });
    if (!role) {
      throw new BadRequestException('Invalid role_id');
    }

    if (dto.agency_id) {
      const agency = await this.prisma.agency.findUnique({
        where: { id: dto.agency_id },
      });
      if (!agency) {
        throw new BadRequestException('Invalid agency_id');
      }
    }

    const password = await bcrypt.hash(dto.password, 10);

    return this.prisma.user.create({
      data: {
        name: dto.name,
        email: dto.email,
        password,
        roleId: dto.role_id,
        agencyId: dto.agency_id,
      },
      select: {
        id: true,
        name: true,
        email: true,
        agencyId: true,
        role: { select: { name: true } },
      },
    });
  }

  async findByAgency(agencyId: number) {
    const agency = await this.prisma.agency.findUnique({ where: { id: agencyId } });
    if (!agency) {
      throw new NotFoundException('Agency not found');
    }

    return this.prisma.user.findMany({
      where: { agencyId },
      select: {
        id: true,
        name: true,
        email: true,
        agencyId: true,
        role: { select: { name: true } },
      },
    });
  }
}
