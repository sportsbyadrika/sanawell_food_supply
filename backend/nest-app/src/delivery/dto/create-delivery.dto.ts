import { IsDateString, IsNumber, IsOptional } from 'class-validator';

export class CreateDeliveryDto {
  @IsNumber()
  route_id: number;

  @IsNumber()
  customer_ids: number[];

  @IsDateString()
  delivery_date: string;

  @IsOptional()
  @IsNumber()
  driver_id?: number;

  @IsOptional()
  @IsNumber()
  vehicle_id?: number;
}