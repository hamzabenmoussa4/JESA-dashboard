{{-- 
@php
  /** @var string $subtitle  Texte sous le logo (ex: "JESA CONNECT | USER LOGIN") */
  $subtitle = $subtitle ?? 'WELCOME TO JESA CONNECT';
@endphp

<style>
  /* --- Page-load overlay (logo + hamburger), avec image de fond --- */
  #intro-overlay{
    position:fixed; inset:0;
    /* Couleur de secours + image avec vignette pour lisibilité */
    background-color:#0b1220;
    background-image:
      radial-gradient(120% 80% at 50% 40%, rgba(11,18,32,0.25) 0%, rgba(11,18,32,0.55) 58%, rgba(11,18,32,0.85) 100%),
      url('{{ asset('images/hero.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    display:grid; place-items:center; z-index:9999;
    pointer-events:none;
    animation: overlayOut .4s ease forwards 2.0s; /* ← disparaît après ~2s */
  }
  .intro-stack{
    display:flex; flex-direction:column; align-items:center; gap:16px;
  }
  .intro-logo{
    width:96px; height:96px; object-fit:contain;
    opacity:0; transform: translateY(8px) scale(.9);
    filter: drop-shadow(0 6px 16px rgba(0,0,0,.45));
    animation: logoReveal 1s ease-out forwards .45s;
  }
  .intro-subtitle{
    color:#e5e7eb; font-weight:700; letter-spacing:.12em; text-align:center;
    font-size:14px; text-transform:uppercase; opacity:0; transform: translateY(6px);
    animation: subtitleIn .9s ease-out forwards 1.0s;
    white-space:nowrap;
  }
  .intro-hamburger{
    width:90px; height:64px;
    display:flex; flex-direction:column; justify-content:space-between;
    margin-top:4px;
  }
  .intro-line{ height:4px; width:0; background:#e5e7eb; border-radius:4px; }
  .intro-line:nth-child(1){ animation: lineTop 1.7s ease forwards; }
  .intro-line:nth-child(2){ animation: lineMiddle 1.7s ease forwards .08s; }
  .intro-line:nth-child(3){ animation: lineBottom 1.7s ease forwards .16s; }

  @keyframes logoReveal{ to { opacity:1; transform: translateY(0) scale(1); } }
  @keyframes subtitleIn{ to { opacity:1; transform: translateY(0); } }
  @keyframes lineTop{
    0%{width:0;opacity:0;transform:translateY(0)}
    40%{width:90px;opacity:1}
    70%{width:90px}
    100%{width:0;opacity:0;transform:translateY(-26px)}
  }
  @keyframes lineMiddle{
    0%{width:0;opacity:0}
    40%{width:90px;opacity:1}
    70%{width:90px}
    100%{width:0;opacity:0}
  }
  @keyframes lineBottom{
    0%{width:0;opacity:0;transform:translateY(0)}
    40%{width:90px;opacity:1}
    70%{width:90px}
    100%{width:0;opacity:0;transform:translateY(26px)}
  }
  @keyframes overlayOut{ to{opacity:0;visibility:hidden} }

  @media (prefers-reduced-motion: reduce){
    #intro-overlay, .intro-line, .intro-logo, .intro-subtitle{ animation:none !important; }
    #intro-overlay{ display:none; }
  }
</style>

<div id="intro-overlay" aria-hidden="true">
  <div class="intro-stack">
    <img src="{{ asset('images/jesa-logo.png') }}" alt="JESA CONNECT" class="intro-logo">
    <div class="intro-subtitle">{{ $subtitle }}</div>
    <div class="intro-hamburger">
      <span class="intro-line"></span>
      <span class="intro-line"></span>
      <span class="intro-line"></span>
    </div>
  </div>
</div>

<script>
  // Retrait propre de l’overlay après animation
  (function(){
    const overlay = document.getElementById('intro-overlay');
    if(!overlay) return;
    overlay.addEventListener('animationend', (e)=>{
      if(e.animationName === 'overlayOut'){
        overlay.remove();
      }
    });
  })();
</script> --}}
{{-- resources/views/partials/intro-overlay.blade.php --}}
@php
  /** @var string $subtitle  Texte sous le logo (ex: "JESA CONNECT | USER LOGIN") */
  $subtitle = $subtitle ?? 'WELCOME TO JESA CONNECT';
@endphp

<style>
  /* Empêche tout flash : on masque tout le contenu tant que l’intro n’est pas finie */
  body:not(.intro-done) > :not(#intro-overlay) {
    visibility: hidden !important;
  }

  /* --- Page-load overlay (logo + hamburger), avec image de fond --- */
  #intro-overlay{
    position:fixed; inset:0;
    background-color:#0b1220;
    background-image:
      radial-gradient(120% 80% at 50% 40%, rgba(11,18,32,0.25) 0%, rgba(11,18,32,0.55) 58%, rgba(11,18,32,0.85) 100%),
      url('{{ asset('images/hero.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    display:grid; place-items:center; z-index:9999;
    pointer-events:none;
    animation: overlayOut .4s ease forwards 2.0s; /* disparaît après ~2s */
  }
  .intro-stack{
    display:flex; flex-direction:column; align-items:center; gap:16px;
  }
  .intro-logo{
    width:96px; height:96px; object-fit:contain;
    opacity:0; transform: translateY(8px) scale(.9);
    filter: drop-shadow(0 6px 16px rgba(0,0,0,.45));
    animation: logoReveal 1s ease-out forwards .45s;
  }
  .intro-subtitle{
    color:#e5e7eb; font-weight:700; letter-spacing:.12em; text-align:center;
    font-size:14px; text-transform:uppercase; opacity:0; transform: translateY(6px);
    animation: subtitleIn .9s ease-out forwards 1.0s;
    white-space:nowrap;
  }
  .intro-hamburger{
    width:90px; height:64px;
    display:flex; flex-direction:column; justify-content:space-between;
    margin-top:4px;
  }
  .intro-line{ height:4px; width:0; background:#e5e7eb; border-radius:4px; }
  .intro-line:nth-child(1){ animation: lineTop 1.7s ease forwards; }
  .intro-line:nth-child(2){ animation: lineMiddle 1.7s ease forwards .08s; }
  .intro-line:nth-child(3){ animation: lineBottom 1.7s ease forwards .16s; }

  @keyframes logoReveal{ to { opacity:1; transform: translateY(0) scale(1); } }
  @keyframes subtitleIn{ to { opacity:1; transform: translateY(0); } }
  @keyframes lineTop{
    0%{width:0;opacity:0;transform:translateY(0)}
    40%{width:90px;opacity:1}
    70%{width:90px}
    100%{width:0;opacity:0;transform:translateY(-26px)}
  }
  @keyframes lineMiddle{
    0%{width:0;opacity:0}
    40%{width:90px;opacity:1}
    70%{width:90px}
    100%{width:0;opacity:0}
  }
  @keyframes lineBottom{
    0%{width:0;opacity:0;transform:translateY(0)}
    40%{width:90px;opacity:1}
    70%{width:90px}
    100%{width:0;opacity:0;transform:translateY(26px)}
  }
  @keyframes overlayOut{ to{opacity:0;visibility:hidden} }

  @media (prefers-reduced-motion: reduce){
    #intro-overlay, .intro-line, .intro-logo, .intro-subtitle{ animation:none !important; }
    #intro-overlay{ display:none; }
  }
</style>

<div id="intro-overlay" aria-hidden="true">
  <div class="intro-stack">
    <img src="{{ asset('images/jesa-logo.png') }}" alt="JESA CONNECT" class="intro-logo">
    <div class="intro-subtitle">{{ $subtitle }}</div>
    <div class="intro-hamburger">
      <span class="intro-line"></span>
      <span class="intro-line"></span>
      <span class="intro-line"></span>
    </div>
  </div>
</div>

<script>
  // Affichage sans délai si l’utilisateur préfère réduire les animations
  (function(){
    const overlay = document.getElementById('intro-overlay');
    const prefersReduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReduce) {
      document.body.classList.add('intro-done');
      if (overlay) overlay.remove();
      return;
    }

    // Sécurité : si l'event 'animationend' ne se déclenche pas, on retire après un délai
    const failSafe = setTimeout(() => {
      document.body.classList.add('intro-done');
      if (overlay) overlay.remove();
    }, 4000);

    if (!overlay) {
      document.body.classList.add('intro-done');
      return;
    }

    overlay.addEventListener('animationend', (e)=>{
      if (e.animationName === 'overlayOut') {
        clearTimeout(failSafe);
        document.body.classList.add('intro-done');
        overlay.remove();
      }
    });
  })();
</script>
