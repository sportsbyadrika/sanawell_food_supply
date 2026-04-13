import { Controller, Get, UseGuards } from '@nestjs/common';
import { DashboardService } from './dashboard.service';
import { JwtGuard } from '../../core/auth/guards/jwt.guard';
import { RolesGuard } from '../../common/guards/roles.guard';
import { Roles } from '../../common/decorators/roles.decorator';
import { User } from '../../common/decorators/user.decorator';

@UseGuards(JwtGuard, RolesGuard)
@Controller('agency')
export class DashboardController {
  constructor(private readonly dashboardService: DashboardService) {}

  @Get('dashboard')
  @Roles('AGENCY_ADMIN', 'SUPER_ADMIN')
  getDashboard(@User() user: any) {
    return this.dashboardService.getAgencyDashboard(user.agency_id);
  }
}
