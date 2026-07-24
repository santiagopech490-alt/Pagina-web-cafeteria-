@extends('layouts.app')

@section('title', 'Gestión de Roles y Permisos - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🔑</span>
    <div>
      <h1 class="section-title">
        Gestión de Roles y Permisos
        <button class="help-btn-trigger" onclick="abrirAyuda('Gestión de Roles', 'Módulo de administración de perfiles de usuario. Permite consultar todos los roles registrados en el sistema, revisar la cantidad de usuarios asociados a cada rol y ACTIVAR o DESACTIVAR el estatus de cada rol de forma dinámica.')">❓</button>
      </h1>
      <p class="section-desc">Administre los perfiles de acceso y active o desactive el estatus de cada rol en el sistema.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div style="background: rgba(220, 53, 69, 0.1); border: 1px solid var(--accent-err); color: var(--accent-err); padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚠️ {{ $errors->first() }}
    </div>
  @endif

  <!-- Métricas Rápidas -->
  @php
    $totalRoles = count($roles);
    $rolesActivos = $roles->where('Activo', 1)->count();
    $rolesInactivos = $roles->where('Activo', 0)->count();
  @endphp
  <div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
      <div class="stat-icon-wrap purple">🔑</div>
      <div>
        <div class="stat-value">{{ $totalRoles }}</div>
        <div class="stat-label">Roles Registrados</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap orange">🟢</div>
      <div>
        <div class="stat-value">{{ $rolesActivos }}</div>
        <div class="stat-label">Roles Habilitados (Activos)</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap orange">🔴</div>
      <div>
        <div class="stat-value">{{ $rolesInactivos }}</div>
        <div class="stat-label">Roles Deshabilitados (Inactivos)</div>
      </div>
    </div>
  </div>

  <!-- Botón Agregar Rol -->
  <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
    <button class="btn-primary" onclick="toggleForm('form-nuevo-rol')" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
      ➕ Crear Nuevo Rol de Usuario
    </button>
  </div>

  <!-- Formulario Nuevo Rol -->
  <div id="form-nuevo-rol" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-gold);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">🔑 Registrar Nuevo Perfil / Rol</h3>
    <form action="/admin/roles/crear" method="POST">
      @csrf
      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px; margin-bottom: 15px;">
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Nombre del Rol</label>
          <input type="text" name="nombre" class="search-input" placeholder="Ej. Supervisor de Barra" required style="width: 100%; border: 1px solid var(--border);">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Descripción de Permisos</label>
          <input type="text" name="descripcion" class="search-input" placeholder="Ej. Encargado de supervisar cortes de caja e inventario..." style="width: 100%; border: 1px solid var(--border);">
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Guardar Rol</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nuevo-rol')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <!-- Tabla de Roles -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Listado de Perfiles y Estatus</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Gestión de Roles de Usuario" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Rol ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre del Rol</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Descripción / Alcance</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Usuarios Vinculados</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus Actual</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acciones (Activar/Desactivar)</th>
          </tr>
        </thead>
        <tbody>
          @if ($roles->isEmpty())
            <tr>
              <td colspan="6" style="text-align: center; padding: 40px; opacity: 0.5;">No hay roles registrados en el sistema.</td>
            </tr>
          @else
            @foreach ($roles as $r)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $r->RolId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 700; color: var(--accent-navy);">
                  🔑 {{ $r->Nombre }}
                </td>
                <td style="padding: 16px 14px; color: var(--text-primary); font-style: italic;">
                  {{ $r->Descripcion ? $r->Descripcion : 'Sin descripción asignada' }}
                </td>
                <td style="padding: 16px 14px; text-align: center; font-weight: bold;">
                  👥 {{ $r->totalUsuarios }} usuarios
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  @if ($r->Activo == 1)
                    <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.78rem; font-weight: bold;">
                      🟢 Habilitado (Activo)
                    </span>
                  @else
                    <span style="background: rgba(220,53,69,0.1); color: var(--accent-err); border: 1px solid rgba(220,53,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.78rem; font-weight: bold;">
                      🔴 Deshabilitado (Inactivo)
                    </span>
                  @endif
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  <form action="/admin/roles/{{ $r->RolId }}/toggle" method="POST" style="display: inline;">
                    @csrf
                    @if ($r->Activo == 1)
                      <button type="submit" class="btn-primary" onclick="return confirm('¿Está seguro de desactivar el rol {{ $r->Nombre }}?')" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: var(--accent-err); border: 1px solid var(--accent-err);">
                        🔴 Desactivar Rol
                      </button>
                    @else
                      <button type="submit" class="btn-primary" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: #28a745; border: 1px solid #28a745;">
                        🟢 Activar Rol
                      </button>
                    @endif
                  </form>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function toggleForm(id) {
    const el = document.getElementById(id);
    if (el.style.display === 'none') {
      el.style.display = 'block';
    } else {
      el.style.display = 'none';
    }
  }
</script>
@endsection
