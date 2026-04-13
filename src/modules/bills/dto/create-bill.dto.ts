import { Type } from 'class-transformer';
import { IsDateString, IsIn, IsNumber, IsPositive } from 'class-validator';

export class CreateBillDto {
  @Type(() => Number)
  @IsNumber()
  @IsPositive()
  customer_id: number;

  @Type(() => Number)
  @IsNumber()
  amount: number;

  @IsIn(['PENDING', 'PAID', 'OVERDUE'])
  status: string;

  @IsDateString()
  date: string;
}
