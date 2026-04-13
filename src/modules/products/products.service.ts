import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../core/prisma/prisma.service';
import { CreateProductDto } from './dto/create-product.dto';

@Injectable()
export class ProductsService {
  constructor(private readonly prisma: PrismaService) {}

  findAll(agencyId: number) {
    return this.prisma.product.findMany({ where: { agencyId } });
  }

  create(dto: CreateProductDto, agencyId: number) {
    return this.prisma.product.create({
      data: {
        name: dto.name,
        price: dto.price,
        stock: dto.stock ?? 0,
        agencyId,
      },
    });
  }
}
