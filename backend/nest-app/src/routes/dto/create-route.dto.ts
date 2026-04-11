import { IsNumber, IsOptional, IsString } from 'class-validator';

export class CreateRouteDto {
  @IsNumber()
  agency_id: number;

  @IsOptional()
  @IsString()
  name?: string;

  @IsOptional()
  @IsString()
  description?: string;

  @IsOptional()
  @IsNumber()
  driver_id?: number;

  @IsOptional()
  @IsNumber()
  vehicle_id?: number;

  @IsOptional()
  type?: any; // or enum if defined
}