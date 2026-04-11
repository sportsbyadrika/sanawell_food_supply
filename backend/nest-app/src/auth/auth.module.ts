import { Module } from '@nestjs/common';
import { JwtModule } from '@nestjs/jwt';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { PassportModule } from '@nestjs/passport';

import { AuthController } from './auth.controller';
import { AuthService } from './auth.service';
import { JwtStrategy } from './jwt.strategy';
import { PrismaService } from '../prisma/prisma.service';

@Module({
  imports: [
    ConfigModule, // ✅ IMPORTANT

    PassportModule,

    JwtModule.registerAsync({
      imports: [ConfigModule], // ✅ REQUIRED
      inject: [ConfigService],
     useFactory: () => ({
  secret: 'secretKey',
  signOptions: {
    expiresIn: '1d',
  },
}),
    }),
  ],
  controllers: [AuthController],
  providers: [AuthService, JwtStrategy, PrismaService],
  exports: [JwtModule],
})
export class AuthModule {}