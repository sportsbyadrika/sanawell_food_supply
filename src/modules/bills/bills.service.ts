import { BadRequestException, Injectable } from '@nestjs/common';
import { PrismaService } from '../../core/prisma/prisma.service';
import { CreateBillDto } from './dto/create-bill.dto';

@Injectable()
export class BillsService {
  constructor(private readonly prisma: PrismaService) {}

  findAll(agencyId: number) {
    return this.prisma.bill.findMany({
      where: { agencyId },
      include: { customer: true },
      orderBy: { date: 'desc' },
    });
  }

  async create(dto: CreateBillDto, agencyId: number) {
    const customer = await this.prisma.customer.findUnique({
      where: { id: dto.customer_id },
    });

    if (!customer || customer.agencyId !== agencyId) {
      throw new BadRequestException('Invalid customer_id for current agency');
    }

    return this.prisma.bill.create({
      data: {
        customerId: dto.customer_id,
        agencyId,
        amount: dto.amount,
        status: dto.status,
        date: new Date(dto.date),
      },
    });
  }
}
