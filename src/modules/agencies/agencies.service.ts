import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../core/prisma/prisma.service';
import { CreateAgencyDto } from './dto/create-agency.dto';

@Injectable()
export class AgenciesService {
  constructor(private readonly prisma: PrismaService) {}

  create(dto: CreateAgencyDto) {
    return this.prisma.agency.create({ data: dto });
  }

  findAll() {
    return this.prisma.agency.findMany({ orderBy: { id: 'desc' } });
  }

  async findById(id: number) {
    const agency = await this.prisma.agency.findUnique({ where: { id } });
    if (!agency) {
      throw new NotFoundException('Agency not found');
    }
    return agency;
  }
}
