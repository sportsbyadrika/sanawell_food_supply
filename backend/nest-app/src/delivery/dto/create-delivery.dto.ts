import { IsArray, IsDateString, IsNumber } from 'class-validator';

export class CreateDeliveryDto {
  @IsNumber() agencyId!: number;
  @IsNumber() routeId!: number;
  @IsDateString() orderDate!: string;
  @IsArray() customerIds!: number[];
}
