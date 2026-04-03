import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';

@Injectable()
export class BillingService {
  constructor(private readonly prisma: PrismaService) {}

  async generateBill(deliveryOrderId: number, amount: number, agencyId: number) {
    return this.prisma.bill.create({ data: { deliveryOrderId, amount, agencyId } });
  }

  list(agencyId?: number) {
    return this.prisma.bill.findMany({ where: agencyId ? { agencyId } : undefined, include: { receipts: true, deliveryOrder: true } });
  }

  paymentTracking(agencyId: number) {
    return this.prisma.receipt.groupBy({
      by: ['agencyId'],
      where: { agencyId },
      _sum: { amount: true },
      _count: { id: true },
    });
  }
}
