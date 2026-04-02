import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateDeliveryDto } from './dto/create-delivery.dto';
import { UpdateDeliveryStatusDto } from './dto/update-delivery-status.dto';

@Injectable()
export class DeliveryService {
  constructor(private readonly prisma: PrismaService) {}

  async generateDailyOrders(dto: CreateDeliveryDto) {
    return this.prisma.$transaction(
      dto.customerIds.map((customerId) =>
        this.prisma.deliveryOrder.create({
          data: { agencyId: dto.agencyId, routeId: dto.routeId, customerId, orderDate: new Date(dto.orderDate) },
        }),
      ),
    );
  }

  updateStatus(id: number, dto: UpdateDeliveryStatusDto) {
    return this.prisma.deliveryOrder.update({ where: { id }, data: dto });
  }

  findByDriver(driverId: number) {
    return this.prisma.deliveryOrder.findMany({ where: { route: { driverId } }, include: { customer: true, items: true } });
  }
}
