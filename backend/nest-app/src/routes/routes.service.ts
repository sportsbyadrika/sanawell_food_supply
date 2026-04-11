import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateRouteDto } from './dto/create-route.dto';

@Injectable()
export class RoutesService {
  constructor(private readonly prisma: PrismaService) {}
 create(dto: CreateRouteDto) {
  return this.prisma.route.create({
    data: {
      agency_id: dto.agency_id,   
      name: dto.name,
      description: dto.description,
      driver_id: dto.driver_id,
      type: dto.type
    }
  });
}
  findAll(agencyId?: number) { return this.prisma.route.findMany({ where: agencyId ? { agency_id:agencyId } : undefined }); }
 assignDriver(id: number, driverId: number) {
  return this.prisma.route.update({
    where: { id },
    data: { driver_id: driverId },
  });
}
}
