import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateAgencyDto } from './dto/create-agency.dto';

@Injectable()
export class AgenciesService {
  constructor(private readonly prisma: PrismaService) {}
  create(dto: CreateAgencyDto) { return this.prisma.agency.create({ data: dto }); }
  findAll() { return this.prisma.agency.findMany(); }
  remove(id: number) { return this.prisma.agency.delete({ where: { id } }); }
}
