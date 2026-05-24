// ============================================================
// EduNova — Inscriptions, Certificats & Evaluations Modules
// ============================================================

// ══════════════════════════════════════════════════════════════
// INSCRIPTIONS MODULE
// ══════════════════════════════════════════════════════════════

// ── src/modules/inscriptions/inscriptions.service.ts ──────
/*
import {
  Injectable, ConflictException, NotFoundException, ForbiddenException,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Inscription }       from './entities/inscription.entity';
import { ProgressionLecon }  from './entities/progression-lecon.entity';
import { Cours }             from '../cours/entities/cours.entity';
import { Candidat }          from '../utilisateurs/entities/candidat.entity';
import { NotificationsService } from '../notifications/notifications.service';
import { BadgesService }     from '../badges/badges.service';

@Injectable()
export class InscriptionsService {
  constructor(
    @InjectRepository(Inscription)      private inscriptionRepo: Repository<Inscription>,
    @InjectRepository(ProgressionLecon) private progressionRepo: Repository<ProgressionLecon>,
    @InjectRepository(Cours)            private coursRepo: Repository<Cours>,
    @InjectRepository(Candidat)         private candidatRepo: Repository<Candidat>,
    private readonly notificationsService: NotificationsService,
    private readonly badgesService: BadgesService,
  ) {}

  async inscrire(candidatId: string, coursId: string): Promise<Inscription> {
    // Check existing enrollment
    const existing = await this.inscriptionRepo.findOne({ where: { candidatId, coursId } });
    if (existing) throw new ConflictException('Vous êtes déjà inscrit à ce cours');

    // Verify course exists and is published
    const cours = await this.coursRepo.findOne({ where: { id: coursId, statut: 'PUBLIE' } });
    if (!cours) throw new NotFoundException('Cours non trouvé ou non disponible');

    // Create enrollment
    const inscription = this.inscriptionRepo.create({ candidatId, coursId, statut: 'ACTIF' });
    const saved = await this.inscriptionRepo.save(inscription);

    // Update course enrollment count
    await this.coursRepo.increment({ id: coursId }, 'nombreInscrits', 1);

    // Send notification
    await this.notificationsService.create({
      utilisateurId: candidatId,
      type: 'INSCRIPTION',
      titre: 'Inscription confirmée ! 🎉',
      message: `Vous êtes maintenant inscrit à "${cours.titre}". Bonne formation !`,
      lien: `/cours/${cours.slug}`,
    });

    // Check "First Enrollment" badge
    await this.badgesService.checkAndAward(candidatId, 'INSCRIPTION');

    return saved;
  }

  async desinscrire(candidatId: string, coursId: string) {
    const inscription = await this.inscriptionRepo.findOne({ where: { candidatId, coursId } });
    if (!inscription) throw new NotFoundException('Inscription non trouvée');
    if (inscription.statut === 'COMPLETE') throw new ForbiddenException('Impossible de se désinscrire d\'un cours terminé');
    await this.inscriptionRepo.update(inscription.id, { statut: 'ABANDONNE' });
    await this.coursRepo.decrement({ id: coursId }, 'nombreInscrits', 1);
    return { message: 'Désinscription effectuée.' };
  }

  async getMesInscriptions(candidatId: string) {
    return this.inscriptionRepo.find({
      where: { candidatId },
      relations: ['cours', 'cours.categorie', 'cours.formateur', 'cours.formateur.utilisateur'],
      order: { derniereConnexionCours: 'DESC' },
    });
  }

  async getInscription(candidatId: string, coursId: string): Promise<Inscription> {
    const insc = await this.inscriptionRepo.findOne({
      where: { candidatId, coursId },
      relations: ['cours'],
    });
    if (!insc) throw new NotFoundException('Inscription non trouvée');
    return insc;
  }

  async updateProgression(inscriptionId: string, leconId: string, candidatId: string) {
    const inscription = await this.inscriptionRepo.findOne({
      where: { id: inscriptionId, candidatId },
      relations: ['cours', 'cours.sections', 'cours.sections.lecons'],
    });
    if (!inscription) throw new NotFoundException('Inscription non trouvée');

    // Mark lesson complete
    let prog = await this.progressionRepo.findOne({
      where: { inscriptionId, leconId },
    });
    if (!prog) {
      prog = this.progressionRepo.create({ inscriptionId, leconId, complete: false });
    }
    if (!prog.complete) {
      prog.complete = true;
      prog.completedAt = new Date();
      await this.progressionRepo.save(prog);

      // Recalculate overall progression
      const totalLecons = inscription.cours.sections
        .reduce((acc, s) => acc + s.lecons.length, 0);
      const completedCount = await this.progressionRepo.count({
        where: { inscriptionId, complete: true },
      });
      const pct = totalLecons > 0 ? (completedCount / totalLecons) * 100 : 0;

      const updates: Partial<Inscription> = {
        progression: pct,
        derniereConnexionCours: new Date(),
        leconActuelle: leconId,
      };
      if (pct >= 100) updates.statut = 'COMPLETE';

      await this.inscriptionRepo.update(inscriptionId, updates);

      // Award XP to user
      await this.badgesService.addXP(candidatId, 10);

      // Auto-issue certificate if complete
      if (pct >= 100) {
        await this.issuerCertificatAuto(inscription);
      }
    }

    return { progression: prog };
  }

  async getProgressionLecons(inscriptionId: string) {
    return this.progressionRepo.find({ where: { inscriptionId } });
  }

  private async issuerCertificatAuto(inscription: Inscription) {
    // Trigger certificate generation (handled by CertificatsService)
    // This would emit an event or call the service directly
  }

  async getEtudiantsByCours(coursId: string, formateurId: string) {
    // Verify trainer owns the course
    const cours = await this.coursRepo.findOne({ where: { id: coursId, formateurId } });
    if (!cours) throw new ForbiddenException('Accès refusé');

    return this.inscriptionRepo.find({
      where: { coursId },
      relations: ['candidat', 'candidat.utilisateur'],
      order: { progression: 'DESC' },
    });
  }
}
*/

// ── src/modules/inscriptions/inscriptions.controller.ts ───
/*
import {
  Controller, Post, Delete, Get, Param, Body,
  UseGuards, Request, Patch,
} from '@nestjs/common';
import { ApiTags, ApiBearerAuth, ApiOperation } from '@nestjs/swagger';
import { InscriptionsService } from './inscriptions.service';
import { RolesGuard } from '../../common/guards/roles.guard';
import { Roles }      from '../../common/decorators/roles.decorator';

@ApiTags('Inscriptions')
@ApiBearerAuth()
@Controller('inscriptions')
export class InscriptionsController {
  constructor(private readonly svc: InscriptionsService) {}

  @Post(':coursId')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiOperation({ summary: 'Enroll in a course' })
  inscrire(@Param('coursId') coursId: string, @Request() req) {
    return this.svc.inscrire(req.user.id, coursId);
  }

  @Delete(':coursId')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiOperation({ summary: 'Unenroll from a course' })
  desinscrire(@Param('coursId') coursId: string, @Request() req) {
    return this.svc.desinscrire(req.user.id, coursId);
  }

  @Get('mes-cours')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiOperation({ summary: 'Get all enrollments for authenticated candidate' })
  mesCours(@Request() req) {
    return this.svc.getMesInscriptions(req.user.id);
  }

  @Get(':coursId/details')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiOperation({ summary: 'Get enrollment details for a specific course' })
  details(@Param('coursId') coursId: string, @Request() req) {
    return this.svc.getInscription(req.user.id, coursId);
  }

  @Patch(':inscriptionId/lecon/:leconId/complete')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiOperation({ summary: 'Mark a lesson as complete and update progress' })
  completeLecon(
    @Param('inscriptionId') inscriptionId: string,
    @Param('leconId') leconId: string,
    @Request() req,
  ) {
    return this.svc.updateProgression(inscriptionId, leconId, req.user.id);
  }

  @Get(':inscriptionId/progression-lecons')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiOperation({ summary: 'Get lesson-level progress for an enrollment' })
  progressionLecons(@Param('inscriptionId') inscriptionId: string) {
    return this.svc.getProgressionLecons(inscriptionId);
  }

  @Get('cours/:coursId/etudiants')
  @Roles('FORMATEUR')
  @UseGuards(RolesGuard)
  @ApiOperation({ summary: 'Get all students enrolled in a trainer course' })
  etudiants(@Param('coursId') coursId: string, @Request() req) {
    return this.svc.getEtudiantsByCours(coursId, req.user.id);
  }
}
*/

// ══════════════════════════════════════════════════════════════
// CERTIFICATS MODULE
// ══════════════════════════════════════════════════════════════

// ── src/modules/certificats/certificats.service.ts ────────
/*
import {
  Injectable, NotFoundException, BadRequestException, ConflictException,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { v4 as uuidv4 } from 'uuid';
import * as QRCode  from 'qrcode';
import * as PDFKit  from 'pdfkit';
import { Certificat }   from './entities/certificat.entity';
import { Inscription }  from '../inscriptions/entities/inscription.entity';
import { NotificationsService } from '../notifications/notifications.service';
import { StorageService }       from '../upload/storage.service';
import { BadgesService }        from '../badges/badges.service';

@Injectable()
export class CertificatsService {
  constructor(
    @InjectRepository(Certificat)  private certRepo: Repository<Certificat>,
    @InjectRepository(Inscription) private inscriptionRepo: Repository<Inscription>,
    private readonly notificationsService: NotificationsService,
    private readonly storageService: StorageService,
    private readonly badgesService: BadgesService,
  ) {}

  async generer(inscriptionId: string, candidatId: string): Promise<Certificat> {
    const inscription = await this.inscriptionRepo.findOne({
      where: { id: inscriptionId, candidatId },
      relations: ['cours', 'cours.formateur', 'cours.formateur.utilisateur', 'candidat', 'candidat.utilisateur'],
    });
    if (!inscription) throw new NotFoundException('Inscription non trouvée');
    if (inscription.progression < 100) throw new BadRequestException('Le cours n\'est pas encore terminé');

    // Check if certificate already exists
    const existing = await this.certRepo.findOne({ where: { inscriptionId } });
    if (existing) return existing;

    // Generate unique verification code
    const codeVerification = `EDN-${new Date().getFullYear()}-${uuidv4().substring(0,8).toUpperCase()}`;

    // Generate QR Code
    const verifyUrl = `https://edunova.com/verify/${codeVerification}`;
    const qrBuffer = await QRCode.toBuffer(verifyUrl, { width: 200, margin: 2 });
    const qrUrl = await this.storageService.uploadBuffer(qrBuffer, `qr/${codeVerification}.png`, 'image/png');

    // Generate PDF Certificate
    const pdfBuffer = await this.generateCertificatePDF({
      candidatNom: `${inscription.candidat.utilisateur.prenom} ${inscription.candidat.utilisateur.nom}`,
      coursTitre: inscription.cours.titre,
      formateurNom: `${inscription.cours.formateur.utilisateur.prenom} ${inscription.cours.formateur.utilisateur.nom}`,
      score: inscription.scoreGlobal || 100,
      dateEmission: new Date(),
      codeVerification,
      qrCodeUrl: qrUrl,
    });
    const pdfUrl = await this.storageService.uploadBuffer(pdfBuffer, `certificats/${codeVerification}.pdf`, 'application/pdf');

    // Calculate completion duration in days
    const startDate  = inscription.createdAt;
    const endDate    = new Date();
    const dureeCompletion = Math.round((endDate.getTime() - startDate.getTime()) / (1000 * 60 * 60 * 24));

    // Save certificate
    const cert = this.certRepo.create({
      inscriptionId,
      candidatId,
      coursId: inscription.coursId,
      codeVerification,
      qrCodeUrl: qrUrl,
      pdfUrl,
      scoreObtenu: inscription.scoreGlobal || 100,
      dureeCompletion,
    });
    const saved = await this.certRepo.save(cert);

    // Update inscription
    await this.inscriptionRepo.update(inscriptionId, { certificatDisponible: true });

    // Send notification
    await this.notificationsService.create({
      utilisateurId: candidatId,
      type: 'CERTIFICAT',
      titre: '🎓 Certificat disponible !',
      message: `Félicitations ! Votre certificat pour "${inscription.cours.titre}" est prêt à télécharger.`,
      lien: `/candidat/certificats`,
    });

    // Award certificate badge
    await this.badgesService.checkAndAward(candidatId, 'CERTIFICAT');
    await this.badgesService.addXP(candidatId, 200);

    return saved;
  }

  async getMesCertificats(candidatId: string) {
    return this.certRepo.find({
      where: { candidatId, valide: true },
      relations: ['cours', 'cours.categorie', 'cours.formateur', 'cours.formateur.utilisateur'],
      order: { dateEmission: 'DESC' },
    });
  }

  async verifier(code: string) {
    const cert = await this.certRepo.findOne({
      where: { codeVerification: code, valide: true },
      relations: ['cours', 'candidat', 'candidat.utilisateur'],
    });
    if (!cert) throw new NotFoundException('Certificat invalide ou non trouvé');
    return {
      valide: true,
      candidat: `${cert.candidat.utilisateur.prenom} ${cert.candidat.utilisateur.nom}`,
      cours: cert.cours.titre,
      score: cert.scoreObtenu,
      dateEmission: cert.dateEmission,
      codeVerification: cert.codeVerification,
    };
  }

  async telecharger(code: string, candidatId: string) {
    const cert = await this.certRepo.findOne({ where: { codeVerification: code, candidatId } });
    if (!cert) throw new NotFoundException('Certificat non trouvé');
    return { downloadUrl: cert.pdfUrl };
  }

  private async generateCertificatePDF(data: {
    candidatNom: string; coursTitre: string; formateurNom: string;
    score: number; dateEmission: Date; codeVerification: string; qrCodeUrl: string;
  }): Promise<Buffer> {
    // In production: use PDFKit or Puppeteer to generate a beautiful PDF
    // This is a placeholder that returns a minimal PDF buffer
    const doc = new PDFKit({ size: 'A4', layout: 'landscape' });
    const buffers: Buffer[] = [];
    doc.on('data', buffers.push.bind(buffers));
    return new Promise((resolve, reject) => {
      doc.on('end', () => resolve(Buffer.concat(buffers)));
      doc.on('error', reject);

      // Background & styling
      doc.rect(0, 0, 841, 595).fill('#080c14');
      doc.rect(20, 20, 801, 555).stroke('#7c3aed').lineWidth(2);
      doc.rect(25, 25, 791, 545).stroke('rgba(0,229,255,0.3)').lineWidth(1);

      // Content
      doc.fillColor('#f0f4ff').fontSize(14).font('Helvetica')
         .text('EduNova Platform', 0, 60, { align: 'center' });
      doc.fillColor('#f0f4ff').fontSize(36).font('Helvetica-Bold')
         .text('Certificate of Completion', 0, 100, { align: 'center' });
      doc.fillColor('#8892a4').fontSize(14)
         .text('This is to certify that', 0, 170, { align: 'center' });
      doc.fillColor('#00e5ff').fontSize(32).font('Helvetica-Bold')
         .text(data.candidatNom, 0, 200, { align: 'center' });
      doc.fillColor('#8892a4').fontSize(14).font('Helvetica')
         .text('has successfully completed', 0, 250, { align: 'center' });
      doc.fillColor('#f0f4ff').fontSize(22).font('Helvetica-Bold')
         .text(data.coursTitre, 60, 280, { align: 'center', width: 721 });
      doc.fillColor('#8892a4').fontSize(12).font('Helvetica')
         .text(`Score: ${data.score}% | Date: ${data.dateEmission.toLocaleDateString('fr-FR')}`, 0, 350, { align: 'center' });
      doc.fillColor('rgba(0,229,255,0.4)').fontSize(10)
         .text(`Verification Code: ${data.codeVerification}`, 0, 520, { align: 'center' });

      doc.end();
    });
  }
}
*/

// ── src/modules/certificats/certificats.controller.ts ─────
/*
import { Controller, Post, Get, Param, UseGuards, Request } from '@nestjs/common';
import { ApiTags, ApiBearerAuth, ApiOperation } from '@nestjs/swagger';
import { CertificatsService } from './certificats.service';
import { RolesGuard } from '../../common/guards/roles.guard';
import { Roles }      from '../../common/decorators/roles.decorator';
import { Public }     from '../../common/decorators/public.decorator';

@ApiTags('Certificats')
@Controller('certificats')
export class CertificatsController {
  constructor(private readonly svc: CertificatsService) {}

  @Post('generer/:inscriptionId')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @ApiOperation({ summary: 'Generate certificate for completed course' })
  generer(@Param('inscriptionId') id: string, @Request() req) {
    return this.svc.generer(id, req.user.id);
  }

  @Get('mes-certificats')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @ApiOperation({ summary: 'Get all certificates for authenticated candidate' })
  mesCertificats(@Request() req) {
    return this.svc.getMesCertificats(req.user.id);
  }

  @Public()
  @Get('verifier/:code')
  @ApiOperation({ summary: 'Publicly verify a certificate by code' })
  verifier(@Param('code') code: string) {
    return this.svc.verifier(code);
  }

  @Get('telecharger/:code')
  @Roles('CANDIDAT')
  @UseGuards(RolesGuard)
  @ApiBearerAuth()
  @ApiOperation({ summary: 'Get download URL for a certificate PDF' })
  telecharger(@Param('code') code: string, @Request() req) {
    return this.svc.telecharger(code, req.user.id);
  }
}
*/

// ══════════════════════════════════════════════════════════════
// EVALUATIONS MODULE
// ══════════════════════════════════════════════════════════════

// ── src/modules/evaluations/evaluations.service.ts ────────
/*
import {
  Injectable, NotFoundException, ForbiddenException, BadRequestException,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Evaluation }            from './entities/evaluation.entity';
import { QuestionQuiz }          from './entities/question-quiz.entity';
import { SoumissionEvaluation }  from './entities/soumission-evaluation.entity';
import { Inscription }           from '../inscriptions/entities/inscription.entity';
import { NotificationsService }  from '../notifications/notifications.service';
import { BadgesService }         from '../badges/badges.service';

@Injectable()
export class EvaluationsService {
  constructor(
    @InjectRepository(Evaluation)           private evalRepo: Repository<Evaluation>,
    @InjectRepository(QuestionQuiz)         private questionRepo: Repository<QuestionQuiz>,
    @InjectRepository(SoumissionEvaluation) private soumissionRepo: Repository<SoumissionEvaluation>,
    @InjectRepository(Inscription)          private inscriptionRepo: Repository<Inscription>,
    private readonly notificationsService: NotificationsService,
    private readonly badgesService: BadgesService,
  ) {}

  async getEvaluationsByCours(coursId: string) {
    return this.evalRepo.find({
      where: { coursId, actif: true },
      order: { ordre: 'ASC' },
    });
  }

  async getEvaluationWithQuestions(evalId: string, candidatId: string) {
    const eval_ = await this.evalRepo.findOne({
      where: { id: evalId, actif: true },
      relations: ['questions'],
    });
    if (!eval_) throw new NotFoundException('Évaluation non trouvée');

    // Check enrollment
    const inscription = await this.inscriptionRepo.findOne({
      where: { coursId: eval_.coursId, candidatId, statut: 'ACTIF' },
    });
    if (!inscription) throw new ForbiddenException('Vous n\'êtes pas inscrit à ce cours');

    // Check attempt limit
    const attempts = await this.soumissionRepo.count({
      where: { evaluationId: evalId, candidatId },
    });
    if (attempts >= eval_.tentativesMax)
      throw new BadRequestException(`Nombre maximum de tentatives atteint (${eval_.tentativesMax})`);

    // Shuffle questions if enabled
    let questions = eval_.questions;
    if (eval_.melangerQuestions) {
      questions = questions.sort(() => Math.random() - 0.5);
    }

    // Create in-progress submission
    const soumission = this.soumissionRepo.create({
      evaluationId: evalId,
      candidatId,
      inscriptionId: inscription.id,
      tentativeNum: attempts + 1,
      statut: 'EN_COURS',
    });
    await this.soumissionRepo.save(soumission);

    return { evaluation: { ...eval_, questions }, soumissionId: soumission.id };
  }

  async soumettre(soumissionId: string, candidatId: string, reponses: Record<string, string>) {
    const soumission = await this.soumissionRepo.findOne({
      where: { id: soumissionId, candidatId, statut: 'EN_COURS' },
      relations: ['evaluation', 'evaluation.questions'],
    });
    if (!soumission) throw new NotFoundException('Session de quiz non trouvée ou déjà soumise');

    // Auto-correct QCM/VRAI_FAUX questions
    const evaluation = soumission.evaluation;
    let totalPoints = 0, earnedPoints = 0;
    const correction: Record<string, any> = {};

    for (const question of evaluation.questions) {
      totalPoints += question.points;
      const reponseCandidat = reponses[question.id];
      let correct = false;

      if (question.type === 'QCM' || question.type === 'VRAI_FAUX') {
        const correctOption = (question.options as any[]).find(o => o.isCorrect);
        correct = reponseCandidat === correctOption?.text;
        if (correct) earnedPoints += question.points;
      } else if (question.type === 'REPONSE_COURTE') {
        // Simple case-insensitive match
        correct = reponseCandidat?.toLowerCase().trim() === question.reponseCorrecte?.toLowerCase().trim();
        if (correct) earnedPoints += question.points;
      }

      correction[question.id] = {
        reponseCandidat,
        correcte: correct,
        reponseCorrecte: question.reponseCorrecte,
        explication: question.explication,
        points: correct ? question.points : 0,
      };
    }

    const score = totalPoints > 0 ? (earnedPoints / totalPoints) * 100 : 0;
    const reussi = score >= evaluation.scoreMinimum;

    await this.soumissionRepo.update(soumissionId, {
      reponses,
      correction,
      score,
      reussi,
      statut: 'CORRIGE',
      soumisAt: new Date(),
      corrigeAt: new Date(),
    });

    // Award XP for passing
    if (reussi) {
      const xp = score >= 90 ? 150 : 100;
      await this.badgesService.addXP(candidatId, xp);
      if (score >= 90) await this.badgesService.checkAndAward(candidatId, 'QUIZ_MASTER');

      // Notify
      await this.notificationsService.create({
        utilisateurId: candidatId,
        type: 'QUIZ',
        titre: `Quiz réussi ! ${score.toFixed(0)}% 🎯`,
        message: `Vous avez obtenu ${score.toFixed(0)}% et gagné ${xp} XP.`,
      });
    }

    return {
      score,
      reussi,
      scoreMinimum: evaluation.scoreMinimum,
      correction: evaluation.afficherReponses ? correction : null,
      message: reussi ? `Félicitations ! Vous avez réussi avec ${score.toFixed(0)}%.` : `Score: ${score.toFixed(0)}%. Minimum requis: ${evaluation.scoreMinimum}%.`,
    };
  }

  async getMesResultats(candidatId: string) {
    return this.soumissionRepo.find({
      where: { candidatId },
      relations: ['evaluation', 'evaluation.cours'],
      order: { commenceAt: 'DESC' },
    });
  }

  // Trainer: create evaluation
  async creerEvaluation(dto: any, formateurId: string) {
    const evaluation = this.evalRepo.create({ ...dto, formateurId });
    return this.evalRepo.save(evaluation);
  }

  // Trainer: add questions
  async ajouterQuestion(evalId: string, dto: any) {
    const q = this.questionRepo.create({ ...dto, evaluationId: evalId });
    return this.questionRepo.save(q);
  }
}
*/
