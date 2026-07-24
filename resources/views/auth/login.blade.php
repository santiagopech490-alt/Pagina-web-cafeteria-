<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Acceso — Caf&eacute; Parisien</title>
  <link rel="stylesheet" href="/css/site.css" />
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <div class="login-image-section">
        <div class="login-bg-img" style="background-image: url('/images/1c5b1c7217313ccdbf57301aa2b44dad.jpg');"></div>
        <div class="login-image-overlay"></div>
        <div class="login-brand-info">
          <span class="brand-sub">Caf&eacute; Parisien</span>
          <h1 class="brand-main">L'&Eacute;l&eacute;gance</h1>
          <p class="brand-desc">Inicia sesi&oacute;n para descubrir nuestras especialidades &uacute;nicas.</p>
        </div>
      </div>
      
      <div class="login-form-section">
        <div class="login-header">
          <h2>Iniciar Sesi&oacute;n</h2>
          <p>Introduce tus credenciales para continuar.</p>
        </div>

        @if ($errors->any())
          <div class="alert-box error" style="margin-bottom: 20px; padding: 12px; border-radius: var(--radius-sm); background: rgba(244,67,54,0.1); border: 1px solid var(--accent-err); color: var(--accent-err); font-size: 0.9rem;">
            <span>⚠️ {{ $errors->first() }}</span>
          </div>
        @endif

        <form action="/login" method="POST">
          @csrf
          <div class="form-group" style="margin-bottom: 18px;">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" placeholder="Nombre de usuario" required autocomplete="off" value="{{ old('username') }}" />
          </div>
          <div class="form-group" style="margin-bottom: 18px;">
            <label for="password">Contrase&ntilde;a</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required />
          </div>
          
          <button type="submit" class="btn-primary login-btn">
            Ingresar
          </button>
        </form>
        
        <div class="login-footer" style="margin-top: 20px;">
          <p style="color: var(--text-secondary); margin-bottom: 10px;">&iquest;No tienes cuenta? <a href="/registro" style="color: var(--accent-gold-dark); font-weight: 600; text-decoration: none;">Reg&iacute;strate aqu&iacute;</a></p>
          <a href="/" class="back-link">📋 Volver a la Tienda</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>