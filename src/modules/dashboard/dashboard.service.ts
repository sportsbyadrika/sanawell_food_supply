import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../core/prisma/prisma.service';

@Injectable()
export class DashboardService {
  constructor(private readonly prisma: PrismaService) {}

  async getAgencyDashboard(agencyId: number) {
    const [products, staff, drivers, monthlyBills, monthlyOrders] = await Promise.all([
      this.prisma.product.count({ where: { agencyId } }),
      this.prisma.user.count({ where: { agencyId, role: { name: 'AGENCY_ADMIN' } } }),
      this.prisma.user.count({ where: { agencyId, role: { name: 'DRIVER' } } }),
      this.prisma.bill.groupBy({
        by: ['date'],
        where: { agencyId, status: 'PAID' },
        _sum: { amount: true },
        orderBy: { date: 'asc' },
      }),
      this.prisma.bill.groupBy({
        by: ['date'],
        where: { agencyId },
        _count: { id: true },
        orderBy: { date: 'asc' },
      }),
    ]);

    return {
      products,
      staff,
      drivers,
      revenue: monthlyBills.map((entry) => Number(entry._sum.amount ?? 0)),
      orders: monthlyOrders.map((entry) => entry._count.id),
    };
  }
}
