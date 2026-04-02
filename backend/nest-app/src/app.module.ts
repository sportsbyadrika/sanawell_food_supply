import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { PrismaModule } from './prisma/prisma.module';
import { AuthModule } from './auth/auth.module';
import { UsersModule } from './users/users.module';
import { AgenciesModule } from './agencies/agencies.module';
import { ProductsModule } from './products/products.module';
import { CustomersModule } from './customers/customers.module';
import { RoutesModule } from './routes/routes.module';
import { DeliveryModule } from './delivery/delivery.module';
import { BillingModule } from './billing/billing.module';

@Module({
  imports: [
    ConfigModule.forRoot({ isGlobal: true }),
    PrismaModule,
    AuthModule,
    UsersModule,
    AgenciesModule,
    ProductsModule,
    CustomersModule,
    RoutesModule,
    DeliveryModule,
    BillingModule,
  ],
})
export class AppModule {}
