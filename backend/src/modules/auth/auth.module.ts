// ============================================================
// EduNova — Auth Module
// ============================================================

// ── src/modules/auth/dto/register.dto.ts ──────────────────
import { IsEmail, IsEnum, IsNotEmpty, IsString, MinLength, IsOptional } from 'class-validator';
import { ApiProperty } from '@nestjs/swagger';

export enum RoleEnum { CANDIDAT = 'CANDIDAT', FORMATEUR = 'FORMATEUR' }

export class RegisterDto {
  @ApiProperty({ example: 'Sara' })
  @IsString() @IsNotEmpty()
  prenom: string;

  @ApiProperty({ example: 'Aissaoui' })
  @IsString() @IsNotEmpty()
  nom: string;

  @ApiProperty({ example: 'sara@example.com' })
  @IsEmail()
  email: string;

  @ApiProperty({ example: 'SecurePass123!', minLength: 8 })
  @IsString() @MinLength(8)
  motDePasse: string;

  @ApiProperty({ enum: RoleEnum })
  @IsEnum(RoleEnum)
  role: RoleEnum;

  @ApiProperty({ required: false })
  @IsOptional() @IsString()
  specialite?: string;

  @ApiProperty({ required: false })
  @IsOptional() @IsString()
  objectifApprentissage?: string;
}

export class LoginDto {
  @ApiProperty({ example: 'sara@example.com' })
  @IsEmail()
  email: string;

  @ApiProperty({ example: 'SecurePass123!' })
  @IsString() @IsNotEmpty()
  motDePasse: string;
}

export class RefreshTokenDto {
  @IsString() @IsNotEmpty()
  refreshToken: string;
}

export class ForgotPasswordDto {
  @IsEmail()
  email: string;
}

export class ResetPasswordDto {
  @IsString() @IsNotEmpty()
  token: string;

  @IsString() @MinLength(8)
  nouveauMotDePasse: string;
}

export class ChangePasswordDto {
  @IsString() @IsNotEmpty()
  ancienMotDePasse: string;

  @IsString() @MinLength(8)
  nouveauMotDePasse: string;
}

// ── src/modules/auth/strategies/jwt.strategy.ts ───────────
/*
import { Injectable, UnauthorizedException } from '@nestjs/common';
import { PassportStrategy } from '@nestjs/passport';
import { ExtractJwt, Strategy } from 'passport-jwt';
import { ConfigService } from '@nestjs/config';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Utilisateur } from '../utilisateurs/entities/utilisateur.entity';

@Injectable()
export class JwtStrategy extends PassportStrategy(Strategy) {
  constructor(
    private readonly cfg: ConfigService,
    @InjectRepository(Utilisateur)
    private readonly userRepo: Repository<Utilisateur>,
  ) {
    super({
      jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
      ignoreExpiration: false,
      secretOrKey: cfg.get<string>('jwt.secret'),
    });
  }

  async validate(payload: { sub: string; email: string; role: string }) {
    const user = await this.userRepo.findOne({
      where: { id: payload.sub, actif: true },
    });
    if (!user) throw new UnauthorizedException('Token invalide ou compte désactivé');
    return { id: payload.sub, email: payload.email, role: payload.role };
  }
}
*/

// ── src/modules/auth/auth.service.ts ──────────────────────
/*
import {
  Injectable, ConflictException, UnauthorizedException,
  NotFoundException, BadRequestException,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, DataSource } from 'typeorm';
import { JwtService } from '@nestjs/jwt';
import { ConfigService } from '@nestjs/config';
import * as bcrypt from 'bcrypt';
import { v4 as uuidv4 } from 'uuid';
import { Utilisateur } from '../utilisateurs/entities/utilisateur.entity';
import { Candidat }    from '../utilisateurs/entities/candidat.entity';
import { Formateur }   from '../utilisateurs/entities/formateur.entity';
import { RegisterDto, LoginDto, RoleEnum } from './dto/register.dto';
import { MailService } from '../mail/mail.service';

@Injectable()
export class AuthService {
  constructor(
    @InjectRepository(Utilisateur) private userRepo: Repository<Utilisateur>,
    @InjectRepository(Candidat)    private candidatRepo: Repository<Candidat>,
    @InjectRepository(Formateur)   private formateurRepo: Repository<Formateur>,
    private readonly jwtService: JwtService,
    private readonly cfg: ConfigService,
    private readonly dataSource: DataSource,
    private readonly mailService: MailService,
  ) {}

  async register(dto: RegisterDto) {
    // Check existing email
    const existing = await this.userRepo.findOne({ where: { email: dto.email } });
    if (existing) throw new ConflictException('Cet email est déjà utilisé');

    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      // Hash password
      const hash = await bcrypt.hash(dto.motDePasse, 12);
      const tokenVerif = uuidv4();

      // Create utilisateur
      const user = queryRunner.manager.create(Utilisateur, {
        prenom:      dto.prenom,
        nom:         dto.nom,
        email:       dto.email,
        motDePasse:  hash,
        role:        dto.role,
        tokenVerif,
      });
      await queryRunner.manager.save(user);

      // Create role-specific profile
      if (dto.role === RoleEnum.CANDIDAT) {
        const candidat = queryRunner.manager.create(Candidat, {
          utilisateurId:          user.id,
          objectifApprentissage:  dto.objectifApprentissage,
        });
        await queryRunner.manager.save(candidat);
      } else {
        const formateur = queryRunner.manager.create(Formateur, {
          utilisateurId: user.id,
          specialite:    dto.specialite,
          statut:        'EN_ATTENTE',
        });
        await queryRunner.manager.save(formateur);
      }

      await queryRunner.commitTransaction();

      // Send verification email
      await this.mailService.sendVerificationEmail(user.email, user.prenom, tokenVerif);

      return { message: 'Compte créé. Vérifiez votre email pour activer votre compte.' };
    } catch (err) {
      await queryRunner.rollbackTransaction();
      throw err;
    } finally {
      await queryRunner.release();
    }
  }

  async login(dto: LoginDto) {
    const user = await this.userRepo.findOne({ where: { email: dto.email } });
    if (!user) throw new UnauthorizedException('Identifiants invalides');
    if (!user.actif) throw new UnauthorizedException('Compte désactivé');
    if (!user.emailVerifie) throw new UnauthorizedException('Veuillez vérifier votre email avant de vous connecter');

    const valid = await bcrypt.compare(dto.motDePasse, user.motDePasse);
    if (!valid) throw new UnauthorizedException('Identifiants invalides');

    // Update last login
    await this.userRepo.update(user.id, { dernierConnexion: new Date() });

    return this.generateTokens(user);
  }

  async refreshTokens(refreshToken: string) {
    try {
      const payload = this.jwtService.verify(refreshToken, {
        secret: this.cfg.get<string>('jwt.refreshSecret'),
      });
      const user = await this.userRepo.findOne({ where: { id: payload.sub } });
      if (!user || user.refreshToken !== refreshToken)
        throw new UnauthorizedException();
      return this.generateTokens(user);
    } catch {
      throw new UnauthorizedException('Refresh token invalide ou expiré');
    }
  }

  async verifyEmail(token: string) {
    const user = await this.userRepo.findOne({ where: { tokenVerif: token } });
    if (!user) throw new BadRequestException('Token de vérification invalide');
    await this.userRepo.update(user.id, { emailVerifie: true, tokenVerif: null });
    return { message: 'Email vérifié avec succès. Vous pouvez maintenant vous connecter.' };
  }

  async forgotPassword(email: string) {
    const user = await this.userRepo.findOne({ where: { email } });
    if (!user) return { message: 'Si cet email existe, un lien de réinitialisation a été envoyé.' };
    const token = uuidv4();
    const expiry = new Date(Date.now() + 3600000); // 1 hour
    await this.userRepo.update(user.id, { tokenReset: token, tokenResetExpiry: expiry });
    await this.mailService.sendPasswordReset(user.email, user.prenom, token);
    return { message: 'Si cet email existe, un lien de réinitialisation a été envoyé.' };
  }

  async resetPassword(token: string, newPassword: string) {
    const user = await this.userRepo.findOne({ where: { tokenReset: token } });
    if (!user || user.tokenResetExpiry < new Date())
      throw new BadRequestException('Token invalide ou expiré');
    const hash = await bcrypt.hash(newPassword, 12);
    await this.userRepo.update(user.id, {
      motDePasse: hash, tokenReset: null, tokenResetExpiry: null,
    });
    return { message: 'Mot de passe réinitialisé avec succès.' };
  }

  async logout(userId: string) {
    await this.userRepo.update(userId, { refreshToken: null });
    return { message: 'Déconnecté avec succès.' };
  }

  private async generateTokens(user: Utilisateur) {
    const payload = { sub: user.id, email: user.email, role: user.role };
    const [accessToken, refreshToken] = await Promise.all([
      this.jwtService.signAsync(payload, {
        secret: this.cfg.get('jwt.secret'),
        expiresIn: this.cfg.get('jwt.expiresIn'),
      }),
      this.jwtService.signAsync(payload, {
        secret: this.cfg.get('jwt.refreshSecret'),
        expiresIn: this.cfg.get('jwt.refreshExpiresIn'),
      }),
    ]);
    await this.userRepo.update(user.id, { refreshToken });
    return {
      accessToken,
      refreshToken,
      user: {
        id:     user.id,
        email:  user.email,
        prenom: user.prenom,
        nom:    user.nom,
        role:   user.role,
        avatar: user.avatar,
        xp:     user.xp,
      },
    };
  }
}
*/

// ── src/modules/auth/auth.controller.ts ───────────────────
/*
import {
  Controller, Post, Body, Get, Query,
  UseGuards, Request, HttpCode, HttpStatus,
} from '@nestjs/common';
import { ApiTags, ApiBearerAuth, ApiOperation, ApiResponse } from '@nestjs/swagger';
import { Throttle } from '@nestjs/throttler';
import { AuthService } from './auth.service';
import {
  RegisterDto, LoginDto, RefreshTokenDto,
  ForgotPasswordDto, ResetPasswordDto, ChangePasswordDto,
} from './dto/register.dto';
import { Public }    from '../../common/decorators/public.decorator';
import { JwtAuthGuard } from '../../common/guards/jwt-auth.guard';

@ApiTags('Auth')
@Controller('auth')
export class AuthController {
  constructor(private readonly authService: AuthService) {}

  @Public()
  @Post('register')
  @Throttle({ default: { ttl: 60000, limit: 5 } })
  @ApiOperation({ summary: 'Register a new user (candidate or trainer)' })
  @ApiResponse({ status: 201, description: 'Account created successfully' })
  @ApiResponse({ status: 409, description: 'Email already in use' })
  register(@Body() dto: RegisterDto) {
    return this.authService.register(dto);
  }

  @Public()
  @Post('login')
  @HttpCode(HttpStatus.OK)
  @Throttle({ default: { ttl: 60000, limit: 10 } })
  @ApiOperation({ summary: 'Login with email and password' })
  login(@Body() dto: LoginDto) {
    return this.authService.login(dto);
  }

  @Public()
  @Post('refresh')
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Refresh access token' })
  refresh(@Body() dto: RefreshTokenDto) {
    return this.authService.refreshTokens(dto.refreshToken);
  }

  @Public()
  @Get('verify-email')
  @ApiOperation({ summary: 'Verify email address' })
  verifyEmail(@Query('token') token: string) {
    return this.authService.verifyEmail(token);
  }

  @Public()
  @Post('forgot-password')
  @HttpCode(HttpStatus.OK)
  @Throttle({ default: { ttl: 60000, limit: 3 } })
  @ApiOperation({ summary: 'Request password reset email' })
  forgotPassword(@Body() dto: ForgotPasswordDto) {
    return this.authService.forgotPassword(dto.email);
  }

  @Public()
  @Post('reset-password')
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Reset password with token' })
  resetPassword(@Body() dto: ResetPasswordDto) {
    return this.authService.resetPassword(dto.token, dto.nouveauMotDePasse);
  }

  @Post('logout')
  @UseGuards(JwtAuthGuard)
  @ApiBearerAuth()
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Logout and invalidate tokens' })
  logout(@Request() req) {
    return this.authService.logout(req.user.id);
  }

  @Get('me')
  @UseGuards(JwtAuthGuard)
  @ApiBearerAuth()
  @ApiOperation({ summary: 'Get current authenticated user' })
  me(@Request() req) {
    return req.user;
  }
}
*/
