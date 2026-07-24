@extends('layouts.app')

@section('title', 'Bienvenidos')

@section('content')
<div class="landing-container" style="color: var(--text-primary);">
  
  <!-- ═══════════════════════════════════════════════════════ -->
  <!-- WAVE + BUBBLES TRANSITION OVERLAY                      -->
  <!-- ═══════════════════════════════════════════════════════ -->
  <div id="waveTransition" class="wave-transition-overlay">
    <!-- Water fill -->
    <div class="wave-water-fill" id="waterFill"></div>

    <!-- SVG Waves - Top (enters from bottom) -->
    <svg class="wave-svg wave-top" viewBox="0 0 1440 320" preserveAspectRatio="none">
      <path class="wave-path wave-path-1" d="M0,160 C360,40,720,280,1080,160 C1260,100,1380,140,1440,120 L1440,320 L0,320 Z" />
      <path class="wave-path wave-path-2" d="M0,200 C240,80,480,240,720,160 C960,80,1200,240,1440,160 L1440,320 L0,320 Z" />
    </svg>

    <!-- SVG Waves - Bottom (enters from top) -->
    <svg class="wave-svg wave-bottom" viewBox="0 0 1440 320" preserveAspectRatio="none">
      <path class="wave-path wave-path-3" d="M0,160 C360,280,720,40,1080,160 C1260,220,1380,180,1440,200 L1440,0 L0,0 Z" />
      <path class="wave-path wave-path-4" d="M0,120 C240,240,480,80,720,160 C960,240,1200,80,1440,160 L1440,0 L0,0 Z" />
    </svg>

    <!-- Bubbles container -->
    <div class="wave-bubbles" id="waveBubbles"></div>

    <!-- Center loading text -->
    <div class="wave-loading-text" id="waveLoadingText">
      <span class="wave-loading-icon">☕</span>
      <span class="wave-loading-label">Preparando su experiencia...</span>
    </div>
  </div>

  <style>
    /* ── Wave Transition Overlay ── */
    .wave-transition-overlay {
      position: fixed;
      inset: 0;
      z-index: 100000;
      pointer-events: none;
      opacity: 0;
      overflow: hidden;
    }
    .wave-transition-overlay.active {
      pointer-events: all;
      opacity: 1;
    }

    /* Water fill background */
    .wave-water-fill {
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, 
        rgba(10, 30, 60, 0.0) 0%,
        rgba(10, 30, 60, 0.92) 40%,
        rgba(6, 20, 42, 0.97) 100%
      );
      opacity: 0;
      transition: opacity 0.6s ease;
    }
    .wave-transition-overlay.active .wave-water-fill {
      opacity: 1;
    }

    /* SVG wave containers */
    .wave-svg {
      position: absolute;
      left: 0;
      width: 100%;
      height: 45%;
    }
    .wave-svg.wave-top {
      bottom: -100%;
      transition: bottom 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .wave-svg.wave-bottom {
      top: -100%;
      transition: top 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .wave-transition-overlay.active .wave-svg.wave-top {
      bottom: -5%;
    }
    .wave-transition-overlay.active .wave-svg.wave-bottom {
      top: -5%;
    }

    /* Wave path fills */
    .wave-path-1 { fill: rgba(10, 40, 80, 0.7); }
    .wave-path-2 { fill: rgba(15, 55, 100, 0.5); }
    .wave-path-3 { fill: rgba(10, 40, 80, 0.7); }
    .wave-path-4 { fill: rgba(15, 55, 100, 0.5); }

    /* Wave undulation animation */
    .wave-path {
      animation: waveUndulate 2.5s ease-in-out infinite alternate;
      transform-origin: center;
    }
    .wave-path-2 { animation-delay: 0.4s; animation-duration: 3s; }
    .wave-path-3 { animation-delay: 0.2s; animation-duration: 2.8s; }
    .wave-path-4 { animation-delay: 0.6s; animation-duration: 3.2s; }

    @keyframes waveUndulate {
      0%   { d: path("M0,160 C360,280,720,40,1080,160 C1260,220,1380,180,1440,200 L1440,0 L0,0 Z"); }
      100% { d: path("M0,200 C300,60,600,300,900,140 C1100,60,1300,240,1440,160 L1440,0 L0,0 Z"); }
    }

    /* ── Bubbles ── */
    .wave-bubbles {
      position: absolute;
      inset: 0;
      overflow: hidden;
    }
    .bubble {
      position: absolute;
      border-radius: 50%;
      background: radial-gradient(circle at 35% 35%, 
        rgba(196, 164, 106, 0.35), 
        rgba(255, 255, 255, 0.12) 40%, 
        rgba(196, 164, 106, 0.08) 70%, 
        transparent 100%
      );
      border: 1px solid rgba(196, 164, 106, 0.25);
      box-shadow: 
        inset 0 -4px 8px rgba(255,255,255,0.06),
        0 0 12px rgba(196, 164, 106, 0.15);
      animation: bubbleRise var(--duration) var(--delay) ease-out forwards;
      opacity: 0;
      will-change: transform, opacity;
    }
    .bubble::after {
      content: '';
      position: absolute;
      top: 18%;
      left: 22%;
      width: 30%;
      height: 20%;
      background: rgba(255,255,255,0.3);
      border-radius: 50%;
      transform: rotate(-30deg);
    }

    @keyframes bubbleRise {
      0%   { transform: translateY(0) scale(0.3); opacity: 0; }
      10%  { opacity: 0.9; transform: translateY(-5vh) scale(0.7); }
      50%  { opacity: 1; transform: translateY(-35vh) scale(1) translateX(var(--sway)); }
      85%  { opacity: 0.8; transform: translateY(-70vh) scale(1.1) translateX(calc(var(--sway) * -0.5)); }
      95%  { opacity: 0.3; transform: translateY(-85vh) scale(1.3); }
      100% { opacity: 0; transform: translateY(-100vh) scale(1.5); }
    }

    /* Bubble pop flash */
    .bubble.pop {
      animation: bubblePop 0.35s ease-out forwards !important;
    }
    @keyframes bubblePop {
      0%   { transform: scale(1); opacity: 0.8; }
      50%  { transform: scale(1.8); opacity: 0.5; border-color: rgba(196,164,106,0.6); box-shadow: 0 0 30px rgba(196,164,106,0.4); }
      100% { transform: scale(2.5); opacity: 0; }
    }

    /* Water drain effect */
    .wave-transition-overlay.draining .wave-water-fill {
      opacity: 0;
      transition: opacity 0.8s ease-in;
    }
    .wave-transition-overlay.draining .wave-svg.wave-top {
      bottom: -110%;
      transition: bottom 0.7s cubic-bezier(0.55, 0, 1, 0.45);
    }
    .wave-transition-overlay.draining .wave-svg.wave-bottom {
      top: -110%;
      transition: top 0.7s cubic-bezier(0.55, 0, 1, 0.45);
    }

    /* Loading text */
    .wave-loading-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0.8);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
      opacity: 0;
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      z-index: 10;
    }
    .wave-transition-overlay.active .wave-loading-text {
      opacity: 1;
      transform: translate(-50%, -50%) scale(1);
      transition-delay: 0.6s;
    }
    .wave-loading-icon {
      font-size: 3.5rem;
      animation: loadingSpin 2s ease-in-out infinite;
      filter: drop-shadow(0 0 20px rgba(196, 164, 106, 0.5));
    }
    @keyframes loadingSpin {
      0%, 100% { transform: rotate(0deg) scale(1); }
      25%  { transform: rotate(-15deg) scale(1.1); }
      50%  { transform: rotate(0deg) scale(1); }
      75%  { transform: rotate(15deg) scale(1.1); }
    }
    .wave-loading-label {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.5rem;
      font-weight: 600;
      color: rgba(196, 164, 106, 0.9);
      letter-spacing: 0.08em;
      text-shadow: 0 2px 15px rgba(0,0,0,0.5);
    }
    .wave-transition-overlay.draining .wave-loading-text {
      opacity: 0;
      transform: translate(-50%, -50%) scale(0.5);
      transition: all 0.4s ease-in;
      transition-delay: 0s;
    }
  </style>

  <!-- HERO SECTION -->
  <header class="landing-hero" style="position: relative; padding: 120px 20px; text-align: center; background: linear-gradient(rgba(10, 25, 47, 0.75), rgba(10, 25, 47, 0.85)), url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&q=80&w=1200&h=600') no-repeat center center/cover; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); margin-bottom: 50px;">
    <div style="max-width: 800px; margin: 0 auto; position: relative; z-index: 2;">
      <span style="font-size: 1.1rem; color: var(--accent-gold); font-weight: 700; letter-spacing: 4px; text-transform: uppercase; display: block; margin-bottom: 12px; font-family: 'Montserrat', sans-serif;">⚜️ Caf&eacute; Parisien ⚜️</span>
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 4rem; font-weight: 700; color: #fff; margin-bottom: 20px; line-height: 1.1;">Un Rinc&oacute;n de Par&iacute;s en tu Ciudad</h1>
      <p style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.85); margin-bottom: 40px; line-height: 1.6; font-style: italic; font-family: 'Cormorant Garamond', serif;">
        Descubra el arte de la reposter&iacute;a artesanal y el aroma del caf&eacute; reci&eacute;n tostado bajo la atm&oacute;sfera m&aacute;s elegante.
      </p>
      <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
        <a href="/menu" id="btnExplorar" class="btn-primary" style="text-decoration: none; padding: 16px 36px; font-size: 1.05rem; font-weight: bold; width: auto; display: inline-flex; align-items: center; gap: 8px;">
          📋 Explorar Especialidades
        </a>
        <a href="/Reservar" class="btn-secondary" style="text-decoration: none; padding: 16px 36px; font-size: 1.05rem; font-weight: bold; width: auto; display: inline-flex; align-items: center; gap: 8px; border-color: rgba(255,255,255,0.4); color: #fff; background: rgba(255,255,255,0.05);">
          🪑 Reservar una Mesa
        </a>
      </div>
    </div>
  </header>

  <!-- NUESTRA HISTORIA -->
  <section class="card" style="padding: 50px; margin-bottom: 50px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; flex-wrap: wrap;">
      <div>
        <span style="color: var(--accent-gold-dark); font-weight: bold; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 8px;">Nuestra Esencia</span>
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; color: var(--accent-navy); margin-bottom: 20px; font-weight: 700;">El Arte de la Tradici&oacute;n</h2>
        <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: 20px; font-size: 1.05rem;">
          En Caf&eacute; Parisien, cada detalle ha sido concebido para evocar el encanto de los bistr&oacute;s parisinos tradicionales. Seleccionamos cuidadosamente granos de caf&eacute; de especialidad y empleamos t&eacute;cnicas de panader&iacute;a francesas ancestrales para garantizar una experiencia sublime en cada bocado.
        </p>
        <p style="color: var(--text-secondary); line-height: 1.7; font-size: 1.05rem; font-style: italic;">
          "No es solo una taza de caf&eacute;, es un viaje sensorial a las orillas del Sena."
        </p>
      </div>
      <div style="position: relative; border-radius: var(--radius-lg); overflow: hidden; height: 350px; box-shadow: var(--shadow-md);">
        <div style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&q=80&w=800&h=600'); background-size: cover; background-position: center; width: 100%; height: 100%;"></div>
      </div>
    </div>
  </section>

  <!-- ESPECIALIDADES DESTACADAS -->
  <section style="margin-bottom: 50px;">
    <div class="section-header" style="text-align: center; margin-bottom: 40px;">
      <span class="section-icon">⚜️</span>
      <h2 class="section-title" style="font-size: 2.6rem;">Especialidades de la Casa</h2>
      <p class="section-desc" style="margin: 0 auto;">Una muestra selecta de nuestras creaciones m&aacute;s aclamadas.</p>
    </div>

    <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
      @foreach ($destacados as $p)
        @php
          // Resolver imagen local o de Unsplash
          $hash = abs(crc32($p->Nombre));
          $unsplashIds = [
              "photo-1509042239860-f550ce710b93",
              "photo-1495474472287-4d71bcdd2085",
              "photo-1517248135467-4c7edcad34c4"
          ];
          $imgUrl = "https://images.unsplash.com/" . $unsplashIds[$hash % count($unsplashIds)] . "?auto=format&fit=crop&q=80&w=600&h=400";
          
          // Buscar si existe en la carpeta externa local
          $carpetaExterna = "C:\\XD\\Examen-de-programacion\\imagenes para diseño de la pagia";
          if (is_dir($carpetaExterna)) {
              $archivos = scandir($carpetaExterna);
              foreach ($archivos as $archivo) {
                  if ($archivo === "." || $archivo === "..") continue;
                  if (strcasecmp(pathinfo($archivo, PATHINFO_FILENAME), trim($p->Nombre)) === 0) {
                      $imgUrl = "/images/externo/" . $archivo;
                      break;
                  }
              }
          }
        @endphp
        <div class="product-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
          <div class="product-img-wrap" style="height: 220px; overflow: hidden;">
            <img src="{{ $imgUrl }}" alt="{{ $p->Nombre }}" class="product-img" style="width: 100%; height: 100%; object-fit: cover;" />
            <span class="product-badge-code" style="background: var(--accent-gold-dark);">Destacado</span>
          </div>
          <div class="product-info" style="padding: 24px; text-align: center;">
            <h3 class="product-name" style="font-size: 1.3rem; margin-bottom: 8px;">{{ $p->Nombre }}</h3>
            <span class="product-price" style="font-size: 1.4rem; color: var(--accent-gold-dark); font-weight: 700; font-family: 'Cormorant Garamond', serif; display: block; margin-bottom: 16px;">
              ${{ number_format($p->Precio, 2) }}
            </span>
            <a href="/menu" class="btn-primary" style="text-decoration: none; padding: 10px 20px; font-size: 0.9rem; display: inline-block;">
              Ordenar Ahora
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  <!-- LE CLUB PARISIEN (LEALTAD) -->
  <section class="card" style="padding: 50px; background: linear-gradient(135deg, var(--accent-navy), #0b1e36); color: #fff; text-align: center; border-radius: var(--radius-lg); position: relative; overflow: hidden; box-shadow: var(--shadow-md); margin-bottom: 50px;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(196,164,106,0.06); border-radius: 50%; pointer-events: none;"></div>
    <div style="position: relative; z-index: 2; max-width: 700px; margin: 0 auto;">
      <span style="font-size: 1.8rem; display: block; margin-bottom: 15px;">⭐</span>
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.6rem; font-weight: 700; color: var(--accent-gold); margin-bottom: 15px;">Le Club Parisien</h2>
      <p style="font-size: 1.15rem; color: rgba(255, 255, 255, 0.85); margin-bottom: 30px; line-height: 1.6;">
        Acumule el 10% de sus compras en puntos de lealtad y util&iacute;celos para pagar su pr&oacute;ximo caf&eacute; o platillo de forma gratuita. Reg&iacute;strese hoy y comience a disfrutar los privilegios exclusivos.
      </p>
      @auth
        <a href="/MisPuntos" class="btn-primary" style="text-decoration: none; display: inline-block; width: auto; padding: 14px 32px; font-weight: bold; background: var(--accent-gold-dark); border-color: var(--accent-gold-dark);">
          Ver Mi Estado de Puntos
        </a>
      @else
        <a href="/registro" class="btn-primary" style="text-decoration: none; display: inline-block; width: auto; padding: 14px 32px; font-weight: bold; background: var(--accent-gold-dark); border-color: var(--accent-gold-dark);">
          Unirse al Club de Lealtad
        </a>
      @endauth
    </div>
  </section>

  <!-- CONTACTO & HORARIOS -->
  <section class="card" style="padding: 40px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; flex-wrap: wrap; text-align: center;">
      <div style="padding-right: 20px;">
        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--accent-navy); margin-bottom: 10px;">🕒 Horarios de Servicio</h3>
        <p style="color: var(--text-secondary); margin-bottom: 6px; font-weight: 600;">Lunes a Domingo</p>
        <p style="color: var(--accent-gold-dark); font-size: 1.2rem; font-weight: bold;">7:00 AM - 10:00 PM</p>
      </div>
      <div>
        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--accent-navy); margin-bottom: 10px;">📍 Ubicaci&oacute;n</h3>
        <p style="color: var(--text-secondary); margin-bottom: 6px;">Avenida de la Elegancia #104, Barrio Franc&eacute;s</p>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Reservaciones al: (555) 123-4567</p>
      </div>
    </div>
  </section>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnExplorar');
  if (!btn) return;

  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const targetUrl = this.href;
    const overlay = document.getElementById('waveTransition');
    const bubblesContainer = document.getElementById('waveBubbles');

    // ── Phase 1: Waves crash in ──
    overlay.classList.add('active');

    // ── Phase 2: Spawn bubbles after waves arrive ──
    setTimeout(() => {
      spawnBubbles(bubblesContainer, 35);
    }, 900);

    // ── Phase 3: Pop bubbles & drain water ──
    setTimeout(() => {
      // Pop all visible bubbles
      const bubbles = bubblesContainer.querySelectorAll('.bubble:not(.pop)');
      bubbles.forEach((b, i) => {
        setTimeout(() => b.classList.add('pop'), i * 40);
      });

      // Start draining
      setTimeout(() => {
        overlay.classList.add('draining');
      }, bubbles.length * 30 + 200);

      // Navigate after drain completes
      setTimeout(() => {
        window.location.href = targetUrl;
      }, bubbles.length * 30 + 1000);
    }, 2800);
  });

  function spawnBubbles(container, count) {
    container.innerHTML = '';
    for (let i = 0; i < count; i++) {
      const bubble = document.createElement('div');
      bubble.className = 'bubble';

      const size = 18 + Math.random() * 55;
      const left = 3 + Math.random() * 94;
      const bottom = Math.random() * 30;
      const delay = Math.random() * 1.8;
      const duration = 2.2 + Math.random() * 2.5;
      const sway = -40 + Math.random() * 80;

      bubble.style.width = size + 'px';
      bubble.style.height = size + 'px';
      bubble.style.left = left + '%';
      bubble.style.bottom = bottom + '%';
      bubble.style.setProperty('--delay', delay + 's');
      bubble.style.setProperty('--duration', duration + 's');
      bubble.style.setProperty('--sway', sway + 'px');

      container.appendChild(bubble);
    }
  }
});
</script>
@endsection