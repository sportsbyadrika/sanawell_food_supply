import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateDeliveryDto } from './dto/create-delivery.dto';
import { UpdateDeliveryStatusDto } from './dto/update-delivery-status.dto';

@Injectable()
export class DeliveryService {
  constructor(private readonly prisma: PrismaService) {}

async generateDailyOrders(dto: CreateDeliveryDto) {
  return this.prisma.$transaction(
    dto.customer_ids.map((customer_id: number) =>
      this.prisma.deliveryOrder.create({
        data: {
          route_id: dto.route_id,
          customer_id: customer_id,
          delivery_date: new Date(dto.delivery_date),
          order_no: Math.floor(Math.random() * 100000), // required field
        },
      })
    )
  );
}

  updateStatus(id: number, dto: UpdateDeliveryStatusDto) {
    return this.prisma.deliveryOrder.update({
      where: { id },
      data: dto,
    });
  }

 findByDriver(driverId: number) {
  return this.prisma.deliveryOrder.findMany({
  
  });
}
}