import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { CreateProductDto } from './dto/create-product.dto';

@Injectable()
export class ProductsService {
  constructor(private readonly prisma: PrismaService) {}
  create(dto: CreateProductDto) {
  return this.prisma.product.create({
    data: {
      name: dto.name,
      description: dto.description,
      variant: dto.variant,
      image: dto.image,
      agency_id: dto.agency_id   // ✅ important
    }
  });
}
  findAll(agencyId?: number) { return this.prisma.product.findMany({ where: agencyId ? { agency_id:agencyId } : undefined }); }
  remove(id: number) { return this.prisma.product.delete({ where: { id } }); }
}
