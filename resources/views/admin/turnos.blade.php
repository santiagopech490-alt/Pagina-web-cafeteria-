@extends('layouts.app')

@section('title', 'Turnos Operativos - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">⏰</span>
    <div>
      <h1 class="section-title">
        Turnos Operativos del Personal
        <button class="help-btn-trigger" onclick="abrirAyuda('Turnos Operativos', 'Módulo de definición de horarios laborales. Defina los turnos del establecimiento (Matutino, Vespertino, Nocturno) especificando sus horas de entrada y salida.')">❓</button>
      </h1>
      <p class="section-desc">Defina los horarios y turnos de trabajo para la asignación de roles y cronogramas de los empleados.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  <!-- Botones de Acción -->
  <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
    <button class="btn-primary" onclick="toggleForm('form-nuevo-turno')" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
      ➕ Registrar Nuevo Turno
    </button>
    <a href="/admin/asignacionturnos" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem; width: auto; background: var(--accent-navy);">
      📅 Asignación de Turnos a Empleados
    </a>
  </div>

  <!-- Formulario Nuevo Turno -->
  <div id="form-nuevo-turno" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-gold);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">⏰ Registrar Nuevo Turno Laboral</h3>
    <form action="/admin/turnos/crear" method="POST">
      @csrf
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Nombre del Turno</label>
          <input type="text" name="nombre" class="search-input" placeholder="Ej. Turno Matutino Baristas" required style="width: 100%; border: 1px solid var(--border);">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Hora de Inicio (Entrada)</label>
          <input type="time" name="horaInicio" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Hora de Término (Salida)</label>
          <input type="time" name="horaFin" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Guardar Turno</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nuevo-turno')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Horarios y Turnos Registrados
    </h2>

    <div class="table-wrap">
      <table aria-label="Turnos Operativos Registrados" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Turno ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Denominación del Turno</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Hora de Entrada</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Hora de Salida</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($turnos as $t)
            <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
              <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                #{{ $t->TurnoId }}
              </td>
              <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                ⏰ {{ $t->Nombre }}
              </td>
              <td style="padding: 16px 14px; text-align: center; font-weight: bold; color: #28a745;">
                🌅 {{ date('h:i A', strtotime($t->HoraInicio)) }}
              </td>
              <td style="padding: 16px 14px; text-align: center; font-weight: bold; color: var(--accent-gold-dark);">
                🌙 {{ date('h:i A', strtotime($t->HoraFin)) }}
              </td>
            </tr>
          @endforeach
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
