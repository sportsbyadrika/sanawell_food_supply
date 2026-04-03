import { Body, Controller, Delete, Get, Param, ParseIntPipe, Post, Query, UseGuards } from '@nestjs/common';
import { Roles } from '../common/decorators/roles.decorator';
import { JwtAuthGuard } from '../common/guards/jwt-auth.guard';
import { RolesGuard } from '../common/guards/roles.guard';
import { CreateProductDto } from './dto/create-product.dto';
import { ProductsService } from './products.service';

@UseGuards(JwtAuthGuard, RolesGuard)
@Controller('products')
export class ProductsController {
  constructor(private readonly service: ProductsService) {}
  @Post() @Roles('agency_admin') create(@Body() dto: CreateProductDto) { return this.service.create(dto); }
  @Get() @Roles('agency_admin', 'office_staff') findAll(@Query('agencyId') agencyId?: string) { return this.service.findAll(agencyId ? Number(agencyId) : undefined); }
  @Delete(':id') @Roles('agency_admin') remove(@Param('id', ParseIntPipe) id: number) { return this.service.remove(id); }
}
