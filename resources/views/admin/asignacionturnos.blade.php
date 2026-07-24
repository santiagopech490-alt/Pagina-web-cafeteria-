@extends('layouts.app')

@section('title', 'Asignación de Turnos - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">📅</span>
    <div>
      <h1 class="section-title">
        Asignación de Turnos al Personal
        <button class="help-btn-trigger" onclick="abrirAyuda('Asignación de Turnos', 'Módulo de programación de horarios del personal. Asigne turnos de trabajo a cada colaborador (baristas, meseros, cajeros) especificando la fecha correspondiente.')">❓</button>
      </h1>
      <p class="section-desc">Programe y controle el rol de turnos asignados a cada empleado del establecimiento.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  <!-- Botones de Acción -->
  <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
    <button class="btn-primary" onclick="toggleForm('form-nueva-asig')" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
      ➕ Programar Asignación de Turno
    </button>
    <a href="/admin/turnos" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem; width: auto; background: var(--accent-navy);">
      ⏰ Catálogo de Turnos
    </a>
  </div>

  <!-- Formulario Nueva Asignación -->
  <div id="form-nueva-asig" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-navy);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">📅 Asignar Turno a Empleado</h3>
    <form action="/admin/asignacionturnos/crear" method="POST">
      @csrf
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Colaborador / Empleado</label>
          <select name="empleadoId" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
            @foreach ($empleados as $emp)
              <option value="{{ $emp->EmpleadoId }}">👤 {{ $emp->Nombre }} ({{ $emp->Puesto ?? 'Empleado' }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Turno a Asignar</label>
          <select name="turnoId" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
            @foreach ($turnos as $tur)
              <option value="{{ $tur->TurnoId }}">⏰ {{ $tur->Nombre }} ({{ date('h:i A', strtotime($tur->HoraInicio)) }} - {{ date('h:i A', strtotime($tur->HoraFin)) }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Fecha del Turno</label>
          <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Asignar Turno</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nueva-asig')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Programación de Rol de Turnos
    </h2>

    <div class="table-wrap">
      <table aria-label="Programación de Rol de Turnos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Empleado / Colaborador</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Turno Asignado</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Fecha Programada</th>
          </tr>
        </thead>
        <tbody>
          @if ($asignaciones->isEmpty())
            <tr>
              <td colspan="4" style="text-align: center; padding: 40px; opacity: 0.5;">No hay asignaciones de turno programadas.</td>
            </tr>
          @else
            @foreach ($asignaciones as $a)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $a->AsignacionId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                  👤 {{ $a->EmpleadoNombre ?? 'Empleado' }}
                </td>
                <td style="padding: 16px 14px; font-weight: 500;">
                  ⏰ {{ $a->TurnoNombre ?? 'Turno General' }}
                </td>
                <td style="padding: 16px 14px; text-align: center; font-weight: bold; color: var(--accent-navy);">
                  📅 {{ date('d/m/Y', strtotime($a->Fecha)) }}
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
