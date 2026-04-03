import { IsNumber, IsOptional, IsString } from 'class-validator';

export class CreateCustomerDto {
  @IsNumber() agencyId!: number;
  @IsOptional() @IsNumber() routeId?: number;
  @IsString() name!: string;
  @IsString() address!: string;
}
