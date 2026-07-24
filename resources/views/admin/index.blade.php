@extends('layouts.app')

@section('title', 'Panel de Control')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">⚜️</span>
    <div>
      <h1 class="section-title">Panel de Control Administrativo</h1>
      <p class="section-desc">Bienvenido al &aacute;rea de gesti&oacute;n. Seleccione una operaci&oacute;n del cat&aacute;logo o supervise el inventario.</p>
    </div>
  </div>

  <!-- Panel de Accesos R&aacute;pidos -->
  <div class="admin-shortcuts-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <a href="/admin/tablas?tabla=pedidos" class="shortcut-card" style="border-color: rgba(33, 150, 243, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(33, 150, 243, 0.08); color: #2196F3;"> KDS </div>
      <div class="shortcut-info">
        <h3>Pedidos y Cocina (KDS)</h3>
        <p>Monitorea y despacha las &oacute;rdenes de los clientes en tiempo real.</p>
      </div>
    </a>
    <a href="/admin/tablas?tabla=mesas" class="shortcut-card" style="border-color: rgba(76, 175, 80, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(76, 175, 80, 0.08); color: #4CAF50;">🪑</div>
      <div class="shortcut-info">
        <h3>Mesas y Reservaciones</h3>
        <p>Controla el aforo, asigna mesas y gestiona el calendario.</p>
      </div>
    </a>
    <a href="/admin/tablas" class="shortcut-card" style="border-color: rgba(255, 152, 0, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(255, 152, 0, 0.08); color: #FF9800;">🗄️</div>
      <div class="shortcut-info">
        <h3>Gestor de tablas</h3>
        <p>Administra de forma directa las tablas de MySQL en tiempo real.</p>
      </div>
    </a>

    <!-- SECCION RECURSOS HUMANOS -->
    <a href="/admin/empleados" class="shortcut-card" style="border-color: rgba(156, 39, 176, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(156, 39, 176, 0.08); color: #9C27B0;">👤</div>
      <div class="shortcut-info">
        <h3>Gesti&oacute;n Empleados</h3>
        <p>Consulte la lista de personal y sus datos de contrataci&oacute;n.</p>
      </div>
    </a>

    <!-- SECCION FINANZAS Y COMPRAS -->
    <a href="/admin/facturas" class="shortcut-card" style="border-color: rgba(0, 150, 136, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(0, 150, 136, 0.08); color: #009688;">🧾</div>
      <div class="shortcut-info">
        <h3>Facturaci&oacute;n e IVA</h3>
        <p>Consulte las facturas emitidas y desglose de IVA del local.</p>
      </div>
    </a>
    <a href="/admin/ordenes-compra" class="shortcut-card" style="border-color: rgba(0, 150, 136, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(0, 150, 136, 0.08); color: #009688;">📦</div>
      <div class="shortcut-info">
        <h3>&Oacute;rdenes de Compra</h3>
        <p>Supervise los pedidos de abastecimiento mayorista a proveedores.</p>
      </div>
    </a>

    <!-- SECCION SISTEMA Y LEALTAD -->
    <a href="/admin/usuarios-roles" class="shortcut-card" style="border-color: rgba(121, 85, 72, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(121, 85, 72, 0.08); color: #795548;">👥</div>
      <div class="shortcut-info">
        <h3>Usuarios & Roles</h3>
        <p>Administre cuentas de clientes, cajeros y administradores.</p>
      </div>
    </a>
    <a href="/admin/logs-auditoria" class="shortcut-card" style="border-color: rgba(121, 85, 72, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(121, 85, 72, 0.08); color: #795548;">📜</div>
      <div class="shortcut-info">
        <h3>Logs de Auditor&iacute;a</h3>
        <p>Historial de cambios cr&iacute;ticos y acciones administrativas.</p>
      </div>
    </a>
    <a href="/admin/cupones" class="shortcut-card" style="border-color: rgba(233, 30, 99, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(233, 30, 99, 0.08); color: #E91E63;">🎟️</div>
      <div class="shortcut-info">
        <h3>Cupones de Descuento</h3>
        <p>Gestione campa&ntilde;as de marketing, validez y porcentajes.</p>
      </div>
    </a>
    <a href="/admin/categorias" class="shortcut-card" style="border-color: rgba(63, 81, 181, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(63, 81, 181, 0.08); color: #3F51B5;">📁</div>
      <div class="shortcut-info">
        <h3>Categor&iacute;as</h3>
        <p>Gestione las agrupaciones del cat&aacute;logo del men&uacute; principal.</p>
      </div>
    </a>
    <a href="/admin/puntos-lealtad" class="shortcut-card" style="border-color: rgba(63, 81, 181, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(63, 81, 181, 0.08); color: #3F51B5;">⭐</div>
      <div class="shortcut-info">
        <h3>Puntos Clientes</h3>
        <p>Consulte la acumulaci&oacute;n de puntos de fidelidad por usuario.</p>
      </div>
    </a>
    <a href="/admin/resenas-productos" class="shortcut-card" style="border-color: rgba(63, 81, 181, 0.3);">
      <div class="shortcut-icon-box" style="background: rgba(63, 81, 181, 0.08); color: #3F51B5;">⭐</div>
      <div class="shortcut-info">
        <h3>Rese&ntilde;as de Productos</h3>
        <p>Modere y consulte los comentarios y puntuaciones de clientes.</p>
      </div>
    </a>
  </div>

  <!-- Estad&iacute;sticas Cortas -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrap purple">⚜️</div>
      <div>
        <div class="stat-value">{{ count($productos) }}</div>
        <div class="stat-label">Total Productos</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap orange">⚠️</div>
      <div>
        <div class="stat-value">{{ $productos->where('Existencia', '<=', 10)->count() }}</div>
        <div class="stat-label">Con Alerta de Stock Bajo (=10)</div>
      </div>
    </div>
  </div>

  <!-- Vista R&aacute;pida de Inventario Administrativo -->
  <div class="card">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--accent-navy); margin-bottom: 15px;">Control de Inventario</h3>
    <div class="table-wrap">
      <table aria-label="Tabla de control de inventario">
        <thead>
          <tr>
            <th>C&oacute;digo</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Existencia</th>
            <th>Acci&oacute;n R&aacute;pida</th>
          </tr>
        </thead>
        <tbody>
          @if ($productos->isEmpty())
            <tr>
              <td colspan="5" style="text-align:center;padding:2rem;opacity:.5;">
                No hay productos en el sistema.
              </td>
            </tr>
          @endif
          @foreach ($productos as $p)
            @php
              $stockClass = $p->Existencia == 0 ? "stock-vacio" : ($p->Existencia <= 10 ? "stock-bajo" : "stock-ok");
              $stockIcon  = $p->Existencia == 0 ? "🔴" : ($p->Existencia <= 10 ? "🟡" : "🟢");
            @endphp
            <tr class="table-row-anim">
              <td><span class="badge-codigo">{{ $p->Codigo }}</span></td>
              <td>{{ $p->Nombre }}</td>
              <td class="precio-cell">${{ number_format($p->Precio, 2) }}</td>
              <td><span class="{{ $stockClass }}">{{ $stockIcon }} {{ $p->Existencia }} uds.</span></td>
              <td>
                <a href="/admin/tablas?tabla=productos" class="back-link" style="font-weight: 600;">Editar en tabla</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection