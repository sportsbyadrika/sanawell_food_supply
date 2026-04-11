import { Injectable, UnauthorizedException } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import { compare } from 'bcrypt';
import { PrismaService } from '../prisma/prisma.service';
import { LoginDto } from './dto/login.dto';

@Injectable()
export class AuthService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly jwtService: JwtService,
  ) {}

  async login(dto: LoginDto) {
    // 🔍 Find user
    const user = await this.prisma.user.findUnique({
      where: { email: dto.email },
    });

    if (!user) {
      throw new UnauthorizedException('Invalid credentials');
    }

    if (!user.password_hash) {
      throw new UnauthorizedException('Password not set');
    }

    // 🔧 Fix bcrypt issue ($2y → $2b)
    let hash = user.password_hash;
    if (hash.startsWith('$2y$')) {
      hash = hash.replace('$2y$', '$2b$');
    }

    // 🔐 Compare password
    const matched = await compare(dto.password, hash);

    if (!matched) {
      throw new UnauthorizedException('Invalid credentials');
    }

    // 🚨 FIRST LOGIN CHECK
    if (user.first_login) {
      return {
        message: 'FIRST_LOGIN',
        userId: user.id,
      };
    }

    // 🎟 JWT payload
    const payload = {
      sub: user.id,
      role_id: user.role_id,
      email: user.email,
    };

    const accessToken = await this.jwtService.signAsync(payload);

    // ✅ SUCCESS RESPONSE
    return {
      message: 'LOGIN_SUCCESS',
      accessToken,
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        role_id: user.role_id,
      },
    };
  }
}