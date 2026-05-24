/* ============================================================
   E-LEARNING PLATFORM — Main JavaScript
   Handles: Navbar, Sidebar, Toasts, Modals, Animations,
            Counters, Charts, Filters, Tabs, Dark/Light Mode
   ============================================================ */

'use strict';

// ── Utility ──────────────────────────────────────────────────
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];
const on = (el, ev, fn, opts) => el?.addEventListener(ev, fn, opts);

// ── Navbar scroll effect ──────────────────────────────────────
(function initNavbar() {
  const nav = $('.navbar');
  if (!nav) return;
  const update = () => nav.classList.toggle('scrolled', window.scrollY > 20);
  on(window, 'scroll', update, { passive: true });
  update();
})();

// ── Hamburger / Mobile Sidebar ────────────────────────────────
(function initHamburger() {
  const btn = $('.hamburger');
  const sidebar = $('.sidebar');
  const overlay = $('#sidebar-overlay');
  if (!btn) return;
  const toggle = (open) => {
    sidebar?.classList.toggle('open', open);
    overlay?.classList.toggle('hidden', !open);
    btn.setAttribute('aria-expanded', String(open));
  };
  on(btn, 'click', () => toggle(!sidebar?.classList.contains('open')));
  on(overlay, 'click', () => toggle(false));
  on(document, 'keydown', e => { if (e.key === 'Escape') toggle(false); });
})();

// ── Toast Notifications ───────────────────────────────────────
window.Toast = (function() {
  let container = null;
  function getContainer() {
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }
    return container;
  }
  function show({ title = '', msg = '', type = 'info', duration = 4000 } = {}) {
    const icons = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
    const c = getContainer();
    const t = document.createElement('div');
    t.className = 'toast';
    t.innerHTML = `
      <span class="toast-icon">${icons[type] || icons.info}</span>
      <div class="toast-content">
        ${title ? `<div class="toast-title">${title}</div>` : ''}
        ${msg ? `<div class="toast-msg">${msg}</div>` : ''}
      </div>
      <button class="toast-close" aria-label="Close">×</button>`;
    c.appendChild(t);
    const close = () => {
      t.classList.add('removing');
      setTimeout(() => t.remove(), 200);
    };
    on($('.toast-close', t), 'click', close);
    if (duration > 0) setTimeout(close, duration);
    return { close };
  }
  return {
    success: (title, msg) => show({ title, msg, type: 'success' }),
    error:   (title, msg) => show({ title, msg, type: 'error' }),
    warning: (title, msg) => show({ title, msg, type: 'warning' }),
    info:    (title, msg) => show({ title, msg, type: 'info' }),
  };
})();

// ── Modal ─────────────────────────────────────────────────────
window.Modal = (function() {
  function open(id) {
    const m = $(`#modal-${id}`);
    if (!m) return;
    m.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
  function close(id) {
    const m = id ? $(`#modal-${id}`) : $('.modal-overlay');
    if (!m) return;
    m.classList.add('hidden');
    document.body.style.overflow = '';
  }
  $$('[data-modal-open]').forEach(btn => on(btn, 'click', () => open(btn.dataset.modalOpen)));
  $$('[data-modal-close]').forEach(btn => on(btn, 'click', () => close(btn.dataset.modalClose)));
  $$('.modal-overlay').forEach(ov => on(ov, 'click', e => { if (e.target === ov) close(); }));
  on(document, 'keydown', e => { if (e.key === 'Escape') close(); });
  return { open, close };
})();

// ── Tabs ──────────────────────────────────────────────────────
$$('[data-tabs]').forEach(tabGroup => {
  const buttons = $$('.tab-btn', tabGroup);
  const panels  = $$('[data-tab-panel]', document);
  buttons.forEach(btn => {
    on(btn, 'click', () => {
      buttons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const target = btn.dataset.tab;
      panels.forEach(p => p.classList.toggle('hidden', p.dataset.tabPanel !== target));
    });
  });
});

// ── Animated Counters ─────────────────────────────────────────
function animateCounter(el) {
  const target = parseFloat(el.dataset.count);
  const suffix = el.dataset.suffix || '';
  const prefix = el.dataset.prefix || '';
  const duration = 1500;
  const start = performance.now();
  const isDecimal = target % 1 !== 0;
  const isLarge = target >= 10000;
  function step(now) {
    const elapsed = now - start;
    const progress = Math.min(elapsed / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3);
    const value = target * eased;
    let display;
    if (isDecimal) {
      display = value.toFixed(1);
    } else if (isLarge) {
      display = Math.round(value).toLocaleString();
    } else {
      display = Math.round(value).toString();
    }
    el.textContent = prefix + display + suffix;
    if (progress < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}

// ── Intersection Observer for animations ──────────────────────
(function initObserver() {
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      el.classList.add('visible');
      // Also clear inline styles in case they were set
      el.style.opacity = '';
      el.style.transform = '';
      if (el.hasAttribute('data-count')) animateCounter(el);
      io.unobserve(el);
    });
  }, { threshold: 0.1 });

  $$('[data-animate], [data-count]').forEach(el => {
    el.style.transitionDelay = (el.dataset.delay || '0') + 'ms';
    io.observe(el);
  });
})();

// ── Progress Bar Animation ────────────────────────────────────
$$('[data-progress]').forEach(bar => {
  const fill = $('.progress-bar-fill', bar.parentElement);
  if (!fill) return;
  const io = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) {
      setTimeout(() => { fill.style.width = bar.dataset.progress + '%'; }, 200);
      io.unobserve(bar);
    }
  }, { threshold: 0.3 });
  io.observe(bar);
});

// ── Filter Tags ───────────────────────────────────────────────
$$('.filter-group').forEach(group => {
  const tags = $$('.tag', group);
  const mode = group.dataset.mode || 'single';
  tags.forEach(tag => {
    on(tag, 'click', () => {
      if (mode === 'single') { tags.forEach(t => t.classList.remove('active')); tag.classList.add('active'); }
      else tag.classList.toggle('active');
      const selected = $$('.tag.active', group).map(t => t.dataset.value);
      const event = new CustomEvent('filter:change', { detail: { group: group.id, values: selected }, bubbles: true });
      group.dispatchEvent(event);
    });
  });
});

// ── Search with debounce ──────────────────────────────────────
function debounce(fn, ms) {
  let timer;
  return (...args) => { clearTimeout(timer); timer = setTimeout(() => fn(...args), ms); };
}
$$('[data-search]').forEach(input => {
  const target = input.dataset.search;
  const items = $$(`[data-searchable="${target}"]`);
  const handle = debounce(e => {
    const q = e.target.value.toLowerCase().trim();
    items.forEach(item => {
      const text = item.textContent.toLowerCase();
      item.style.display = q === '' || text.includes(q) ? '' : 'none';
    });
  }, 250);
  on(input, 'input', handle);
});

// ── Dropdown toggles ──────────────────────────────────────────
$$('[data-dropdown]').forEach(trigger => {
  const menu = $(`#${trigger.dataset.dropdown}`);
  if (!menu) return;
  on(trigger, 'click', e => { e.stopPropagation(); menu.classList.toggle('hidden'); });
  on(document, 'click', () => menu.classList.add('hidden'));
});

// ── Course Card hover tilt effect ─────────────────────────────
$$('.course-card').forEach(card => {
  on(card, 'mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    card.style.transform = `translateY(-5px) perspective(800px) rotateY(${x * 6}deg) rotateX(${-y * 4}deg)`;
  });
  on(card, 'mouseleave', () => { card.style.transform = ''; });
});

// ── Simple Mini Chart (Canvas) ────────────────────────────────
function drawLineChart(canvas, data, color = '#00e5ff') {
  const ctx = canvas.getContext('2d');
  const w = canvas.width, h = canvas.height;
  const max = Math.max(...data);
  const min = Math.min(...data);
  const range = max - min || 1;
  const pad = 8;
  ctx.clearRect(0, 0, w, h);
  // Gradient fill
  const gradient = ctx.createLinearGradient(0, pad, 0, h - pad);
  gradient.addColorStop(0, color + '40');
  gradient.addColorStop(1, color + '00');
  const points = data.map((v, i) => ({
    x: pad + (i / (data.length - 1)) * (w - 2 * pad),
    y: h - pad - ((v - min) / range) * (h - 2 * pad)
  }));
  // Area
  ctx.beginPath();
  ctx.moveTo(points[0].x, h - pad);
  points.forEach(p => ctx.lineTo(p.x, p.y));
  ctx.lineTo(points[points.length - 1].x, h - pad);
  ctx.closePath();
  ctx.fillStyle = gradient;
  ctx.fill();
  // Line
  ctx.beginPath();
  ctx.moveTo(points[0].x, points[0].y);
  points.forEach(p => ctx.lineTo(p.x, p.y));
  ctx.strokeStyle = color;
  ctx.lineWidth = 2.5;
  ctx.lineJoin = 'round';
  ctx.stroke();
  // Dots
  points.forEach(p => {
    ctx.beginPath();
    ctx.arc(p.x, p.y, 3.5, 0, Math.PI * 2);
    ctx.fillStyle = color;
    ctx.fill();
    ctx.beginPath();
    ctx.arc(p.x, p.y, 1.5, 0, Math.PI * 2);
    ctx.fillStyle = '#080c14';
    ctx.fill();
  });
}

function drawDonutChart(canvas, value, color = '#7c3aed', track = 'rgba(255,255,255,0.06)') {
  const ctx = canvas.getContext('2d');
  const cx = canvas.width / 2, cy = canvas.height / 2;
  const r = Math.min(cx, cy) - 8;
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.lineWidth = 10;
  ctx.lineCap = 'round';
  // Track
  ctx.beginPath();
  ctx.arc(cx, cy, r, 0, Math.PI * 2);
  ctx.strokeStyle = track;
  ctx.stroke();
  // Progress
  const angle = (value / 100) * Math.PI * 2 - Math.PI / 2;
  ctx.beginPath();
  ctx.arc(cx, cy, r, -Math.PI / 2, angle);
  ctx.strokeStyle = color;
  ctx.stroke();
  // Text
  ctx.font = `bold ${Math.round(r * 0.45)}px Syne, sans-serif`;
  ctx.fillStyle = '#f0f4ff';
  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
  ctx.fillText(value + '%', cx, cy);
}

function drawBarChart(canvas, labels, values, color = '#7c3aed') {
  const ctx = canvas.getContext('2d');
  const w = canvas.width, h = canvas.height;
  const max = Math.max(...values) || 1;
  const gap = 8, barW = (w - gap * (labels.length + 1)) / labels.length;
  ctx.clearRect(0, 0, w, h);
  labels.forEach((label, i) => {
    const x = gap + i * (barW + gap);
    const barH = ((values[i] / max) * (h - 30));
    const y = h - 20 - barH;
    // Bar
    const gr = ctx.createLinearGradient(0, y, 0, h - 20);
    gr.addColorStop(0, color);
    gr.addColorStop(1, color + '40');
    ctx.fillStyle = gr;
    ctx.beginPath();
    ctx.roundRect(x, y, barW, barH, 4);
    ctx.fill();
    // Label
    ctx.fillStyle = '#4b5563';
    ctx.font = '10px DM Sans, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText(label, x + barW / 2, h - 5);
  });
}

// Initialize charts on page — use rAF to ensure layout is complete
function initCharts() {
  $$('[data-chart-line]').forEach(canvas => {
    const data = JSON.parse(canvas.dataset.chartLine);
    const color = canvas.dataset.color || '#00e5ff';
    const rect = canvas.getBoundingClientRect();
    canvas.width  = rect.width  || canvas.offsetWidth  || 300;
    canvas.height = rect.height || canvas.offsetHeight || 80;
    if (canvas.width > 0) drawLineChart(canvas, data, color);
  });
  $$('[data-chart-donut]').forEach(canvas => {
    const value = parseInt(canvas.dataset.chartDonut);
    const color = canvas.dataset.color || '#7c3aed';
    drawDonutChart(canvas, value, color);
  });
  $$('[data-chart-bar]').forEach(canvas => {
    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const values = JSON.parse(canvas.dataset.chartBar);
    const color  = canvas.dataset.color || '#7c3aed';
    const rect = canvas.getBoundingClientRect();
    canvas.width  = rect.width  || canvas.offsetWidth  || 300;
    canvas.height = rect.height || canvas.offsetHeight || 120;
    if (canvas.width > 0) drawBarChart(canvas, labels, values, color);
  });
}
document.addEventListener('DOMContentLoaded', () => {
  requestAnimationFrame(() => setTimeout(initCharts, 50));
});

// ── Sidebar active link ───────────────────────────────────────
(function highlightSidebarLink() {
  const current = window.location.pathname.split('/').pop();
  $$('.sidebar-nav-item a').forEach(link => {
    const href = link.getAttribute('href')?.split('/').pop();
    link.classList.toggle('active', href === current);
  });
})();

// ── Copy to clipboard ─────────────────────────────────────────
$$('[data-copy]').forEach(btn => {
  on(btn, 'click', async () => {
    const text = btn.dataset.copy;
    try {
      await navigator.clipboard.writeText(text);
      const orig = btn.textContent;
      btn.textContent = '✓ Copied!';
      setTimeout(() => { btn.textContent = orig; }, 2000);
      Toast.success('Copied!', 'Content copied to clipboard.');
    } catch { Toast.error('Error', 'Could not copy to clipboard.'); }
  });
});

// ── Course enrollment simulation ──────────────────────────────
$$('[data-enroll]').forEach(btn => {
  on(btn, 'click', () => {
    const courseId = btn.dataset.enroll;
    const isEnrolled = btn.classList.contains('enrolled');
    if (isEnrolled) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Enrolling…';
    setTimeout(() => {
      btn.innerHTML = '✅ Enrolled';
      btn.classList.add('enrolled', 'btn-ghost');
      btn.classList.remove('btn-cyan');
      Toast.success('Enrolled!', 'You have successfully enrolled in this course.');
      // Update enrollment count if present
      const count = $(`[data-enrollments="${courseId}"]`);
      if (count) count.textContent = (parseInt(count.textContent) + 1).toLocaleString();
    }, 1200);
  });
});

// ── Quiz logic ───────────────────────────────────────────────
(function initQuiz() {
  const form = $('#quiz-form');
  if (!form) return;
  $$('.quiz-option', form).forEach(opt => {
    on(opt, 'click', () => {
      const name = opt.dataset.name;
      $$(`[data-name="${name}"]`, form).forEach(o => o.classList.remove('selected'));
      opt.classList.add('selected');
      const radio = $('input[type="radio"]', opt);
      if (radio) radio.checked = true;
    });
  });
  on(form, 'submit', e => {
    e.preventDefault();
    let score = 0, total = 0;
    $$('.quiz-question', form).forEach(q => {
      total++;
      const correct = q.dataset.correct;
      const selected = $(`[data-name="${q.dataset.name}"].selected`, q);
      if (selected?.dataset.value === correct) { selected.classList.add('correct'); score++; }
      else {
        selected?.classList.add('wrong');
        $(`[data-name="${q.dataset.name}"][data-value="${correct}"]`, q)?.classList.add('correct');
      }
    });
    const pct = Math.round((score / total) * 100);
    Toast[pct >= 70 ? 'success' : 'warning'](
      `Score: ${pct}%`,
      `You got ${score}/${total} correct.`
    );
    $('#quiz-result')?.classList.remove('hidden');
    const scoreEl = $('#quiz-score');
    if (scoreEl) { scoreEl.dataset.count = pct; scoreEl.dataset.suffix = '%'; animateCounter(scoreEl); }
    $$('.quiz-option', form).forEach(o => o.style.pointerEvents = 'none');
  });
})();

// ── Video Lesson Navigation ───────────────────────────────────
(function initLessonNav() {
  const lessonItems = $$('[data-lesson]');
  if (!lessonItems.length) return;
  lessonItems.forEach(item => {
    on(item, 'click', () => {
      lessonItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const title = item.dataset.lesson;
      const titleEl = $('#lesson-title');
      if (titleEl) titleEl.textContent = title;
    });
  });
})();

// ── Notification panel ────────────────────────────────────────
(function initNotifPanel() {
  const btn = $('#notif-btn');
  const panel = $('#notif-panel');
  if (!btn || !panel) return;
  on(btn, 'click', e => { e.stopPropagation(); panel.classList.toggle('hidden'); });
  on(document, 'click', e => { if (!panel.contains(e.target)) panel.classList.add('hidden'); });
})();

// ── Accordion ────────────────────────────────────────────────
$$('[data-accordion]').forEach(item => {
  const trigger = $('.accordion-trigger', item);
  const content = $('.accordion-content', item);
  if (!trigger || !content) return;
  on(trigger, 'click', () => {
    const open = item.classList.toggle('open');
    content.style.maxHeight = open ? content.scrollHeight + 'px' : '0';
    const icon = $('.accordion-icon', trigger);
    if (icon) icon.style.transform = open ? 'rotate(180deg)' : '';
  });
  content.style.maxHeight = '0';
  content.style.overflow = 'hidden';
  content.style.transition = 'max-height 300ms ease';
});

// ── Star Rating ───────────────────────────────────────────────
$$('[data-rating]').forEach(widget => {
  const stars = $$('.rating-star', widget);
  let current = 0;
  stars.forEach((star, i) => {
    on(star, 'mouseenter', () => { stars.forEach((s,j) => s.classList.toggle('hovered', j <= i)); });
    on(star, 'mouseleave', () => { stars.forEach(s => s.classList.remove('hovered')); });
    on(star, 'click', () => {
      current = i + 1;
      stars.forEach((s,j) => s.classList.toggle('active', j < current));
      widget.dataset.value = current;
      const input = $(`#${widget.dataset.rating}`);
      if (input) input.value = current;
    });
  });
});

// ── File Upload Preview ───────────────────────────────────────
$$('[data-upload]').forEach(input => {
  const preview = $(`#${input.dataset.upload}`);
  if (!preview) return;
  on(input, 'change', e => {
    const file = e.target.files[0];
    if (!file) return;
    preview.textContent = `📎 ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
    preview.classList.remove('hidden');
    Toast.info('File selected', file.name);
  });
});

// ── Typewriter effect for hero ────────────────────────────────
(function initTypewriter() {
  const el = $('[data-typewriter]');
  if (!el) return;
  const phrases = JSON.parse(el.dataset.typewriter);
  let phraseIdx = 0, charIdx = 0, deleting = false;
  function type() {
    const current = phrases[phraseIdx];
    el.textContent = current.slice(0, deleting ? --charIdx : ++charIdx);
    let delay = deleting ? 40 : 80;
    if (!deleting && charIdx === current.length) { delay = 2000; deleting = true; }
    else if (deleting && charIdx === 0) { deleting = false; phraseIdx = (phraseIdx + 1) % phrases.length; delay = 400; }
    setTimeout(type, delay);
  }
  type();
})();

console.info('🎓 EduNova Platform v1.0 — Ready');
