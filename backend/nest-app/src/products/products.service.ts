import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateProductDto } from './dto/create-product.dto';

@Injectable()
export class ProductsService {
  constructor(private readonly prisma: PrismaService) {}
  create(dto: CreateProductDto) { return this.prisma.product.create({ data: dto }); }
  findAll(agencyId?: number) { return this.prisma.product.findMany({ where: agencyId ? { agencyId } : undefined }); }
  remove(id: number) { return this.prisma.product.delete({ where: { id } }); }
}
