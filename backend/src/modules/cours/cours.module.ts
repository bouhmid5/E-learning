// ============================================================
// EduNova — Cours Module (Courses)
// ============================================================

// ── src/modules/cours/entities/cours.entity.ts ────────────
/*
import {
  Entity, PrimaryGeneratedColumn, Column, ManyToOne, OneToMany,
  CreateDateColumn, UpdateDateColumn, JoinColumn, Index,
} from 'typeorm';
import { Formateur }    from '../../utilisateurs/entities/formateur.entity';
import { Categorie }    from '../../categories/entities/categorie.entity';
import { Section }      from './section.entity';
import { Inscription }  from '../../inscriptions/entities/inscription.entity';
import { Evaluation }   from '../../evaluations/entities/evaluation.entity';
import { Avis }         from './avis.entity';

export enum CoursNiveau    { DEBUTANT = 'DEBUTANT', INTERMEDIAIRE = 'INTERMEDIAIRE', AVANCE = 'AVANCE' }
export enum CoursStatut    { BROUILLON = 'BROUILLON', EN_REVISION = 'EN_REVISION', PUBLIE = 'PUBLIE', ARCHIVE = 'ARCHIVE', REJETE = 'REJETE' }

@Entity('cours')
@Index(['statut', 'categorieId'])
export class Cours {
  @PrimaryGeneratedColumn('uuid') id: string;

  @Column({ length: 300 })                         titre: string;
  @Column({ length: 300, unique: true })            slug: string;
  @Column({ type: 'text' })                         description: string;
  @Column({ length: 500, nullable: true })          descriptionCourte: string;

  @Column()                                         formateurId: string;
  @Column()                                         categorieId: string;

  @Column({ type: 'enum', enum: CoursNiveau })      niveau: CoursNiveau;
  @Column({ length: 10, default: 'fr' })            langue: string;
  @Column({ type: 'decimal', precision: 10, scale: 2, default: 0 }) prix: number;
  @Column({ type: 'decimal', precision: 10, scale: 2, nullable: true }) prixPromo: number;

  @Column({ length: 500, nullable: true })          imageUrl: string;
  @Column({ length: 500, nullable: true })          videoIntro: string;

  @Column({ type: 'enum', enum: CoursStatut, default: CoursStatut.BROUILLON }) statut: CoursStatut;

  @Column({ type: 'int', unsigned: true, default: 0 }) dureeTotal: number;
  @Column({ type: 'int', unsigned: true, default: 0 }) nombreLecons: number;

  @Column({ type: 'json', nullable: true })         objectifs: string[];
  @Column({ type: 'json', nullable: true })         prerequis: string[];
  @Column({ type: 'json', nullable: true })         tags: string[];

  @Column({ type: 'decimal', precision: 3, scale: 2, default: 0 }) noteGlobale: number;
  @Column({ type: 'int', unsigned: true, default: 0 }) nombreAvis: number;
  @Column({ type: 'int', unsigned: true, default: 0 }) nombreInscrits: number;
  @Column({ type: 'int', unsigned: true, default: 0 }) nombreCertifs: number;
  @Column({ type: 'int', unsigned: true, default: 70 }) scoreMini: number;

  @Column({ default: true })   certifAuto: boolean;
  @Column({ default: false })  isBestseller: boolean;
  @Column({ default: false })  isFeatured: boolean;

  @Column({ nullable: true })                       valideParId: string;
  @Column({ type: 'datetime', nullable: true })     valideAt: Date;
  @Column({ type: 'text', nullable: true })         raisonRejet: string;
  @Column({ type: 'datetime', nullable: true })     publishedAt: Date;

  @CreateDateColumn() createdAt: Date;
  @UpdateDateColumn() updatedAt: Date;

  // Relations
  @ManyToOne(() => Formateur,   f  => f.cours,        { eager: false }) @JoinColumn({ name: 'formateurId' }) formateur: Formateur;
  @ManyToOne(() => Categorie,   c  => c.cours,        { eager: false }) @JoinColumn({ name: 'categorieId' }) categorie: Categorie;
  @OneToMany(() => Section,     s  => s.cours,        { cascade: true }) sections: Section[];
  @OneToMany(() => Inscription, i  => i.cours)        inscriptions: Inscription[];
  @OneToMany(() => Evaluation,  ev => ev.cours)       evaluations: Evaluation[];
  @OneToMany(() => Avis,        av => av.cours)       avis: Avis[];
}
*/

// ── src/modules/cours/dto/create-cours.dto.ts ─────────────
/*
import {
  IsString, IsEnum, IsNumber, IsOptional, IsBoolean,
  IsArray, Min, Max, MinLength, MaxLength, IsUrl,
} from 'class-validator';
import { ApiProperty } from '@nestjs/swagger';
import { CoursNiveau } from '../entities/cours.entity';
import { Transform } from 'class-transformer';

export class CreateCoursDto {
  @ApiProperty({ example: 'Machine Learning A-Z' })
  @IsString() @MinLength(5) @MaxLength(300)
  titre: string;

  @ApiProperty()
  @IsString() @MinLength(50)
  description: string;

  @ApiProperty({ required: false })
  @IsOptional() @IsString() @MaxLength(500)
  descriptionCourte?: string;

  @ApiProperty()
  @IsString()
  categorieId: string;

  @ApiProperty({ enum: CoursNiveau })
  @IsEnum(CoursNiveau)
  niveau: CoursNiveau;

  @ApiProperty({ default: 'fr' })
  @IsOptional() @IsString()
  langue?: string;

  @ApiProperty({ default: 0 })
  @IsNumber() @Min(0)
  prix: number;

  @ApiProperty({ required: false })
  @IsOptional() @IsNumber() @Min(0)
  prixPromo?: number;

  @ApiProperty({ required: false })
  @IsOptional() @IsUrl()
  imageUrl?: string;

  @ApiProperty({ required: false })
  @IsOptional() @IsUrl()
  videoIntro?: string;

  @ApiProperty({ type: [String], required: false })
  @IsOptional() @IsArray() @IsString({ each: true })
  objectifs?: string[];

  @ApiProperty({ type: [String], required: false })
  @IsOptional() @IsArray() @IsString({ each: true })
  prerequis?: string[];

  @ApiProperty({ type: [String], required: false })
  @IsOptional() @IsArray() @IsString({ each: true })
  tags?: string[];

  @ApiProperty({ default: 70 })
  @IsOptional() @IsNumber() @Min(0) @Max(100)
  scoreMini?: number;
}
*/

// ── src/modules/cours/cours.service.ts ────────────────────
/*
import {
  Injectable, NotFoundException, ForbiddenException,
  BadRequestException, ConflictException,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, SelectQueryBuilder, Like, In } from 'typeorm';
import slugify from 'slugify';
import { Cours, CoursStatut, CoursNiveau } from './entities/cours.entity';
import { CreateCoursDto } from './dto/create-cours.dto';
import { UpdateCoursDto } from './dto/update-cours.dto';
import { FilterCoursDto } from './dto/filter-cours.dto';

@Injectable()
export class CoursService {
  constructor(
    @InjectRepository(Cours) private readonly coursRepo: Repository<Cours>,
  ) {}

  async create(dto: CreateCoursDto, formateurId: string): Promise<Cours> {
    const slug = await this.generateUniqueSlug(dto.titre);
    const cours = this.coursRepo.create({ ...dto, formateurId, slug, statut: CoursStatut.BROUILLON });
    return this.coursRepo.save(cours);
  }

  async findAll(filter: FilterCoursDto) {
    const { page = 1, limit = 12, categorie, niveau, search, sortBy = 'createdAt', order = 'DESC' } = filter;
    const skip = (page - 1) * limit;

    const qb: SelectQueryBuilder<Cours> = this.coursRepo
      .createQueryBuilder('c')
      .leftJoinAndSelect('c.formateur', 'f')
      .leftJoinAndSelect('f.utilisateur', 'u')
      .leftJoinAndSelect('c.categorie', 'cat')
      .where('c.statut = :statut', { statut: CoursStatut.PUBLIE });

    if (categorie) qb.andWhere('cat.slug = :categorie', { categorie });
    if (niveau)    qb.andWhere('c.niveau = :niveau', { niveau });
    if (search)    qb.andWhere('(c.titre LIKE :search OR c.description LIKE :search)', { search: `%${search}%` });

    qb.orderBy(`c.${sortBy}`, order as 'ASC' | 'DESC');
    qb.skip(skip).take(limit);

    const [data, total] = await qb.getManyAndCount();
    return { data, total, page, limit, totalPages: Math.ceil(total / limit) };
  }

  async findOneBySlug(slug: string): Promise<Cours> {
    const cours = await this.coursRepo.findOne({
      where: { slug, statut: CoursStatut.PUBLIE },
      relations: ['formateur', 'formateur.utilisateur', 'categorie', 'sections', 'sections.lecons'],
    });
    if (!cours) throw new NotFoundException('Cours non trouvé');
    return cours;
  }

  async findById(id: string): Promise<Cours> {
    const cours = await this.coursRepo.findOne({ where: { id }, relations: ['formateur', 'categorie'] });
    if (!cours) throw new NotFoundException('Cours non trouvé');
    return cours;
  }

  async update(id: string, dto: UpdateCoursDto, formateurId: string): Promise<Cours> {
    const cours = await this.findById(id);
    if (cours.formateurId !== formateurId) throw new ForbiddenException('Accès refusé');
    if (cours.statut === CoursStatut.PUBLIE) {
      // Reset to draft on edit
      await this.coursRepo.update(id, { ...dto, statut: CoursStatut.BROUILLON });
    } else {
      await this.coursRepo.update(id, dto);
    }
    return this.findById(id);
  }

  async submitForReview(id: string, formateurId: string) {
    const cours = await this.findById(id);
    if (cours.formateurId !== formateurId) throw new ForbiddenException();
    if (cours.statut !== CoursStatut.BROUILLON) throw new BadRequestException('Le cours doit être en brouillon pour être soumis');
    await this.coursRepo.update(id, { statut: CoursStatut.EN_REVISION });
    return { message: 'Cours soumis pour révision avec succès.' };
  }

  async approve(id: string, adminId: string) {
    const cours = await this.findById(id);
    if (cours.statut !== CoursStatut.EN_REVISION) throw new BadRequestException('Le cours doit être en révision');
    await this.coursRepo.update(id, {
      statut: CoursStatut.PUBLIE,
      valideParId: adminId,
      valideAt: new Date(),
      publishedAt: new Date(),
    });
    return { message: 'Cours approuvé et publié.' };
  }

  async reject(id: string, adminId: string, raison: string) {
    await this.coursRepo.update(id, { statut: CoursStatut.REJETE, raisonRejet: raison });
    return { message: 'Cours rejeté.' };
  }

  async getFormateurCours(formateurId: string) {
    return this.coursRepo.find({
      where: { formateurId },
      order: { createdAt: 'DESC' },
      relations: ['categorie'],
    });
  }

  async updateStats(id: string) {
    // Recalculate rating from avis table
    const result = await this.coursRepo.query(
      'SELECT AVG(note) as avg, COUNT(*) as cnt FROM avis WHERE coursId = ?', [id],
    );
    if (result[0]) {
      await this.coursRepo.update(id, {
        noteGlobale: parseFloat(result[0].avg) || 0,
        nombreAvis: parseInt(result[0].cnt) || 0,
      });
    }
  }

  private async generateUniqueSlug(titre: string): Promise<string> {
    let slug = slugify(titre, { lower: true, strict: true });
    const existing = await this.coursRepo.findOne({ where: { slug } });
    if (existing) slug = `${slug}-${Date.now()}`;
    return slug;
  }
}
*/

// ── src/modules/cours/cours.controller.ts ─────────────────
/*
import {
  Controller, Get, Post, Put, Patch, Delete, Body, Param,
  Query, UseGuards, Request, HttpCode, HttpStatus,
} from '@nestjs/common';
import { ApiTags, ApiBearerAuth, ApiOperation, ApiQuery } from '@nestjs/swagger';
import { CoursService }    from './cours.service';
import { CreateCoursDto }  from './dto/create-cours.dto';
import { UpdateCoursDto }  from './dto/update-cours.dto';
import { FilterCoursDto }  from './dto/filter-cours.dto';
import { Public }          from '../../common/decorators/public.decorator';
import { Roles }           from '../../common/decorators/roles.decorator';
import { RolesGuard }      from '../../common/guards/roles.guard';

@ApiTags('Cours')
@Controller('cours')
export class CoursController {
  constructor(private readonly coursService: CoursService) {}

  // ── Public endpoints ──────────────────────────────────────
  @Public()
  @Get()
  @ApiOperation({ summary: 'Get all published courses with filters' })
  findAll(@Query() filter: FilterCoursDto) {
    return this.coursService.findAll(filter);
  }

  @Public()
  @Get(':slug')
  @ApiOperation({ summary: 'Get course by slug with full details' })
  findOne(@Param('slug') slug: string) {
    return this.coursService.findOneBySlug(slug);
  }

  // ── Trainer endpoints ─────────────────────────────────────
  @Post()
  @Roles('FORMATEUR')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @ApiOperation({ summary: 'Create a new course (trainer only)' })
  create(@Body() dto: CreateCoursDto, @Request() req) {
    return this.coursService.create(dto, req.user.id);
  }

  @Put(':id')
  @Roles('FORMATEUR')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @ApiOperation({ summary: 'Update course (owner trainer only)' })
  update(@Param('id') id: string, @Body() dto: UpdateCoursDto, @Request() req) {
    return this.coursService.update(id, dto, req.user.id);
  }

  @Patch(':id/soumettre')
  @Roles('FORMATEUR')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Submit course for admin review' })
  submit(@Param('id') id: string, @Request() req) {
    return this.coursService.submitForReview(id, req.user.id);
  }

  @Get('formateur/mes-cours')
  @Roles('FORMATEUR')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @ApiOperation({ summary: 'Get all courses by authenticated trainer' })
  mesCours(@Request() req) {
    return this.coursService.getFormateurCours(req.user.id);
  }

  // ── Admin endpoints ───────────────────────────────────────
  @Patch(':id/approuver')
  @Roles('ADMIN')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Approve and publish a course (admin only)' })
  approve(@Param('id') id: string, @Request() req) {
    return this.coursService.approve(id, req.user.id);
  }

  @Patch(':id/rejeter')
  @Roles('ADMIN')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Reject a course (admin only)' })
  reject(
    @Param('id') id: string,
    @Body('raison') raison: string,
    @Request() req,
  ) {
    return this.coursService.reject(id, req.user.id, raison);
  }
}
*/
