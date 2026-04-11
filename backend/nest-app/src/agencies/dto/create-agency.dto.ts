import { IsString, IsOptional } from 'class-validator';

export class CreateAgencyDto {
  @IsString()
  name: string;

  @IsString()
  contact_email: string;

  @IsOptional()
  @IsString()
  contact_number?: string;

  @IsOptional()
  @IsString()
  whatsapp_number?: string;
}