@extends('layouts.app')

@section('title', 'Directorio de Proveedores - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🏢</span>
    <div>
      <h1 class="section-title">
        Directorio de Proveedores
        <button class="help-btn-trigger" onclick="abrirAyuda('Directorio de Proveedores', 'Módulo de gestión de proveedores e insumos del restaurante. Registre nuevos proveedores de materias primas (café, lácteos, panadería) y controle su estatus de activación.')">❓</button>
      </h1>
      <p class="section-desc">Consulte y administre la lista de distribuidores autorizados de materia prima e insumos.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  @if (isset($errors) && $errors->any())
    <div style="background: rgba(220, 53, 69, 0.1); border: 1px solid var(--accent-err); color: var(--accent-err); padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚠️ {{ $errors->first() }}
    </div>
  @endif

  <!-- Botón Agregar Proveedor -->
  <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
    <button class="btn-primary" onclick="toggleForm('form-nuevo-proveedor')" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
      ➕ Registrar Nuevo Proveedor
    </button>
    <a href="/admin/ordenes-compra" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem; width: auto; background: var(--accent-navy);">
      📦 Ver Órdenes de Compra
    </a>
  </div>

  <!-- Formulario Nuevo Proveedor -->
  <div id="form-nuevo-proveedor" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-gold);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">🏢 Alta de Proveedor / Distribuidor</h3>
    <form action="/admin/proveedores/crear" method="POST">
      @csrf
      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Razón Social / Nombre Comercial del Proveedor</label>
        <input type="text" name="razonSocial" class="search-input" placeholder="Ej. Distribuidora de Café Gourmet de Chiapas S.A." required style="width: 100%; border: 1px solid var(--border);">
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Guardar Proveedor</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nuevo-proveedor')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <!-- Tabla de Proveedores -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Nómina de Proveedores Registrados
    </h2>

    <div class="table-wrap">
      <table aria-label="Directorio de Proveedores" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Proveedor ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Razón Social / Distribuidor</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus Actual</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acciones (Activar/Desactivar)</th>
          </tr>
        </thead>
        <tbody>
          @if ($proveedores->isEmpty())
            <tr>
              <td colspan="4" style="text-align: center; padding: 40px; opacity: 0.5;">No hay proveedores registrados en la base de datos.</td>
            </tr>
          @else
            @foreach ($proveedores as $p)
              @php $activo = $p->Activo ?? 1; @endphp
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $p->ProveedorId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                  🏢 {{ $p->RazonSocial }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  @if ($activo == 1)
                    <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      🟢 Habilitado (Activo)
                    </span>
                  @else
                    <span style="background: rgba(220,53,69,0.1); color: var(--accent-err); border: 1px solid rgba(220,53,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      🔴 Deshabilitado (Inactivo)
                    </span>
                  @endif
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  <form action="/admin/proveedores/{{ $p->ProveedorId }}/toggle" method="POST" style="display: inline;">
                    @csrf
                    @if ($activo == 1)
                      <button type="submit" class="btn-primary" onclick="return confirm('¿Está seguro de desactivar al proveedor {{ $p->RazonSocial }}?')" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: var(--accent-err); border: 1px solid var(--accent-err);">
                        🔴 Desactivar Proveedor
                      </button>
                    @else
                      <button type="submit" class="btn-primary" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: #28a745; border: 1px solid #28a745;">
                        🟢 Activar Proveedor
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
