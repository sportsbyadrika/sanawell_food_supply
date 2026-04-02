import { Body, Controller, Get, Param, ParseIntPipe, Patch, Post, Query, UseGuards } from '@nestjs/common';
import { Roles } from '../common/decorators/roles.decorator';
import { JwtAuthGuard } from '../common/guards/jwt-auth.guard';
import { RolesGuard } from '../common/guards/roles.guard';
import { CreateRouteDto } from './dto/create-route.dto';
import { RoutesService } from './routes.service';

@UseGuards(JwtAuthGuard, RolesGuard)
@Controller('routes')
export class RoutesController {
  constructor(private readonly service: RoutesService) {}
  @Post() @Roles('agency_admin') create(@Body() dto: CreateRouteDto) { return this.service.create(dto); }
  @Get() @Roles('agency_admin', 'office_staff', 'driver') findAll(@Query('agencyId') agencyId?: string) { return this.service.findAll(agencyId ? Number(agencyId) : undefined); }
  @Patch(':id/assign-driver/:driverId') @Roles('agency_admin') assignDriver(@Param('id', ParseIntPipe) id: number, @Param('driverId', ParseIntPipe) driverId: number) { return this.service.assignDriver(id, driverId); }
}
