{{-- <!DOCTYPE html>
<html lang="en" class="scroll-smooth intro-active">
<head>
  <meta charset="UTF-8" />
  <title>JESA CONNECT Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('images/jesa-logo.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link rel="preload" as="image" href="{{ asset('images/hero.jpg') }}">


  <style>
    /* Keep the hero background visible during intro; hide everything else */
html.intro-active body > :not(#intro-overlay):not(.bg-hero) {
  visibility: hidden !important;
}

    #intro-overlay {
      visibility: visible !important;
    }
    /* <<< Fin de l'ajout anti-flash */

    body { font-family: 'Poppins', sans-serif; background: #0b1220; line-height: 1.7; }
    header { position: fixed; top: 0; left: 0; width: 100%; z-index: 100; }
    main { padding-top: 110px; }
    svg.icon { width: 28px; height: 28px; fill: currentColor; }

    /* Effet glassmorphism */
    .glass {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.18);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      color: #e5e7eb;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Effet tilt (cartes) */
    .tilt:hover {
      transform: translateY(-12px) scale(1.03);
      box-shadow: 0 25px 50px rgba(0,0,0,0.45);
    }

    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #0b1220; }
    ::-webkit-scrollbar-thumb { background-color: #1e3a8a; border-radius: 10px; }

    .hamburger { display: none; flex-direction: column; cursor: pointer; width: 24px; height: 18px; justify-content: space-between; }
    .hamburger span { height: 3px; background: #e2e8f0; border-radius: 3px; transition: all 0.3s ease; }
    .hamburger.active span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

    @media (max-width: 768px) {
      nav div.menu { display: none; width: 100%; margin-top: 12px; flex-direction: column; gap: 12px; }
      nav div.menu.active { display: flex; }
      .hamburger { display: flex; }
    }

    .bg-hero { position: fixed; inset: 0; z-index: -1; overflow: hidden; }
    .bg-hero .image {
      position: absolute; inset: 0;
      background-image: url('{{ asset('images/hero.jpg') }}');
      background-size: cover; background-position: center;
      filter: saturate(1.1) brightness(0.8);
      transform: scale(1.02);
    }
    .bg-hero .vignette {
      position: absolute; inset: 0;
      background: radial-gradient(120% 80% at 50% 0%, rgba(15,23,42,0) 0%, rgba(15,23,42,0.2) 50%, rgba(15,23,42,0.6) 100%);
      mix-blend-mode: multiply;
    }
    .bg-hero .grain {
      position: absolute; inset: 0; opacity: .08; pointer-events: none;
      background-image: url("data:image/svg+xml;utf8,\
<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'>\
<filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='2' stitchTiles='stitch'/></filter>\
<rect width='100%' height='100%' filter='url(#n)' opacity='0.5'/></svg>");
      background-size: 300px 300px;
      animation: grainShift 8s steps(10) infinite;
    }
    @keyframes grainShift { 0% { transform: translate(0,0) } 100% { transform: translate(-20px,-20px) } }
    .blob { position: absolute; border-radius: 9999px; filter: blur(40px);
      opacity: 0.35; mix-blend-mode: screen;
      animation: floatY 22s ease-in-out infinite alternate, floatX 28s ease-in-out infinite alternate;
    }
    .blob.b1 { width: 520px; height: 520px; left: -80px; top: -80px;
      background: radial-gradient(circle at 30% 30%, #60a5fa, #1e40af 70%); }
    .blob.b2 { width: 460px; height: 460px; right: -120px; top: 10%;
      background: radial-gradient(circle at 70% 30%, #f472b6, #7c3aed 70%); animation-delay: -6s; }
    .blob.b3 { width: 520px; height: 520px; left: 10%; bottom: -160px;
      background: radial-gradient(circle at 40% 60%, #34d399, #065f46 70%); animation-delay: -12s; }
    @keyframes floatY { from { transform: translateY(-20px); } to { transform: translateY(20px); } }
    @keyframes floatX { from { transform: translateX(-15px); } to { transform: translateX(15px); } }

    /* --- Page-load hamburger intro + logo & subtitle reveal (fond = hero.jpg) --- */
    #intro-overlay{
      position:fixed; inset:0;
      background-image:
        radial-gradient(120% 80% at 50% 40%, rgba(11,18,32,0.25) 0%, rgba(11,18,32,0.55) 58%, rgba(11,18,32,0.85) 100%),
        url('{{ asset('images/hero.jpg') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display:grid; place-items:center; z-index:9999;
      pointer-events:none;
      animation: overlayOut .6s ease forwards 2.8s; /* sortie après ~2.8s */
    }
    .intro-stack{
      display:flex; flex-direction:column; align-items:center; gap:16px;
    }
    .intro-logo{
      width:96px; height:96px; object-fit:contain;
      opacity:0; transform: translateY(8px) scale(.9);
      filter: drop-shadow(0 6px 16px rgba(0,0,0,.45));
      animation: logoReveal .95s ease-out forwards .4s;
    }
    .intro-subtitle{
      color:#e5e7eb; font-weight:700; letter-spacing:.12em; text-align:center;
      font-size:14px; text-transform:uppercase;
      opacity:0; transform: translateY(6px);
      animation: subtitleIn .9s ease-out forwards .9s;
      white-space:nowrap;
    }
    .intro-hamburger{
      width:90px; height:64px;
      display:flex; flex-direction:column; justify-content:space-between;
      margin-top:4px;
    }
    .intro-line{ height:4px; width:0; background:#e5e7eb; border-radius:4px; }
    .intro-line:nth-child(1){ animation: lineTop 1.6s ease forwards; }
    .intro-line:nth-child(2){ animation: lineMiddle 1.6s ease forwards .05s; }
    .intro-line:nth-child(3){ animation: lineBottom 1.6s ease forwards .1s; }

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

    /* --- Boutons "blanc pas très clair" avec tache (blob) animée --- */
    .btn-blob{
      position: relative; overflow: hidden; border-radius: 0.5rem;
      background: rgba(255,255,255,0.92); /* blanc cassé */
      color: #0f172a; border: 1px solid rgba(255,255,255,0.24);
      box-shadow: 0 8px 24px rgba(0,0,0,0.25);
      transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease;
      isolation: isolate;
    }
    .btn-blob::before{
      content:""; position:absolute; inset:auto;
      width: 220%; height: 220%;
      left: var(--bx, 50%); top: var(--by, 50%);
      transform: translate(-50%, -50%);
      background: radial-gradient(180px 180px, var(--blob, rgba(59,130,246,0.28)), transparent 60%);
      transition: left .12s ease, top .12s ease;
      pointer-events:none; z-index: -1;
    }
    .btn-blob:hover{ transform: translateY(-2px); box-shadow: 0 14px 36px rgba(0,0,0,0.35); }
    .btn-blob:active{ transform: translateY(0); }

    .btn-blob-blue{ --blob: rgba(59,130,246,0.28); }   /* bleu */
    .btn-blob-orange{ --blob: rgba(249,115,22,0.28); } /* orange */


  </style>
</head>
<body>

  <!-- INTRO OVERLAY (logo + "JESA CONNECT, HOME" + animation hamburger au chargement) -->
  <div id="intro-overlay" aria-hidden="true">
    <div class="intro-stack">
      <img src="{{ asset('images/jesa-logo.png') }}" alt="JESA CONNECT" class="intro-logo">
      <div class="intro-subtitle">JESA CONNECT, HOME</div>
      <div class="intro-hamburger">
        <span class="intro-line"></span>
        <span class="intro-line"></span>
        <span class="intro-line"></span>
      </div>
    </div>
  </div>

  <!-- BACKGROUND -->
  <div class="bg-hero fixed inset-0 -z-10 overflow-hidden">
    <div class="image absolute inset-0 bg-cover bg-center" 
         style="background-image: url('{{ asset('images/hero.jpg') }}'); filter:saturate(1.1) brightness(0.8);">
    </div>
  </div>

  <!-- HEADER -->
  <header class="bg-white/10 glass">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center flex-wrap gap-4">
      <!-- Logo -->
      <a href="{{ url('/') }}" class="flex items-center gap-3 select-none">
        <img src="{{ asset('images/jesa-logo.png') }}" alt="JESA Connect" class="h-10 w-10 rounded-lg shadow-md object-contain">
        <span class="text-xl font-extrabold tracking-wide text-white">JESA CONNECT</span>
      </a>

      
      <!-- Menu -->
<div class="flex items-center gap-4 menu">
  @auth
    @if(auth()->user()->isAdmin())
      <!-- Même style que "Admin Login" non connecté : blanc + tache orange -->
      <a href="{{ route('filament.admin.pages.dashboard') }}"
         class="btn-blob btn-blob-orange px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-500/40">
        Admin Dashboard
      </a>
      <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="inline">
        @csrf
        <button type="submit" class="text-red-200 hover:text-red-100 font-semibold transition">
          Logout
        </button>
      </form>
    @else
      <!-- Même style que "User Login" non connecté : blanc + tache bleue -->
      <a href="{{ route('filament.utilisateur.pages.dashboard') }}"
         class="btn-blob btn-blob-blue px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        User Dashboard
      </a>
      <form method="POST" action="{{ route('filament.utilisateur.auth.logout') }}" class="inline">
        @csrf
        <button type="submit" class="text-red-200 hover:text-red-100 font-semibold transition">
          Logout
        </button>
      </form>
    @endif
  @else
    <!-- Boutons état non connecté (inchangés) -->
    <a href="{{ route('filament.utilisateur.auth.login') }}"
       class="btn-blob btn-blob-blue px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      User Login
    </a>
    <a href="{{ route('filament.admin.auth.login') }}"
       class="btn-blob btn-blob-orange px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-500/40">
      Admin Login
    </a>
  @endauth
</div>

    </nav>
  </header>

  <!-- HERO SECTION -->
  <main class="max-w-7xl mx-auto px-6 text-center">
    <section class="py-10 md:py-16">
      <h2 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-6">
        JESA CONNECT – Work smarter, manage professional relationships
      </h2>
      <p class="text-lg max-w-3xl mx-auto text-slate-200/90 mb-12">
        A platform dedicated to JESA employees and administration to centralize, organize, and optimize all your professional interactions.
      </p>

      <!-- CARDS -->
      <div class="flex flex-wrap justify-center gap-6 md:gap-10">
        
        <!-- Card 1 : Interlocutors & Exchanges -->
        <article class="glass rounded-3xl shadow-xl p-8 max-w-xs w-full cursor-pointer tilt">
          <div class="flex items-center mb-5 gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1z"/>
            </svg>
            <h3 class="text-xl font-semibold text-white">Contacts & Exchanges</h3>
          </div>
          <p>Manage your professional contacts and track all interactions (emails, calls, notes) in one unified workspace.</p>
        </article>

        <!-- Card 2 : Meetings & Minutes -->
        <article class="glass rounded-3xl shadow-xl p-8 max-w-xs w-full cursor-pointer tilt">
          <div class="flex items-center mb-5 gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="currentColor" viewBox="0 0 16 16">
              <path d="M4.5 1a.5.5 0 0 0-.5.5V3H2v8h12V4h-2V2a.5.5 0 0 0-.5-.5z"/>
            </svg>
            <h3 class="text-xl font-semibold text-white">Meetings & Minutes</h3>
          </div>
          <p>Plan meetings, document discussions, and keep structured minutes accessible for your teams.</p>
        </article>

        <!-- Card 3 : Statistics -->
        <article class="glass rounded-3xl shadow-xl p-8 max-w-xs w-full cursor-pointer tilt">
          <div class="flex items-center mb-5 gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="currentColor" viewBox="0 0 16 16">
              <path d="M10 13.5a.5.5 0 0 0 .5.5h1V8h-1zM7 14a.5.5 0 0 1-.5-.5V9h1v4.5a.5.5 0 0 1-.5.5zm-3-1a.5.5 0 0 1-.5-.5V11h1v1.5a.5.5 0 0 1-.5.5z"/>
            </svg>
            <h3 class="text-xl font-semibold text-white">Statistics & Reports</h3>
          </div>
          <p>Access key metrics and generate clear, actionable reports to support strategic decision-making.</p>
        </article>

      </div>
    </section>
  </main>


  <!-- FOOTER -->
  <footer class="mt-8 md:mt-12 py-8 text-center text-slate-200/80 text-sm select-none">
    &copy; {{ date('Y') }} JESA CONNECT. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ once: true, duration: 600, easing: 'ease-in-out' });

    // Retire proprement l’overlay après l’animation de sortie
    (function(){
      const overlay = document.getElementById('intro-overlay');
      if(!overlay) return;
      overlay.addEventListener('animationend', (e)=>{
        if(e.animationName === 'overlayOut'){
          // >>> afficher la page après l'intro
          document.documentElement.classList.remove('intro-active');
          overlay.remove();
        }
      });
    })();

    // Blob animé qui suit la souris sur les boutons
    (function(){
      const btns = document.querySelectorAll('.btn-blob');
      btns.forEach(btn=>{
        btn.addEventListener('mousemove', (e)=>{
          const r = btn.getBoundingClientRect();
          const x = e.clientX - r.left;
          const y = e.clientY - r.top;
          btn.style.setProperty('--bx', x + 'px');
          btn.style.setProperty('--by', y + 'px');
        });
        // valeur par défaut centrée
        btn.style.setProperty('--bx', '50%');
        btn.style.setProperty('--by', '50%');
      });
    })();

  

  </script>

</body>
</html>
 --}}

 <!DOCTYPE html>
<html lang="en" class="scroll-smooth intro-active">
<head>
  <meta charset="UTF-8" />
  <title>JESA CONNECT Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('images/jesa-logo.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link rel="preload" as="image" href="{{ asset('images/hero.jpg') }}">

  <style>
    /* ======== ANTI-FOUC ======== 
       On ne masque QUE le contenu (wrapper #page-content)
       -> l'overlay (#intro-overlay) et le fond (.bg-hero) restent visibles.
    */
    /* AJOUT: remplace l'ancienne règle par celle-ci */
    html.intro-active #page-content {
      visibility: hidden !important;
    }
    #intro-overlay,
    .bg-hero {
      visibility: visible !important;
    }
    /* =========================== */

    body { font-family: 'Poppins', sans-serif; background: #0b1220; line-height: 1.7; }
    header { position: fixed; top: 0; left: 0; width: 100%; z-index: 100; }
    main { padding-top: 110px; }
    svg.icon { width: 28px; height: 28px; fill: currentColor; }

    /* Effet glassmorphism */
    .glass {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.18);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      color: #e5e7eb;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Effet tilt (cartes) */
    .tilt:hover {
      transform: translateY(-12px) scale(1.03);
      box-shadow: 0 25px 50px rgba(0,0,0,0.45);
    }

    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #0b1220; }
    ::-webkit-scrollbar-thumb { background-color: #1e3a8a; border-radius: 10px; }

    .hamburger { display: none; flex-direction: column; cursor: pointer; width: 24px; height: 18px; justify-content: space-between; }
    .hamburger span { height: 3px; background: #e2e8f0; border-radius: 3px; transition: all 0.3s ease; }
    .hamburger.active span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

    @media (max-width: 768px) {
      nav div.menu { display: none; width: 100%; margin-top: 12px; flex-direction: column; gap: 12px; }
      nav div.menu.active { display: flex; }
      .hamburger { display: flex; }
    }

    .bg-hero { position: fixed; inset: 0; z-index: -1; overflow: hidden; }
    .bg-hero .image {
      position: absolute; inset: 0;
      background-image: url('{{ asset('images/hero.jpg') }}');
      background-size: cover; background-position: center;
      filter: saturate(1.1) brightness(0.8);
      transform: scale(1.02);
    }
    .bg-hero .vignette {
      position: absolute; inset: 0;
      background: radial-gradient(120% 80% at 50% 0%, rgba(15,23,42,0) 0%, rgba(15,23,42,0.2) 50%, rgba(15,23,42,0.6) 100%);
      mix-blend-mode: multiply;
    }
    .bg-hero .grain {
      position: absolute; inset: 0; opacity: .08; pointer-events: none;
      background-image: url("data:image/svg+xml;utf8,\
<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'>\
<filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='2' stitchTiles='stitch'/></filter>\
<rect width='100%' height='100%' filter='url(#n)' opacity='0.5'/></svg>");
      background-size: 300px 300px;
      animation: grainShift 8s steps(10) infinite;
    }
    @keyframes grainShift { 0% { transform: translate(0,0) } 100% { transform: translate(-20px,-20px) } }
    .blob { position: absolute; border-radius: 9999px; filter: blur(40px);
      opacity: 0.35; mix-blend-mode: screen;
      animation: floatY 22s ease-in-out infinite alternate, floatX 28s ease-in-out infinite alternate;
    }
    .blob.b1 { width: 520px; height: 520px; left: -80px; top: -80px;
      background: radial-gradient(circle at 30% 30%, #60a5fa, #1e40af 70%); }
    .blob.b2 { width: 460px; height: 460px; right: -120px; top: 10%;
      background: radial-gradient(circle at 70% 30%, #f472b6, #7c3aed 70%); animation-delay: -6s; }
    .blob.b3 { width: 520px; height: 520px; left: 10%; bottom: -160px;
      background: radial-gradient(circle at 40% 60%, #34d399, #065f46 70%); animation-delay: -12s; }
    @keyframes floatY { from { transform: translateY(-20px); } to { transform: translateY(20px); } }
    @keyframes floatX { from { transform: translateX(-15px); } to { transform: translateX(15px); } }

    /* --- Page-load hamburger intro + logo & subtitle reveal (fond = hero.jpg) --- */
    #intro-overlay{
      position:fixed; inset:0;
      background-image:
        radial-gradient(120% 80% at 50% 40%, rgba(11,18,32,0.25) 0%, rgba(11,18,32,0.55) 58%, rgba(11,18,32,0.85) 100%),
        url('{{ asset('images/hero.jpg') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display:grid; place-items:center; z-index:9999;
      pointer-events:none;
      animation: overlayOut .6s ease forwards 2.8s; /* sortie après ~2.8s */
    }
    .intro-stack{
      display:flex; flex-direction:column; align-items:center; gap:16px;
    }
    .intro-logo{
      width:96px; height:96px; object-fit:contain;
      opacity:0; transform: translateY(8px) scale(.9);
      filter: drop-shadow(0 6px 16px rgba(0,0,0,.45));
      animation: logoReveal .95s ease-out forwards .4s;
    }
    .intro-subtitle{
      color:#e5e7eb; font-weight:700; letter-spacing:.12em; text-align:center;
      font-size:14px; text-transform:uppercase;
      opacity:0; transform: translateY(6px);
      animation: subtitleIn .9s ease-out forwards .9s;
      white-space:nowrap;
    }
    .intro-hamburger{
      width:90px; height:64px;
      display:flex; flex-direction:column; justify-content:space-between;
      margin-top:4px;
    }
    .intro-line{ height:4px; width:0; background:#e5e7eb; border-radius:4px; }
    .intro-line:nth-child(1){ animation: lineTop 1.6s ease forwards; }
    .intro-line:nth-child(2){ animation: lineMiddle 1.6s ease forwards .05s; }
    .intro-line:nth-child(3){ animation: lineBottom 1.6s ease forwards .1s; }

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

    /* --- Boutons "blanc pas très clair" avec tache (blob) animée --- */
    .btn-blob{
      position: relative; overflow: hidden; border-radius: 0.5rem;
      background: rgba(255,255,255,0.92); /* blanc cassé */
      color: #0f172a; border: 1px solid rgba(255,255,255,0.24);
      box-shadow: 0 8px 24px rgba(0,0,0,0.25);
      transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease;
      isolation: isolate;
    }
    .btn-blob::before{
      content:""; position:absolute; inset:auto;
      width: 220%; height: 220%;
      left: var(--bx, 50%); top: var(--by, 50%);
      transform: translate(-50%, -50%);
      background: radial-gradient(180px 180px, var(--blob, rgba(59,130,246,0.28)), transparent 60%);
      transition: left .12s ease, top .12s ease;
      pointer-events:none; z-index: -1;
    }
    .btn-blob:hover{ transform: translateY(-2px); box-shadow: 0 14px 36px rgba(0,0,0,0.35); }
    .btn-blob:active{ transform: translateY(0); }

    .btn-blob-blue{ --blob: rgba(59,130,246,0.28); }   /* bleu */
    .btn-blob-orange{ --blob: rgba(249,115,22,0.28); } /* orange */

    /* ====== AJOUTS DISCRETS POUR LES AVIS ====== */
    .jc-tilt-wrap{ perspective: 1000px; }
    .jc-card-3d{ transform-style: preserve-3d; transition: transform .18s ease; border-radius: 1.5rem; }
    .jc-card-3d:hover{ box-shadow: 0 25px 60px rgba(0,0,0,.45); }
  </style>
</head>
<body>

  <!-- BACKGROUND (home) -->
  <div class="bg-hero fixed inset-0 -z-10 overflow-hidden">
    <div class="image absolute inset-0 bg-cover bg-center" 
         style="background-image: url('{{ asset('images/hero.jpg') }}'); filter:saturate(1.1) brightness(0.8);">
    </div>
  </div>

  <!-- INTRO OVERLAY -->
  <div id="intro-overlay" aria-hidden="true">
    <div class="intro-stack">
      <img src="{{ asset('images/jesa-logo.png') }}" alt="JESA CONNECT" class="intro-logo">
      <div class="intro-subtitle">JESA CONNECT, HOME</div>
      <div class="intro-hamburger">
        <span class="intro-line"></span>
        <span class="intro-line"></span>
        <span class="intro-line"></span>
      </div>
    </div>
  </div>

  <!-- WRAPPER CONTENU (AJOUT) -->
  <div id="page-content">
    <!-- HEADER -->
    <header class="bg-white/10 glass">
      <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center flex-wrap gap-4">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 select-none">
          <img src="{{ asset('images/jesa-logo.png') }}" alt="JESA Connect" class="h-10 w-10 rounded-lg shadow-md object-contain">
          <span class="text-xl font-extrabold tracking-wide text-white">JESA CONNECT</span>
        </a>

        <!-- Menu -->
        <div class="flex items-center gap-4 menu">
          @auth
            @if(auth()->user()->isAdmin())
              <!-- Même style que "Admin Login" non connecté : blanc + tache orange -->
              <a href="{{ route('filament.admin.pages.dashboard') }}"
                class="btn-blob btn-blob-orange px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-500/40">
                Admin Dashboard
              </a>
              <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-red-200 hover:text-red-100 font-semibold transition">
                  Logout
                </button>
              </form>
            @else
              <!-- Même style que "User Login" non connecté : blanc + tache bleue -->
              <a href="{{ route('filament.utilisateur.pages.dashboard') }}"
                class="btn-blob btn-blob-blue px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                User Dashboard
              </a>
              <form method="POST" action="{{ route('filament.utilisateur.auth.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-red-200 hover:text-red-100 font-semibold transition">
                  Logout
                </button>
              </form>
            @endif
          @else
            <!-- Boutons état non connecté (inchangés) -->
            <a href="{{ route('filament.utilisateur.auth.login') }}"
              class="btn-blob btn-blob-blue px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500/40">
              User Login
            </a>
            <a href="{{ route('filament.admin.auth.login') }}"
              class="btn-blob btn-blob-orange px-5 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-500/40">
              Admin Login
            </a>
          @endauth
        </div>
      </nav>
    </header>

    <!-- HERO SECTION -->
    <main class="max-w-7xl mx-auto px-6 text-center">
      <section class="py-10 md:py-16">
        <h2 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-6">
          JESA CONNECT – Work smarter, manage professional relationships
        </h2>
        <p class="text-lg max-w-3xl mx-auto text-slate-200/90 mb-12">
          A platform dedicated to JESA employees and administration to centralize, organize, and optimize all your professional interactions.
        </p>

        <!-- CARDS -->
        <div class="flex flex-wrap justify-center gap-6 md:gap-10">
          <!-- Card 1 : Interlocutors & Exchanges -->
          <article class="glass rounded-3xl shadow-xl p-8 max-w-xs w-full cursor-pointer tilt">
            <div class="flex items-center mb-5 gap-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="currentColor" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1z"/>
              </svg>
              <h3 class="text-xl font-semibold text-white">Contacts & Exchanges</h3>
            </div>
            <p>Manage your professional contacts and track all interactions (emails, calls, notes) in one unified workspace.</p>
          </article>

          <!-- Card 2 : Meetings & Minutes -->
          <article class="glass rounded-3xl shadow-xl p-8 max-w-xs w-full cursor-pointer tilt">
            <div class="flex items-center mb-5 gap-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="currentColor" viewBox="0 0 16 16">
                <path d="M4.5 1a.5.5 0 0 0-.5.5V3H2v8h12V4h-2V2a.5.5 0 0 0-.5-.5z"/>
              </svg>
              <h3 class="text-xl font-semibold text-white">Meetings & Minutes</h3>
            </div>
            <p>Plan meetings, document discussions, and keep structured minutes accessible for your teams.</p>
          </article>

          <!-- Card 3 : Statistics -->
          <article class="glass rounded-3xl shadow-xl p-8 max-w-xs w-full cursor-pointer tilt">
            <div class="flex items-center mb-5 gap-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="currentColor" viewBox="0 0 16 16">
                <path d="M10 13.5a.5.5 0 0 0 .5.5h1V8h-1zM7 14a.5.5 0 0 1-.5-.5V9h1v4.5a.5.5 0 0 1-.5.5zm-3-1a.5.5 0 0 1-.5-.5V11h1v1.5a.5.5 0 0 1-.5.5z"/>
              </svg>
              <h3 class="text-xl font-semibold text-white">Statistics & Reports</h3>
            </div>
            <p>Access key metrics and generate clear, actionable reports to support strategic decision-making.</p>
          </article>
        </div>
      </section>

      <!-- ===== AJOUT : TESTIMONIALS + ABOUT LINK ===== -->
      <section class="py-8 md:py-12">
        <h3 class="text-2xl md:text-3xl font-bold text-white text-center mb-8">What people at JESA say</h3>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 jc-tilt-wrap">
          <!-- 1 -->
          <article class="glass jc-card-3d p-6">
            <div class="flex items-center gap-4 mb-4">
              <img src="{{ asset('images/staff-amina.jpg') }}" alt="Amina El Idrissi" class="w-14 h-14 rounded-full object-cover ring-2 ring-white/20 shadow-lg">
              <div class="text-left">
                <div class="text-white font-semibold">Amina El Idrissi</div>
                <div class="text-xs text-slate-300/80">Project Manager — Industrial Projects</div>
              </div>
            </div>
            <p class="text-slate-200/90 text-left mb-2">“JESA CONNECT drastically reduced the time we spend chasing emails.”</p>
            <div class="flex items-center gap-1 text-yellow-400 text-base" aria-label="5 stars">★ ★ ★ ★ ★</div>
          </article>

          <!-- 2 -->
          <article class="glass jc-card-3d p-6">
            <div class="flex items-center gap-4 mb-4">
              <img src="{{ asset('images/staff-youssef.jpg') }}" alt="Youssef Naji" class="w-14 h-14 rounded-full object-cover ring-2 ring-white/20 shadow-lg">
              <div class="text-left">
                <div class="text-white font-semibold">Youssef Naji</div>
                <div class="text-xs text-slate-300/80">Reliability Engineer — MRO & Support</div>
              </div>
            </div>
            <p class="text-slate-200/90 text-left mb-2">“The contact book is gold — one place to see the full exchange history.”</p>
            <div class="flex items-center gap-1 text-yellow-400 text-base" aria-label="4 stars">★ ★ ★ ★ ☆</div>
          </article>

          <!-- 3 -->
          <article class="glass jc-card-3d p-6">
            <div class="flex items-center gap-4 mb-4">
              <img src="{{ asset('images/staff-sara.jpg') }}" alt="Sara Benali" class="w-14 h-14 rounded-full object-cover ring-2 ring-white/20 shadow-lg">
              <div class="text-left">
                <div class="text-white font-semibold">Sara Benali</div>
                <div class="text-xs text-slate-300/80">HR Business Partner — People & Culture</div>
              </div>
            </div>
            <p class="text-slate-200/90 text-left mb-2">“Onboarding new colleagues is easier: instant context on contacts & meetings.”</p>
            <div class="flex items-center gap-1 text-yellow-400 text-base" aria-label="5 stars">★ ★ ★ ★ ★</div>
          </article>

          <!-- 4 -->
          <article class="glass jc-card-3d p-6">
            <div class="flex items-center gap-4 mb-4">
              <img src="{{ asset('images/staff-thomas.jpg') }}" alt="Thomas Leroy" class="w-14 h-14 rounded-full object-cover ring-2 ring-white/20 shadow-lg">
              <div class="text-left">
                <div class="text-white font-semibold">Thomas Leroy</div>
                <div class="text-xs text-slate-300/80">IT Administrator — Digital Workplace</div>
              </div>
            </div>
            <p class="text-slate-200/90 text-left mb-2">“Exporting minutes and action items to PDF takes seconds now.”</p>
            <div class="flex items-center gap-1 text-yellow-400 text-base" aria-label="4 stars">★ ★ ★ ★ ☆</div>
          </article>
        </div>

        <!-- Petit lien About us -->
        <div class="mt-6 text-center text-slate-300 text-sm">
          About us:
          <a href="https://www.jesagroup.com" target="_blank" rel="noopener" class="underline hover:text-white">
            www.jesagroup.com
          </a>
        </div>
      </section>
      <!-- ===== FIN AJOUT ===== -->

    </main>

    <!-- FOOTER -->
    <footer class="mt-8 md:mt-12 py-8 text-center text-slate-200/80 text-sm select-none">
      &copy; {{ date('Y') }} JESA CONNECT. All rights reserved.
    </footer>
  </div><!-- /#page-content -->

  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ once: true, duration: 600, easing: 'ease-in-out' });

    // Retire proprement l’overlay après l’animation de sortie
    (function(){
      const overlay = document.getElementById('intro-overlay');
      if(!overlay) return;
      overlay.addEventListener('animationend', (e)=>{
        if(e.animationName === 'overlayOut'){
          // >>> afficher la page après l'intro
          document.documentElement.classList.remove('intro-active');
          overlay.remove();
        }
      });
    })();

    // Blob animé qui suit la souris sur les boutons
    (function(){
      const btns = document.querySelectorAll('.btn-blob');
      btns.forEach(btn=>{
        btn.addEventListener('mousemove', (e)=>{
          const r = btn.getBoundingClientRect();
          const x = e.clientX - r.left;
          const y = e.clientY - r.top;
          btn.style.setProperty('--bx', x + 'px');
          btn.style.setProperty('--by', y + 'px');
        });
        // valeur par défaut centrée
        btn.style.setProperty('--bx', '50%');
        btn.style.setProperty('--by', '50%');
      });
    })();

    // Effet tilt 3D (avis) – discret et isolé
    (function(){
      const cards = document.querySelectorAll('.jc-card-3d');
      cards.forEach(card=>{
        card.addEventListener('mousemove', (e)=>{
          const r = card.getBoundingClientRect();
          const x = e.clientX - r.left, y = e.clientY - r.top;
          const rx = ((y / r.height) - .5) * -6;
          const ry = ((x / r.width) - .5) *  8;
          card.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg)`;
        });
        card.addEventListener('mouseleave', ()=>{
          card.style.transform = 'rotateX(0deg) rotateY(0deg)';
        });
      });
    })();
  </script>

</body>
</html>
