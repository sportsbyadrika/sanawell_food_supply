import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateCustomerDto } from './dto/create-customer.dto';

@Injectable()
export class CustomersService {
  constructor(private readonly prisma: PrismaService) {}
  create(dto: CreateCustomerDto) { return this.prisma.customer.create({ data: dto }); }
  findAll(agencyId?: number) { return this.prisma.customer.findMany({ where: agencyId ? { agencyId } : undefined }); }
  assignRoute(customerId: number, routeId: number) { return this.prisma.customer.update({ where: { id: customerId }, data: { routeId } }); }
}
