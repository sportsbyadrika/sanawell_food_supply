import { Body, Controller, Get, Param, ParseIntPipe, Patch, Post, UseGuards } from '@nestjs/common';
import { Roles } from '../common/decorators/roles.decorator';
import { JwtAuthGuard } from '../common/guards/jwt-auth.guard';
import { RolesGuard } from '../common/guards/roles.guard';
import { CreateDeliveryDto } from './dto/create-delivery.dto';
import { DeliveryService } from './delivery.service';
import { UpdateDeliveryStatusDto } from './dto/update-delivery-status.dto';

@UseGuards(JwtAuthGuard, RolesGuard)
@Controller('deliveries')
export class DeliveryController {
  constructor(private readonly service: DeliveryService) {}

  @Post('generate')
  @Roles('office_staff')
  generate(@Body() dto: CreateDeliveryDto) { return this.service.generateDailyOrders(dto); }

  @Patch(':id/status')
  @Roles('driver', 'office_staff')
  updateStatus(@Param('id', ParseIntPipe) id: number, @Body() dto: UpdateDeliveryStatusDto) {
    return this.service.updateStatus(id, dto);
  }

  @Get('driver/:driverId')
  @Roles('driver', 'agency_admin')
  byDriver(@Param('driverId', ParseIntPipe) driverId: number) { return this.service.findByDriver(driverId); }
}
