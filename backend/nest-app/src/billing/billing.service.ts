import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';

@Injectable()
export class BillingService {
  constructor(private readonly prisma: PrismaService) {}

 async generateBill(
  customerId: number,
  totalAmount: number,
  routeId?: number,
) {
  return this.prisma.bill.create({
    data: {
      customer_id: customerId,
      total_amount: totalAmount,
      bill_date: new Date(),
      route_id: routeId,
    },
  });
}

  async list(customerId?: number) {
  return this.prisma.bill.findMany({
    where: customerId
      ? {
          customer_id: customerId,
        }
      : undefined,

  
  });
}
async paymentTracking(agencyId: number) {
  return this.prisma.bill.findMany({
    where: {
      customer_id: agencyId,
    },
   
  });
}
}