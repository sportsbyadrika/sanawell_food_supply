import { IsIn, IsOptional, IsString } from 'class-validator';

export class UpdateDeliveryStatusDto {
  @IsIn(['pending', 'delivered', 'not_delivered'])
  status!: 'pending' | 'delivered' | 'not_delivered';

  @IsOptional()
  @IsString()
  failureReason?: string;
}
