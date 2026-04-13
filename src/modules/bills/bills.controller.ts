import { Body, Controller, Get, Post, UseGuards } from '@nestjs/common';
import { BillsService } from './bills.service';
import { JwtGuard } from '../../core/auth/guards/jwt.guard';
import { RolesGuard } from '../../common/guards/roles.guard';
import { Roles } from '../../common/decorators/roles.decorator';
import { User } from '../../common/decorators/user.decorator';
import { CreateBillDto } from './dto/create-bill.dto';

@UseGuards(JwtGuard, RolesGuard)
@Roles('SUPER_ADMIN', 'AGENCY_ADMIN')
@Controller('bills')
export class BillsController {
  constructor(private readonly billsService: BillsService) {}

  @Get()
  findAll(@User() user: any) {
    return this.billsService.findAll(user.agency_id);
  }

  @Post()
  create(@Body() dto: CreateBillDto, @User() user: any) {
    return this.billsService.create(dto, user.agency_id);
  }
}
