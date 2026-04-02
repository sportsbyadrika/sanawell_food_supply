import { IsNumber, IsString } from 'class-validator';

export class CreateProductDto {
  @IsNumber() agencyId!: number;
  @IsString() name!: string;
  @IsString() sku!: string;
  @IsNumber() price!: number;
}
