import {
  Body,
  Controller,
  Get,
  Param,
  ParseIntPipe,
  Post,
  UseGuards,
} from '@nestjs/common';
import { UsersService } from './users.service';
import { CreateUserDto } from './dto/create-user.dto';
import { JwtGuard } from '../../core/auth/guards/jwt.guard';
import { RolesGuard } from '../../common/guards/roles.guard';
import { Roles } from '../../common/decorators/roles.decorator';
import { User } from '../../common/decorators/user.decorator';

@UseGuards(JwtGuard, RolesGuard)
@Controller('users')
export class UsersController {
  constructor(private readonly usersService: UsersService) {}

  @Post()
  @Roles('SUPER_ADMIN', 'AGENCY_ADMIN')
  create(@Body() dto: CreateUserDto, @User() user: any) {
    if (user.role === 'AGENCY_ADMIN') {
      dto.agency_id = user.agency_id;
    }
    return this.usersService.create(dto);
  }

  @Get('agency/:agencyId')
  @Roles('SUPER_ADMIN', 'AGENCY_ADMIN')
  findByAgency(@Param('agencyId', ParseIntPipe) agencyId: number, @User() user: any) {
    const scopedAgencyId = user.role === 'AGENCY_ADMIN' ? user.agency_id : agencyId;
    return this.usersService.findByAgency(scopedAgencyId);
  }
}
