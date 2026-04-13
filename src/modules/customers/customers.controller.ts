import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Post,
  Put,
  UseGuards,
} from '@nestjs/common';
import { CustomersService } from './customers.service';
import { JwtGuard } from '../../core/auth/guards/jwt.guard';
import { RolesGuard } from '../../common/guards/roles.guard';
import { Roles } from '../../common/decorators/roles.decorator';
import { User } from '../../common/decorators/user.decorator';
import { CreateCustomerDto } from './dto/create-customer.dto';
import { UpdateCustomerDto } from './dto/update-customer.dto';

@UseGuards(JwtGuard, RolesGuard)
@Roles('SUPER_ADMIN', 'AGENCY_ADMIN')
@Controller('customers')
export class CustomersController {
  constructor(private readonly customersService: CustomersService) {}

  @Get()
  findAll(@User() user: any) {
    return this.customersService.findAll(user.agency_id);
  }

  @Post()
  create(@Body() dto: CreateCustomerDto, @User() user: any) {
    return this.customersService.create(dto, user.agency_id);
  }

  @Put(':id')
  update(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: UpdateCustomerDto,
    @User() user: any,
  ) {
    return this.customersService.update(id, dto, user.agency_id);
  }

  @Delete(':id')
  delete(@Param('id', ParseIntPipe) id: number, @User() user: any) {
    return this.customersService.delete(id, user.agency_id);
  }
}
