import {
  Body,
  Controller,
  Get,
  Post,
  Query,
  UseGuards,
} from '@nestjs/common';
import { IsNumber } from 'class-validator';
import { Roles } from '../common/decorators/roles.decorator';
import { JwtAuthGuard } from '../common/guards/jwt-auth.guard';
import { RolesGuard } from '../common/guards/roles.guard';
import { BillingService } from './billing.service';

// ✅ DTO
class GenerateBillDto {
  @IsNumber()
  deliveryOrderId!: number;

  @IsNumber()
  amount!: number;

  @IsNumber()
  agencyId!: number;
}

@UseGuards(JwtAuthGuard, RolesGuard)
@Controller('billing')
export class BillingController {
  constructor(private readonly service: BillingService) {}

  // ✅ Create Bill
  @Post('generate')
  @Roles('office_staff', 'agency_admin')
  generate(@Body() dto: GenerateBillDto) {
    return this.service.generateBill(
      dto.deliveryOrderId,
      dto.amount,
      dto.agencyId,
    );
  }

  // ✅ List Bills
  @Get('bills')
  @Roles('office_staff', 'agency_admin', 'super_admin')
  list(@Query('agencyId') agencyId?: string) {
    return this.service.list(
      agencyId ? Number(agencyId) : undefined,
    );
  }
  

  // ✅ Payment Summary (FIXED ERROR)
  @Get('payments/summary')
  @Roles('agency_admin', 'super_admin')
  paymentsSummary(@Query('agencyId') agencyId: string) {
    return this.service.paymentTracking(Number(agencyId));
  }
}