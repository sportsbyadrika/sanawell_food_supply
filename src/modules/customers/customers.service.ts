import { ForbiddenException, Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../core/prisma/prisma.service';
import { CreateCustomerDto } from './dto/create-customer.dto';
import { UpdateCustomerDto } from './dto/update-customer.dto';

@Injectable()
export class CustomersService {
  constructor(private readonly prisma: PrismaService) {}

  findAll(agencyId: number) {
    return this.prisma.customer.findMany({ where: { agencyId } });
  }

  create(dto: CreateCustomerDto, agencyId: number) {
    return this.prisma.customer.create({ data: { ...dto, agencyId } });
  }

  async update(id: number, dto: UpdateCustomerDto, agencyId: number) {
    const customer = await this.prisma.customer.findUnique({ where: { id } });
    if (!customer) throw new NotFoundException('Customer not found');
    if (customer.agencyId !== agencyId) {
      throw new ForbiddenException('Cannot update customer from another agency');
    }
    return this.prisma.customer.update({ where: { id }, data: dto });
  }

  async delete(id: number, agencyId: number) {
    const customer = await this.prisma.customer.findUnique({ where: { id } });
    if (!customer) throw new NotFoundException('Customer not found');
    if (customer.agencyId !== agencyId) {
      throw new ForbiddenException('Cannot delete customer from another agency');
    }
    return this.prisma.customer.delete({ where: { id } });
  }
}
