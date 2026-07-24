<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Café Parisien - L'Élégance Terminal POS" />
  <title>@yield('title') — Café Parisien</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="stylesheet" href="/css/site.css" />
</head>
<body>
  @php
    $isAdminRoute = request()->is('admin*');
    $isLandingRoute = request()->is('/');
  @endphp
  
  <div class="app-wrapper {{ $isLandingRoute ? 'no-sidebar' : '' }}">

    @if (!$isLandingRoute)
      <!-- TOPBAR -->
      <header class="topbar">
        <div style="display: flex; align-items: center; gap: 14px;">
          <a href="/" class="topbar-logo-link" title="Café Parisien">
            <span class="topbar-logo">⚜️</span>
          </a>
          <span class="topbar-title" style="font-family: 'Cormorant Garamond', serif; font-weight: bold; font-size: 1.45rem; color: var(--accent-navy);">
            {{ $isAdminRoute ? "Café Parisien — Administración" : "Café Parisien" }}
          </span>
        </div>

        <div style="display: flex; align-items: center; gap: 15px;">
          @if (!$isAdminRoute && !request()->is('Carrito'))
            <button class="cart-topbar-btn" onclick="openCart()" aria-label="Ver carrito">
              <span class="cart-topbar-icon">🛒</span>
              <span class="cart-badge" id="cartBadge" style="display:none;">0</span>
            </button>
          @endif
          <span class="topbar-subtitle">Sistema de Ventas</span>
        </div>
      </header>
    @endif

    @if (!$isLandingRoute)
      <!-- SIDEBAR (MENÚ LATERAL - SOLO PARA RUTAS INTERNAS Y DE ADMIN) -->
      <nav class="sidebar" role="navigation" aria-label="Menú principal">
        @if ($isAdminRoute)
          <!-- MENÚ DE ADMINISTRACIÓN -->
          <span class="sidebar-label">Panel Control</span>
          <a class="nav-item {{ request()->is('admin') && !request()->is('admin/empleados') ? 'active' : '' }}" href="/admin" aria-label="Panel de Control">
            <span class="nav-icon">📊</span>
            <span class="nav-text">Panel Control</span>
          </a>
          <a class="nav-item {{ request()->is('admin/kds') ? 'active' : '' }}" href="/admin/kds" aria-label="Pedidos y KDS">
            <span class="nav-icon">📥</span>
            <span class="nav-text">Consola KDS</span>
          </a>

          <span class="sidebar-label">Gestión Catálogo</span>
          <a class="nav-item {{ request()->is('admin/registrar') ? 'active' : '' }}" href="/admin/registrar" aria-label="Registrar producto">
            <span class="nav-icon">➕</span>
            <span class="nav-text">Registrar Producto</span>
          </a>
          <a class="nav-item {{ request()->is('admin/reabastecer') ? 'active' : '' }}" href="/admin/reabastecer" aria-label="Reabastecer producto">
            <span class="nav-icon">🔄</span>
            <span class="nav-text">Abastecer</span>
          </a>
          <a class="nav-item {{ request()->is('admin/precio') ? 'active' : '' }}" href="/admin/precio" aria-label="Cambiar precio">
            <span class="nav-icon">🏷️</span>
            <span class="nav-text">Ajustar Precios</span>
          </a>
          <a class="nav-item {{ request()->is('admin/editar') ? 'active' : '' }}" href="/admin/editar" aria-label="Editar nombre del producto">
            <span class="nav-icon">✏️</span>
            <span class="nav-text">Editar Nombre</span>
          </a>
          <a class="nav-item {{ request()->is('admin/eliminar') ? 'active' : '' }}" href="/admin/eliminar" aria-label="Eliminar producto">
            <span class="nav-icon">🗑️</span>
            <span class="nav-text">Eliminar Producto</span>
          </a>

          <span class="sidebar-label">Salón y Operaciones</span>
          <a class="nav-item {{ request()->is('admin/zonas') ? 'active' : '' }}" href="/admin/zonas" aria-label="Zonas y Mesas">
            <span class="nav-icon">🏛️</span>
            <span class="nav-text">Mesas & Reservas</span>
          </a>

          <span class="sidebar-label">Recursos Humanos</span>
          <a class="nav-item {{ request()->is('admin/empleados') ? 'active' : '' }}" href="/admin/empleados" aria-label="Empleados">
            <span class="nav-icon">👤</span>
            <span class="nav-text">Empleados</span>
          </a>
          <a class="nav-item {{ request()->is('admin/turnos') ? 'active' : '' }}" href="/admin/turnos" aria-label="Turnos">
            <span class="nav-icon">⏰</span>
            <span class="nav-text">Turnos</span>
          </a>
          <a class="nav-item {{ request()->is('admin/asignacionturnos') ? 'active' : '' }}" href="/admin/asignacionturnos" aria-label="Asignación Turnos">
            <span class="nav-icon">📅</span>
            <span class="nav-text">Asignación Turnos</span>
          </a>

          <span class="sidebar-label">Finanzas y Compras</span>
          <a class="nav-item {{ request()->is('admin/facturas') ? 'active' : '' }}" href="/admin/facturas" aria-label="Facturas">
            <span class="nav-icon">🧾</span>
            <span class="nav-text">Facturas</span>
          </a>
          <a class="nav-item {{ request()->is('admin/proveedores') ? 'active' : '' }}" href="/admin/proveedores" aria-label="Proveedores">
            <span class="nav-icon">🏢</span>
            <span class="nav-text">Proveedores</span>
          </a>
          <a class="nav-item {{ request()->is('admin/ordenes-compra') ? 'active' : '' }}" href="/admin/ordenes-compra" aria-label="Órdenes Compra">
            <span class="nav-icon">📦</span>
            <span class="nav-text">Órdenes Compra</span>
          </a>
          <a class="nav-item {{ request()->is('admin/corte-caja') ? 'active' : '' }}" href="/admin/corte-caja" aria-label="Corte de Caja">
            <span class="nav-icon">💰</span>
            <span class="nav-text">Corte de Caja</span>
          </a>

          <span class="sidebar-label">Sistema y Lealtad</span>
          <a class="nav-item {{ request()->is('admin/usuarios-roles') ? 'active' : '' }}" href="/admin/usuarios-roles" aria-label="Usuarios & Roles">
            <span class="nav-icon">👥</span>
            <span class="nav-text">Usuarios & Roles</span>
          </a>
          <a class="nav-item {{ request()->is('admin/roles') ? 'active' : '' }}" href="/admin/roles" aria-label="Roles">
            <span class="nav-icon">🔑</span>
            <span class="nav-text">Roles</span>
          </a>
          <a class="nav-item {{ request()->is('admin/configuracion') ? 'active' : '' }}" href="/admin/configuracion" aria-label="Configuración">
            <span class="nav-icon">⚙️</span>
            <span class="nav-text">Configuración</span>
          </a>
          <a class="nav-item {{ request()->is('admin/cupones') ? 'active' : '' }}" href="/admin/cupones" aria-label="Cupones">
            <span class="nav-icon">🎟️</span>
            <span class="nav-text">Cupones</span>
          </a>
          <a class="nav-item {{ request()->is('admin/notificaciones') ? 'active' : '' }}" href="/admin/notificaciones" aria-label="Notificaciones">
            <span class="nav-icon">🔔</span>
            <span class="nav-text">Notificaciones</span>
          </a>
          <a class="nav-item {{ request()->is('admin/resenas-productos') ? 'active' : '' }}" href="/admin/resenas-productos" aria-label="Reseñas Productos">
            <span class="nav-icon">⭐</span>
            <span class="nav-text">Reseñas Productos</span>
          </a>
          <a class="nav-item {{ request()->is('admin/categorias') ? 'active' : '' }}" href="/admin/categorias" aria-label="Categorías del Menú">
            <span class="nav-icon">📁</span>
            <span class="nav-text">Categorías</span>
          </a>
          <a class="nav-item {{ request()->is('admin/modificadores') ? 'active' : '' }}" href="/admin/modificadores" aria-label="Modificadores">
            <span class="nav-icon">🛠️</span>
            <span class="nav-text">Modificadores</span>
          </a>
          <a class="nav-item {{ request()->is('admin/metodos-pago') ? 'active' : '' }}" href="/admin/metodos-pago" aria-label="Pasarelas de Pago">
            <span class="nav-icon">💳</span>
            <span class="nav-text">Pasarelas de Pago</span>
          </a>
          <a class="nav-item {{ request()->is('admin/puntos-lealtad') ? 'active' : '' }}" href="/admin/puntos-lealtad" aria-label="Puntos de Lealtad">
            <span class="nav-icon">⭐</span>
            <span class="nav-text">Puntos Clientes</span>
          </a>
          <a class="nav-item {{ request()->is('admin/logs-auditoria') ? 'active' : '' }}" href="/admin/logs-auditoria" aria-label="Logs de Auditoría">
            <span class="nav-icon">📜</span>
            <span class="nav-text">Logs Auditoría</span>
          </a>

          <!-- Accesos directos Admin -->
          <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(122, 111, 93, 0.15);">
            <div style="padding: 10px 20px; color: var(--text-secondary); font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">
              Modo Administrador
            </div>
            <a class="nav-item" href="/" aria-label="Vista Cliente">
              <span class="nav-icon">👥</span>
              <span class="nav-text" style="font-size: 0.9rem;">Vista Cliente</span>
            </a>
            <a class="nav-item" href="/logout" aria-label="Cerrar sesión">
              <span class="nav-icon">🚪</span>
              <span class="nav-text" style="font-size: 0.9rem;">Cerrar Sesión</span>
            </a>
          </div>
        @else
          <!-- MENÚ DEL CLIENTE EN PORTAL DE CLIENTE -->
          <span class="sidebar-label">Navegación</span>
          <a class="nav-item {{ request()->is('/') ? 'active' : '' }}" href="/" aria-label="Inicio">
            <span class="nav-icon">🏠</span>
            <span class="nav-text">Inicio</span>
          </a>
          <a class="nav-item {{ request()->is('menu') ? 'active' : '' }}" href="/menu" aria-label="Ver especialidades">
            <span class="nav-icon">📋</span>
            <span class="nav-text">Especialidades</span>
          </a>
          <a class="nav-item {{ request()->is('Carrito') ? 'active' : '' }}" href="/Carrito" aria-label="Ver carrito de compras">
            <span class="nav-icon">🛒</span>
            <span class="nav-text">Carrito de Compras</span>
          </a>

          @auth
            <span class="sidebar-label">Mi Portal</span>
            <a class="nav-item {{ request()->is('MisPedidos') ? 'active' : '' }}" href="/MisPedidos" aria-label="Ver mis pedidos">
              <span class="nav-icon">📜</span>
              <span class="nav-text">Mis Pedidos</span>
            </a>
            <a class="nav-item {{ request()->is('Reservar') ? 'active' : '' }}" href="/Reservar" aria-label="Reservar mesa">
              <span class="nav-icon">🪑</span>
              <span class="nav-text">Reservar Mesa</span>
            </a>
            <a class="nav-item {{ request()->is('MisPuntos') ? 'active' : '' }}" href="/MisPuntos" aria-label="Ver mis puntos">
              <span class="nav-icon">⭐</span>
              <span class="nav-text">Mis Puntos Club</span>
            </a>
          @endauth

          <!-- Bloque decorativo con miniatura café -->
          <div class="sidebar-cafe-card">
            <div class="sidebar-cafe-bg" style="background-image: url('/images/a306b42975fe15719893411736ce48ab.jpg');"></div>
            <div class="sidebar-cafe-overlay"></div>
            <div class="sidebar-cafe-info">
              <span class="cafe-card-sub">Café Parisien</span>
              <span class="cafe-card-main">Cafetería</span>
            </div>
          </div>

          <!-- Accesos de Usuario y Administrador -->
          <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(122, 111, 93, 0.15);">
            @auth
              <div style="padding: 10px 20px; color: var(--text-secondary); font-size: 1.05rem; font-weight: 600; margin-bottom: 5px;">
                ¡Hola, {{ auth()->user()->Username }}!
              </div>
              @if (auth()->user()->isAdmin())
                <a class="nav-item" href="/admin" aria-label="Ir al Panel">
                  <span class="nav-icon">⚜️</span>
                  <span class="nav-text" style="font-size: 0.82rem; font-weight: 600;">Panel Admin</span>
                </a>
              @endif
              <a class="nav-item" href="/logout" aria-label="Cerrar sesión">
                <span class="nav-icon">🚪</span>
                <span class="nav-text" style="font-size: 0.82rem;">Cerrar Sesión</span>
              </a>
            @else
              <a class="nav-item" href="/login" aria-label="Iniciar Sesión">
                <span class="nav-icon">🔑</span>
                <span class="nav-text" style="font-size: 0.9rem; font-weight: 600;">Iniciar Sesión</span>
              </a>
              <a class="nav-item" href="/registro" aria-label="Crear Cuenta">
                <span class="nav-icon">📝</span>
                <span class="nav-text" style="font-size: 0.9rem;">Registrarse</span>
              </a>
            @endauth
          </div>
        @endif
      </nav>
    @endif

    <!-- CONTENIDO DE LA PÁGINA -->
    <main class="main-content" id="main" style="{{ $isLandingRoute ? 'padding: 40px 60px; max-width: 1300px; margin: 0 auto; width: 100%;' : '' }}">
      @yield('content')
    </main>

  </div>

  <!-- TOAST NOTIFICATIONS -->
  <div id="toast-container" aria-live="polite" aria-atomic="false"></div>

  <!-- HELP MODAL -->
  <div id="help-modal-overlay" class="help-modal-overlay" onclick="cerrarAyuda()">
    <div class="help-modal" onclick="event.stopPropagation()">
      <h3 class="help-modal-title">
        <span>❓</span>
        <span id="help-modal-title-text">Ayuda del Sistema</span>
      </h3>
      <div id="help-modal-body-text" class="help-modal-body">
        Explicación de la sección.
      </div>
      <div class="help-modal-footer">
        <button onclick="cerrarAyuda()" class="btn-primary" style="width: auto; padding: 8px 20px;">Entendido</button>
      </div>
    </div>
  </div>

  <style>
    /* Estilos para el Modal de Ayuda Flotante */
    .help-modal-overlay {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(10, 18, 30, 0.65);
      backdrop-filter: blur(6px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 99999;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
    }
    .help-modal-overlay.show {
      opacity: 1;
      pointer-events: auto;
    }
    .help-modal {
      background: var(--bg-card);
      border: 2px solid var(--accent-gold);
      border-radius: var(--radius-md);
      max-width: 500px;
      width: 90%;
      padding: 26px;
      box-shadow: var(--shadow-lg);
      transform: scale(0.92);
      transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.2);
    }
    .help-modal-overlay.show .help-modal {
      transform: scale(1);
    }
    .help-modal-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.6rem;
      color: var(--accent-navy);
      margin-top: 0;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px dashed var(--border);
      padding-bottom: 8px;
    }
    .help-modal-body {
      font-size: 0.95rem;
      line-height: 1.55;
      color: var(--text-primary);
      margin-bottom: 20px;
    }
    .help-btn-trigger {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      background: var(--bg-secondary);
      border: 1px solid var(--border);
      color: var(--text-primary);
      width: 28px;
      height: 28px;
      border-radius: 50%;
      font-size: 0.95rem;
      margin-left: 10px;
      transition: all 0.2s ease;
      vertical-align: middle;
    }
    .help-btn-trigger:hover {
      background: var(--accent-gold);
      color: #fff;
      transform: scale(1.1);
      box-shadow: 0 0 8px rgba(197, 160, 89, 0.4);
    }
  </style>

  <script>
    function abrirAyuda(titulo, descripcion) {
      document.getElementById('help-modal-title-text').innerText = titulo;
      document.getElementById('help-modal-body-text').innerHTML = descripcion;
      document.getElementById('help-modal-overlay').classList.add('show');
    }
    function cerrarAyuda() {
      document.getElementById('help-modal-overlay').classList.remove('show');
    }

    function showToast(mensaje, tipo) {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `toast toast-${tipo}`;
      toast.innerHTML = `<span class="toast-icon">${tipo === 'success' ? '⚜️' : '⚠️'}</span><span>${mensaje}</span>`;
      container.appendChild(toast);
      setTimeout(() => toast.classList.add('toast-show'), 10);
      setTimeout(() => {
        toast.classList.remove('toast-show');
        setTimeout(() => toast.remove(), 400);
      }, 4000);
    }

    document.addEventListener('DOMContentLoaded', () => {
      const params = new URLSearchParams(window.location.search);
      const msg = params.get('msg');
      const tipo = params.get('tipo');
      if (msg) showToast(decodeURIComponent(msg), tipo || 'success');

      // ── Sidebar scroll persistence ──
      const sidebar = document.querySelector('.sidebar');
      if (sidebar) {
        const savedScroll = sessionStorage.getItem('sidebarScroll');
        if (savedScroll) {
          sidebar.scrollTop = parseInt(savedScroll, 10);
        }

        const activeItem = sidebar.querySelector('.nav-item.active');
        if (activeItem) {
          setTimeout(() => {
            activeItem.scrollIntoView({ block: 'center', behavior: 'instant' });
          }, 50);
        }

        sidebar.querySelectorAll('a.nav-item').forEach(link => {
          link.addEventListener('click', () => {
            sessionStorage.setItem('sidebarScroll', sidebar.scrollTop.toString());
          });
        });
      }
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  @yield('scripts')
</body>
</html>