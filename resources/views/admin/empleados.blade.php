@extends('layouts.app')

@section('title', 'Plantilla de Empleados - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">👥</span>
    <div>
      <h1 class="section-title">
        Plantilla de Empleados
        <button class="help-btn-trigger" onclick="abrirAyuda('Plantilla de Empleados', 'Directorio activo del equipo de trabajo de la cafetería. Muestra la nómina de colaboradores registrados con sus respectivos puestos asignados (baristas, cocineros, administradores) y permite ACTIVAR o DESACTIVAR el estatus laboral de cada colaborador.')">❓</button>
      </h1>
      <p class="section-desc">Consulte los miembros del equipo y controle el estatus de activación de cada colaborador.</p>
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

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Equipo Café Parisien</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Nómina de Empleados" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Empleado ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre del Colaborador</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Puesto</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus Actual</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acciones (Activar/Desactivar)</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="5" style="text-align: center; padding: 40px; opacity: 0.5;">No hay empleados registrados en el sistema.</td>
            </tr>
          @else
            @foreach ($filas as $f)
              @php $activo = $f->Activo ?? 1; @endphp
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $f->EmpleadoId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                  {{ $f->Nombre }}
                </td>
                <td style="padding: 16px 14px; font-weight: 500; color: var(--text-primary);">
                  💼 {{ $f->Puesto }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  @if ($activo == 1)
                    <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      🟢 Activo
                    </span>
                  @else
                    <span style="background: rgba(220,53,69,0.1); color: var(--accent-err); border: 1px solid rgba(220,53,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      🔴 Inactivo
                    </span>
                  @endif
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  <form action="/admin/empleados/{{ $f->EmpleadoId }}/toggle" method="POST" style="display: inline;">
                    @csrf
                    @if ($activo == 1)
                      <button type="submit" class="btn-primary" onclick="return confirm('¿Está seguro de inactivar al empleado {{ $f->Nombre }}?')" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: var(--accent-err); border: 1px solid var(--accent-err);">
                        🔴 Desactivar Empleado
                      </button>
                    @else
                      <button type="submit" class="btn-primary" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: #28a745; border: 1px solid #28a745;">
                        🟢 Activar Empleado
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
@endsection
