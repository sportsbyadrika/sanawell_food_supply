import { IsEmail, IsInt, IsOptional, IsString, MinLength } from 'class-validator';

export class CreateUserDto {
  @IsOptional()
  @IsInt()
  agency_id?: number;

  @IsInt()
  role_id: number;

  @IsString()
  name: string;

  @IsEmail()
  email: string;

  @IsString()
  @MinLength(6)
  password: string; // plain password (will hash later)

  @IsOptional()
  @IsString()
  mobile?: string;
}