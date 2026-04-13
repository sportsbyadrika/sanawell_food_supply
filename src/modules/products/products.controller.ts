import { Body, Controller, Get, Post, UseGuards } from '@nestjs/common';
import { ProductsService } from './products.service';
import { JwtGuard } from '../../core/auth/guards/jwt.guard';
import { RolesGuard } from '../../common/guards/roles.guard';
import { Roles } from '../../common/decorators/roles.decorator';
import { User } from '../../common/decorators/user.decorator';
import { CreateProductDto } from './dto/create-product.dto';

@UseGuards(JwtGuard, RolesGuard)
@Roles('SUPER_ADMIN', 'AGENCY_ADMIN')
@Controller('products')
export class ProductsController {
  constructor(private readonly productsService: ProductsService) {}

  @Get()
  findAll(@User() user: any) {
    return this.productsService.findAll(user.agency_id);
  }

  @Post()
  create(@Body() dto: CreateProductDto, @User() user: any) {
    return this.productsService.create(dto, user.agency_id);
  }
}
