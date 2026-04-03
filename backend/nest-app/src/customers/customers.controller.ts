import { Body, Controller, Get, Param, ParseIntPipe, Patch, Post, Query, UseGuards } from '@nestjs/common';
import { Roles } from '../common/decorators/roles.decorator';
import { JwtAuthGuard } from '../common/guards/jwt-auth.guard';
import { RolesGuard } from '../common/guards/roles.guard';
import { CreateCustomerDto } from './dto/create-customer.dto';
import { CustomersService } from './customers.service';

@UseGuards(JwtAuthGuard, RolesGuard)
@Controller('customers')
export class CustomersController {
  constructor(private readonly service: CustomersService) {}
  @Post() @Roles('office_staff', 'agency_admin') create(@Body() dto: CreateCustomerDto) { return this.service.create(dto); }
  @Get() @Roles('office_staff', 'agency_admin') findAll(@Query('agencyId') agencyId?: string) { return this.service.findAll(agencyId ? Number(agencyId) : undefined); }
  @Patch(':id/assign-route/:routeId')
  @Roles('office_staff', 'agency_admin')
  assign(@Param('id', ParseIntPipe) id: number, @Param('routeId', ParseIntPipe) routeId: number) { return this.service.assignRoute(id, routeId); }
}
