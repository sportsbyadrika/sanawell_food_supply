import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Post,
  UseGuards,
} from '@nestjs/common';

import { Roles } from '../common/decorators/roles.decorator';
//import { JwtAuthGuard } from '../common/guards/jwt-auth.guard';
//import { RolesGuard } from '../common/guards/roles.guard';

import { CreateAgencyDto } from './dto/create-agency.dto';
import { AgenciesService } from './agencies.service';

//@UseGuards(JwtAuthGuard, RolesGuard)
@Controller('agencies')
export class AgenciesController {
  constructor(private readonly agenciesService: AgenciesService) {}

  @Post()
  @Roles('super_admin')
  create(@Body() dto: CreateAgencyDto) {
    return this.agenciesService.create(dto);
  }

  @Get()
  @Roles('super_admin', 'agency_admin')
  findAll() {
    return this.agenciesService.findAll();
  }

  @Delete(':id')
  @Roles('super_admin')
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.agenciesService.delete(id);
  }
}