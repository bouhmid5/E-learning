/* ============================================================
   EduNova — Auth & User Session Manager
   Handles: save/load user from localStorage, inject user
   info into any page that has data-user-* attributes.
   ============================================================ */

'use strict';

// ── Storage key ───────────────────────────────────────────────
const USER_KEY = 'edunova_user';

// ── User helpers ──────────────────────────────────────────────
const Auth = {
  /** Save a user object to localStorage */
  save(user) {
    localStorage.setItem(USER_KEY, JSON.stringify(user));
  },

  /** Load user from localStorage, or null */
  load() {
    try {
      const raw = localStorage.getItem(USER_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch { return null; }
  },

  /** Clear session (logout) */
  clear() {
    localStorage.removeItem(USER_KEY);
  },

  /** Get initials from a full name — e.g. "Rabie Baccouch" → "RB" */
  initials(name = '') {
    return name.trim().split(/\s+/).slice(0, 2)
      .map(w => w[0].toUpperCase()).join('');
  },

  /** Redirect if not logged in */
  require(redirectTo = '../pages/login.html') {
    if (!Auth.load()) window.location.href = redirectTo;
  },

  /** Inject user data into the current page */
  inject() {
    const user = Auth.load();
    if (!user) return;

    const initials = Auth.initials(user.name);
    const xpLabel  = user.xp   ? `· ${user.xp.toLocaleString()} XP` : '';
    const levelLabel = { DEBUTANT: '🌱 Beginner', INTERMEDIAIRE: '⚡ Intermediate', AVANCE: '🔥 Advanced' }[user.level] || '🌱 Beginner';

    // ── Text fields ───────────────────────────────────────────
    _all('[data-user-name]').forEach(el => { el.textContent = user.name; });
    _all('[data-user-firstname]').forEach(el => { el.textContent = user.name.split(' ')[0]; });
    _all('[data-user-email]').forEach(el => { el.textContent = user.email; });
    _all('[data-user-role]').forEach(el => { el.textContent = user.role === 'trainer' ? '👩‍🏫 Trainer' : levelLabel + ' ' + xpLabel; });
    _all('[data-user-initials]').forEach(el => { el.textContent = initials; });
    _all('[data-user-xp]').forEach(el => { el.textContent = (user.xp || 0).toLocaleString(); });
    _all('[data-user-level]').forEach(el => { el.textContent = levelLabel; });

    // ── Avatar backgrounds — candidate = violet/cyan, trainer = blue ──
    const avatarBg = user.role === 'trainer'
      ? 'linear-gradient(135deg,#0891b2,#00e5ff)'
      : 'linear-gradient(135deg,#7c3aed,#00e5ff)';
    _all('[data-user-avatar]').forEach(el => {
      el.textContent = initials;
      el.style.background = avatarBg;
      el.style.color = '#fff';
    });

    // ── Welcome message ───────────────────────────────────────
    _all('[data-user-welcome]').forEach(el => {
      el.textContent = `Welcome back, ${user.name.split(' ')[0]} 👋`;
    });
  }
};

function _all(sel) { return [...document.querySelectorAll(sel)]; }

// Auto-inject on every page load
document.addEventListener('DOMContentLoaded', () => Auth.inject());

window.Auth = Auth;
