{{-- <div class="flex items-center space-x-2">
    <img src="{{ asset('images/jesa-logo.png') }}" class="h-8" alt="JESA Logo">
    <span class="font-bold text-lg text-gray-800">JESA CONNECT</span>
</div>
 --}}
<div id="brand" style="display:flex;align-items:center;gap:.5rem">
    {{-- Light mode (logo sombre + texte noir) --}}
    <img id="logo-light" src="{{ asset('images/jesa-logo-dark.png') }}" alt="JESA Logo (light)" style="height:32px">
    {{-- Dark mode (logo clair + texte blanc) --}}
    <img id="logo-dark"  src="{{ asset('images/jesa-logo.png') }}"      alt="JESA Logo (dark)"  style="height:32px;display:none">
  <span id="brand-text"
      style="
        font: 700 28px/1.3 'Poppins','Segoe UI',sans-serif;
        letter-spacing:.03em;
        text-transform: uppercase;
        color:#0d1b2a;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        display:inline-block;
      ">
  JESA CONNECT
</span>

</div>

<script>
(function () {
  const html = document.documentElement;
  const lightLogo = document.getElementById('logo-light');
  const darkLogo  = document.getElementById('logo-dark');
  const text      = document.getElementById('brand-text');

  function getStoredTheme() {
    const keys = ['theme', 'color-theme', 'filament-theme', 'filamentTheme', 'filament:theme'];
    for (const k of keys) {
      const v = localStorage.getItem(k);
      if (v && typeof v === 'string') {
        if (v.toLowerCase().includes('dark')) return 'dark';
        if (v.toLowerCase().includes('light')) return 'light';
      }
    }
    return null;
  }

  function detectTheme() {
    // 1) Priorité aux marqueurs sur <html>
    if (html.classList.contains('dark') || html.dataset.theme === 'dark') return 'dark';
    if (html.dataset.theme === 'light') return 'light';

    // 2) Clé locale éventuelle
    const stored = getStoredTheme();
    if (stored) return stored;

    // 3) Préférence système
    try {
      return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    } catch { return 'light'; }
  }

  function applyTheme(mode) {
    const isDark = mode === 'dark';
    if (isDark) {
      // Dark => logo clair + texte blanc
      lightLogo.style.display = 'none';
      darkLogo.style.display  = 'inline-block';
      if (text) text.style.color = '#f3f4f6';
    } else {
      // Light => logo sombre + texte noir
      lightLogo.style.display = 'inline-block';
      darkLogo.style.display  = 'none';
      if (text) text.style.color = '#111';
    }
  }

  // Initial
  applyTheme(detectTheme());

  // Réagit au changement système
  if (window.matchMedia) {
    try {
      const mm = window.matchMedia('(prefers-color-scheme: dark)');
      mm.addEventListener?.('change', e => applyTheme(e.matches ? 'dark' : 'light'));
    } catch {}
  }

  // Réagit au changement du <html> (class / data-theme)
  const obs = new MutationObserver(() => applyTheme(detectTheme()));
  obs.observe(html, { attributes: true, attributeFilter: ['class', 'data-theme'] });

  // Réagit aux changements dans localStorage (si autre onglet/toggle)
  window.addEventListener('storage', () => applyTheme(detectTheme()));
})();
</script>
