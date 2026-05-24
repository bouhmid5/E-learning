// ============================================================
// EduNova — NestJS Backend
// FILE: src/app.module.ts
// ============================================================
import { Module } from '@nestjs/common';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import { ThrottlerModule } from '@nestjs/throttler';
import { APP_GUARD, APP_INTERCEPTOR, APP_FILTER } from '@nestjs/core';

// Modules
import { AuthModule }           from './modules/auth/auth.module';
import { UtilisateursModule }   from './modules/utilisateurs/utilisateurs.module';
import { CoursModule }          from './modules/cours/cours.module';
import { InscriptionsModule }   from './modules/inscriptions/inscriptions.module';
import { EvaluationsModule }    from './modules/evaluations/evaluations.module';
import { CertificatsModule }    from './modules/certificats/certificats.module';
import { NotificationsModule }  from './modules/notifications/notifications.module';
import { SupportModule }        from './modules/support/support.module';
import { UploadModule }         from './modules/upload/upload.module';
import { CategoriesModule }     from './modules/categories/categories.module';
import { BadgesModule }         from './modules/badges/badges.module';
import { MessagesModule }       from './modules/messages/messages.module';
import { AdminModule }          from './modules/admin/admin.module';

// Guards & Interceptors
import { JwtAuthGuard }         from './common/guards/jwt-auth.guard';
import { ThrottlerGuard }       from '@nestjs/throttler';
import { LoggingInterceptor }   from './common/interceptors/logging.interceptor';
import { TransformInterceptor } from './common/interceptors/transform.interceptor';
import { AllExceptionsFilter }  from './common/filters/all-exceptions.filter';

// Config
import databaseConfig  from './config/database.config';
import jwtConfig       from './config/jwt.config';
import appConfig       from './config/app.config';

@Module({
  imports: [
    // Global config
    ConfigModule.forRoot({
      isGlobal: true,
      load: [databaseConfig, jwtConfig, appConfig],
      envFilePath: ['.env.local', '.env'],
    }),

    // Database
    TypeOrmModule.forRootAsync({
      imports: [ConfigModule],
      inject: [ConfigService],
      useFactory: (cfg: ConfigService) => ({
        type: 'mysql',
        host:     cfg.get('database.host'),
        port:     cfg.get<number>('database.port'),
        username: cfg.get('database.username'),
        password: cfg.get('database.password'),
        database: cfg.get('database.name'),
        entities:       [__dirname + '/**/*.entity{.ts,.js}'],
        synchronize:    cfg.get('app.env') === 'development',
        logging:        cfg.get('app.env') === 'development',
        charset:        'utf8mb4',
        timezone:       'Z',
        extra: { connectionLimit: 10 },
      }),
    }),

    // Rate limiting
    ThrottlerModule.forRoot([{ ttl: 60000, limit: 100 }]),

    // Feature modules
    AuthModule,
    UtilisateursModule,
    CoursModule,
    InscriptionsModule,
    EvaluationsModule,
    CertificatsModule,
    NotificationsModule,
    SupportModule,
    UploadModule,
    CategoriesModule,
    BadgesModule,
    MessagesModule,
    AdminModule,
  ],
  providers: [
    // Global guards
    { provide: APP_GUARD, useClass: JwtAuthGuard },
    { provide: APP_GUARD, useClass: ThrottlerGuard },
    // Global interceptors
    { provide: APP_INTERCEPTOR, useClass: LoggingInterceptor },
    { provide: APP_INTERCEPTOR, useClass: TransformInterceptor },
    // Global filters
    { provide: APP_FILTER, useClass: AllExceptionsFilter },
  ],
})
export class AppModule {}


// ============================================================
// FILE: src/main.ts
// ============================================================
/*
import { NestFactory }        from '@nestjs/core';
import { ValidationPipe }     from '@nestjs/common';
import { SwaggerModule, DocumentBuilder } from '@nestjs/swagger';
import { ConfigService }      from '@nestjs/config';
import helmet                 from 'helmet';
import * as compression       from 'compression';
import { AppModule }          from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(AppModule, { logger: ['error','warn','log'] });

  const cfg = app.get(ConfigService);
  const port = cfg.get<number>('app.port') || 3000;

  // Security
  app.use(helmet());
  app.use(compression());

  // CORS
  app.enableCors({
    origin: cfg.get('app.corsOrigins')?.split(',') || ['http://localhost:4200'],
    methods: ['GET','POST','PUT','PATCH','DELETE','OPTIONS'],
    allowedHeaders: ['Content-Type','Authorization','X-Requested-With'],
    credentials: true,
  });

  // Global prefix
  app.setGlobalPrefix('api/v1');

  // Validation
  app.useGlobalPipes(new ValidationPipe({
    whitelist:    true,
    forbidNonWhitelisted: true,
    transform:    true,
    transformOptions: { enableImplicitConversion: true },
  }));

  // Swagger
  const swaggerConfig = new DocumentBuilder()
    .setTitle('EduNova API')
    .setDescription('E-Learning Platform REST API')
    .setVersion('1.0')
    .addBearerAuth()
    .addTag('Auth', 'Authentication & authorization')
    .addTag('Cours', 'Course management')
    .addTag('Inscriptions', 'Enrollment management')
    .addTag('Evaluations', 'Quizzes & exams')
    .addTag('Certificats', 'Certificate management')
    .addTag('Admin', 'Administration')
    .build();
  const document = SwaggerModule.createDocument(app, swaggerConfig);
  SwaggerModule.setup('api/docs', app, document, {
    swaggerOptions: { persistAuthorization: true },
  });

  await app.listen(port);
  console.log(`🚀 EduNova API running on: http://localhost:${port}/api/v1`);
  console.log(`📚 Swagger docs: http://localhost:${port}/api/docs`);
}
bootstrap();
*/
