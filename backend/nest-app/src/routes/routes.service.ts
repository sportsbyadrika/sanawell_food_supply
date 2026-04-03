import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateRouteDto } from './dto/create-route.dto';

@Injectable()
export class RoutesService {
  constructor(private readonly prisma: PrismaService) {}
  create(dto: CreateRouteDto) { return this.prisma.route.create({ data: dto }); }
  findAll(agencyId?: number) { return this.prisma.route.findMany({ where: agencyId ? { agencyId } : undefined, include: { driver: true, customers: true } }); }
  assignDriver(id: number, driverId: number) { return this.prisma.route.update({ where: { id }, data: { driverId } }); }
}
