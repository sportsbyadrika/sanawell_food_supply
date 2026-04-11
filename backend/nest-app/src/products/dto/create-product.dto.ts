import { IsNumber, IsString, IsOptional } from 'class-validator';

export class CreateProductDto {
  @IsNumber()
  agency_id: number;

  @IsString()
  name: string;

  @IsString()
  variant: string;

  @IsOptional()
  @IsString()
  description?: string;

  @IsOptional()
  @IsString()
  image?: string;
}