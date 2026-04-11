import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateCustomerDto } from './dto/create-customer.dto';

@Injectable()
export class CustomersService {
  constructor(private readonly prisma: PrismaService) {}

  create(dto: CreateCustomerDto) {
  const data: any = {
    name: dto.name,
    address: dto.address,
    agency_id: dto.agencyId,
  };

  if (dto.routeId) {
    data.route_id = dto.routeId;
  }

  return this.prisma.customer.create({
    data,
  });
}

  findAll(agencyId?: number) {
    return this.prisma.customer.findMany({
      where: agencyId ? { agency_id: agencyId } : undefined,
    });
  }

  assignRoute(customerId: number, routeId: number) {
    return this.prisma.customer.update({
      where: { id: customerId },
      data: { route_id: routeId },
    });
  }
}