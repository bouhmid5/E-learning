// ============================================================
// EduNova — Common: Guards, Decorators, Interceptors, Filters
// ============================================================

// ── src/common/decorators/public.decorator.ts ─────────────
/*
import { SetMetadata } from '@nestjs/common';
export const IS_PUBLIC_KEY = 'isPublic';
export const Public = () => SetMetadata(IS_PUBLIC_KEY, true);
*/

// ── src/common/decorators/roles.decorator.ts ──────────────
/*
import { SetMetadata } from '@nestjs/common';
export const ROLES_KEY = 'roles';
export const Roles = (...roles: string[]) => SetMetadata(ROLES_KEY, roles);
*/

// ── src/common/decorators/current-user.decorator.ts ───────
/*
import { createParamDecorator, ExecutionContext } from '@nestjs/common';
export const CurrentUser = createParamDecorator(
  (_data: unknown, ctx: ExecutionContext) => {
    const request = ctx.switchToHttp().getRequest();
    return request.user;
  },
);
*/

// ── src/common/guards/jwt-auth.guard.ts ───────────────────
/*
import { Injectable, ExecutionContext } from '@nestjs/common';
import { Reflector }    from '@nestjs/core';
import { AuthGuard }    from '@nestjs/passport';
import { IS_PUBLIC_KEY } from '../decorators/public.decorator';

@Injectable()
export class JwtAuthGuard extends AuthGuard('jwt') {
  constructor(private reflector: Reflector) { super(); }

  canActivate(context: ExecutionContext) {
    const isPublic = this.reflector.getAllAndOverride<boolean>(IS_PUBLIC_KEY, [
      context.getHandler(),
      context.getClass(),
    ]);
    if (isPublic) return true;
    return super.canActivate(context);
  }
}
*/

// ── src/common/guards/roles.guard.ts ──────────────────────
/*
import { Injectable, CanActivate, ExecutionContext, ForbiddenException } from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import { ROLES_KEY } from '../decorators/roles.decorator';

@Injectable()
export class RolesGuard implements CanActivate {
  constructor(private reflector: Reflector) {}

  canActivate(context: ExecutionContext): boolean {
    const requiredRoles = this.reflector.getAllAndOverride<string[]>(ROLES_KEY, [
      context.getHandler(),
      context.getClass(),
    ]);
    if (!requiredRoles || requiredRoles.length === 0) return true;

    const { user } = context.switchToHttp().getRequest();
    if (!user) throw new ForbiddenException('Authentification requise');

    const hasRole = requiredRoles.some(role => user.role === role || user.role === 'ADMIN');
    if (!hasRole) throw new ForbiddenException(`Accès refusé. Rôle requis: ${requiredRoles.join(' ou ')}`);
    return true;
  }
}
*/

// ── src/common/interceptors/transform.interceptor.ts ──────
/*
import {
  Injectable, NestInterceptor, ExecutionContext, CallHandler,
} from '@nestjs/common';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

export interface ApiResponse<T> {
  success: boolean;
  data: T;
  timestamp: string;
}

@Injectable()
export class TransformInterceptor<T>
  implements NestInterceptor<T, ApiResponse<T>> {

  intercept(_context: ExecutionContext, next: CallHandler): Observable<ApiResponse<T>> {
    return next.handle().pipe(
      map(data => ({
        success: true,
        data,
        timestamp: new Date().toISOString(),
      })),
    );
  }
}
*/

// ── src/common/interceptors/logging.interceptor.ts ────────
/*
import {
  Injectable, NestInterceptor, ExecutionContext,
  CallHandler, Logger,
} from '@nestjs/common';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';

@Injectable()
export class LoggingInterceptor implements NestInterceptor {
  private readonly logger = new Logger(LoggingInterceptor.name);

  intercept(context: ExecutionContext, next: CallHandler): Observable<any> {
    const req    = context.switchToHttp().getRequest();
    const method = req.method;
    const url    = req.url;
    const now    = Date.now();

    return next.handle().pipe(
      tap(() => {
        const res  = context.switchToHttp().getResponse();
        const ms   = Date.now() - now;
        this.logger.log(`${method} ${url} ${res.statusCode} +${ms}ms`);
      }),
    );
  }
}
*/

// ── src/common/filters/all-exceptions.filter.ts ───────────
/*
import {
  ExceptionFilter, Catch, ArgumentsHost,
  HttpException, HttpStatus, Logger,
} from '@nestjs/common';
import { Request, Response } from 'express';

@Catch()
export class AllExceptionsFilter implements ExceptionFilter {
  private readonly logger = new Logger(AllExceptionsFilter.name);

  catch(exception: unknown, host: ArgumentsHost) {
    const ctx    = host.switchToHttp();
    const res    = ctx.getResponse<Response>();
    const req    = ctx.getRequest<Request>();

    const status =
      exception instanceof HttpException
        ? exception.getStatus()
        : HttpStatus.INTERNAL_SERVER_ERROR;

    const message =
      exception instanceof HttpException
        ? (exception.getResponse() as any)?.message || exception.message
        : 'Internal server error';

    if (status >= 500) {
      this.logger.error(`${req.method} ${req.url} ${status}`, (exception as any)?.stack);
    }

    res.status(status).json({
      success:   false,
      statusCode: status,
      message,
      path:      req.url,
      timestamp: new Date().toISOString(),
    });
  }
}
*/

// ── src/config/database.config.ts ─────────────────────────
/*
import { registerAs } from '@nestjs/config';
export default registerAs('database', () => ({
  host:     process.env.DB_HOST     || 'localhost',
  port:     parseInt(process.env.DB_PORT || '3306', 10),
  username: process.env.DB_USERNAME || 'root',
  password: process.env.DB_PASSWORD || '',
  name:     process.env.DB_NAME     || 'edunova_db',
}));
*/

// ── src/config/jwt.config.ts ───────────────────────────────
/*
import { registerAs } from '@nestjs/config';
export default registerAs('jwt', () => ({
  secret:          process.env.JWT_SECRET          || 'edunova-super-secret-2026',
  expiresIn:       process.env.JWT_EXPIRES_IN      || '15m',
  refreshSecret:   process.env.JWT_REFRESH_SECRET  || 'edunova-refresh-secret-2026',
  refreshExpiresIn: process.env.JWT_REFRESH_EXPIRES || '7d',
}));
*/

// ── src/config/app.config.ts ──────────────────────────────
/*
import { registerAs } from '@nestjs/config';
export default registerAs('app', () => ({
  env:         process.env.NODE_ENV     || 'development',
  port:        parseInt(process.env.PORT || '3000', 10),
  corsOrigins: process.env.CORS_ORIGINS || 'http://localhost:4200',
  uploadPath:  process.env.UPLOAD_PATH  || './uploads',
  maxFileSize: parseInt(process.env.MAX_FILE_SIZE || '104857600', 10), // 100MB
}));
*/

// ── src/common/pipes/parse-uuid.pipe.ts ───────────────────
/*
import { PipeTransform, Injectable, BadRequestException } from '@nestjs/common';
import { validate as isUUID } from 'uuid';

@Injectable()
export class ParseUUIDPipe implements PipeTransform<string> {
  transform(value: string): string {
    if (!isUUID(value)) throw new BadRequestException(`"${value}" is not a valid UUID`);
    return value;
  }
}
*/

// ── src/common/enums/index.ts ─────────────────────────────
/*
export enum UserRole        { CANDIDAT = 'CANDIDAT', FORMATEUR = 'FORMATEUR' }
export enum CoursStatut     { BROUILLON = 'BROUILLON', EN_REVISION = 'EN_REVISION', PUBLIE = 'PUBLIE', ARCHIVE = 'ARCHIVE', REJETE = 'REJETE' }
export enum CoursNiveau     { DEBUTANT = 'DEBUTANT', INTERMEDIAIRE = 'INTERMEDIAIRE', AVANCE = 'AVANCE' }
export enum InscriptionStatut { ACTIF = 'ACTIF', COMPLETE = 'COMPLETE', ABANDONNE = 'ABANDONNE', SUSPENDU = 'SUSPENDU' }
export enum EvaluationType  { QUIZ = 'QUIZ', EXAMEN_FINAL = 'EXAMEN_FINAL', DEVOIR = 'DEVOIR' }
export enum FormateurStatut { EN_ATTENTE = 'EN_ATTENTE', VALIDE = 'VALIDE', REJETE = 'REJETE', SUSPENDU = 'SUSPENDU' }
export enum NotifType       { INSCRIPTION = 'INSCRIPTION', PROGRESSION = 'PROGRESSION', QUIZ = 'QUIZ', CERTIFICAT = 'CERTIFICAT', MESSAGE = 'MESSAGE', SYSTEME = 'SYSTEME' }
*/

// ── API Response Wrapper Helper ────────────────────────────
/*
export class ApiResponseDto<T> {
  success: boolean;
  data: T;
  message?: string;
  timestamp: string;
  pagination?: {
    page: number;
    limit: number;
    total: number;
    totalPages: number;
  };

  static ok<T>(data: T, message?: string): ApiResponseDto<T> {
    return { success: true, data, message, timestamp: new Date().toISOString() };
  }

  static paginated<T>(data: T[], total: number, page: number, limit: number): ApiResponseDto<T[]> {
    return {
      success: true,
      data,
      timestamp: new Date().toISOString(),
      pagination: { page, limit, total, totalPages: Math.ceil(total / limit) },
    };
  }
}
*/
