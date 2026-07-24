@extends('layouts.app')

@section('title', 'Configuración del Sistema - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">⚙️</span>
    <div>
      <h1 class="section-title">
        Configuración General del Sistema
        <button class="help-btn-trigger" onclick="abrirAyuda('Configuración General', 'Panel central de parámetros del sistema. Ajuste el nombre comercial del establecimiento, dirección fiscal, teléfono, porcentaje de IVA, mensajes de bienvenida y reglas de lealtad.')">❓</button>
      </h1>
      <p class="section-desc">Administración de valores globales, datos comerciales y parámetros de facturación del establecimiento.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  <!-- Botón Agregar Parámetro -->
  <div style="margin-bottom: 25px;">
    <button class="btn-primary" onclick="toggleForm('form-nueva-config')" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
      ➕ Añadir Parámetro de Configuración
    </button>
  </div>

  <!-- Formulario Nuevo Parámetro -->
  <div id="form-nueva-config" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-gold);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">⚙️ Guardar Parámetro de Sistema</h3>
    <form action="/admin/configuracion/crear" method="POST">
      @csrf
      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px; margin-bottom: 15px;">
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Clave / Identificador Único</label>
          <input type="text" name="clave" class="search-input" placeholder="Ej. MENSAJE_TICKET" required style="width: 100%; border: 1px solid var(--border);">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Valor del Parámetro</label>
          <input type="text" name="valor" class="search-input" placeholder="Ej. Gracias por su preferencia" required style="width: 100%; border: 1px solid var(--border);">
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Guardar Parámetro</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nueva-config')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <!-- Tabla de Parámetros -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Parámetros de Operación Registrados
    </h2>

    <div class="table-wrap">
      <table aria-label="Parámetros de Configuración del Sistema" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Clave / Variable</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Valor Asignado</th>
          </tr>
        </thead>
        <tbody>
          @if ($configs->isEmpty())
            <tr>
              <td colspan="3" style="text-align: center; padding: 40px; opacity: 0.5;">No hay parámetros de configuración cargados.</td>
            </tr>
          @else
            @foreach ($configs as $c)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $c->ConfigId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                  🔑 {{ $c->Clave }}
                </td>
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-gold-dark);">
                  {{ $c->Valor }}
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
