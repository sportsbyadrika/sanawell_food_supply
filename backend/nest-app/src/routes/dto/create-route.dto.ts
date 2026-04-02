import { IsNumber, IsOptional, IsString } from 'class-validator';

export class CreateRouteDto {
  @IsNumber() agencyId!: number;
  @IsString() name!: string;
  @IsOptional() @IsNumber() driverId?: number;
  @IsOptional() @IsString() vehicleNumber?: string;
}
