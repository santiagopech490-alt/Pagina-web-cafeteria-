@extends('layouts.app')

@section('title', 'Reservar Mesa')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🪑</span>
    <div>
      <h1 class="section-title">
        Reservar Mesa
        <button class="help-btn-trigger" onclick="abrirAyuda('Reservar Mesa', 'Módulo de reservación de mesas con sistema anti-colisiones. Selecciona una mesa de la lista y la fecha y hora de tu visita. El sistema validará automáticamente que no existan reservaciones activas para esa mesa en una ventana de 2 horas (antes o después) para garantizar tu lugar sin contratiempos.')">❓</button>
      </h1>
      <p class="section-desc">Planifique su visita y asegure su lugar en la mejor zona de nuestra cafetería.</p>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; align-items: start; flex-wrap: wrap;">
    
    <!-- Formulario de Reserva -->
    <div class="card" style="padding: 25px;">
      <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">⚜️ Nueva Reservación</h3>
      
      @if (session('msg'))
        <div class="alert-box success" style="margin-bottom: 15px; padding: 14px; border-radius: var(--radius-sm); background: rgba(76,175,80,0.1); border: 1px solid #4CAF50; color: #4CAF50; font-size: 0.88rem; font-weight: bold;">
          <div>{{ session('msg') }}</div>
          @if (session('nueva_reservacion_id'))
            <a href="/reservaciones/{{ session('nueva_reservacion_id') }}/descargar-boleto" target="_blank" class="btn-primary" style="display: inline-block; margin-top: 10px; padding: 8px 16px; text-decoration: none; width: auto; background: var(--accent-navy); color: #fff;">
              📥 Descargar Boleto de Reservación (PDF)
            </a>
          @endif
        </div>
      @endif

      @if ($errors->any())
        <div class="alert-box error" style="margin-bottom: 15px; padding: 10px; border-radius: var(--radius-sm); background: rgba(220,53,69,0.1); border: 1px solid var(--accent-err); color: var(--accent-err); font-size: 0.85rem; font-weight: bold;">
          ⚠️ {{ $errors->first() }}
        </div>
      @endif

      <form action="/Reservar" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        @csrf
        <div>
          <label style="display: block; font-weight: bold; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 6px;">Mesa a Reservar:</label>
          <select name="mesaId" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary); font-weight: bold;">
            @foreach ($mesas as $mesa)
              <option value="{{ $mesa->MesaId }}">
                {{ $mesa->IconoZona ?? '🪑' }} {{ $mesa->NumeroMesa }} — {{ $mesa->NombreZona ?? 'Salón Principal' }} (Capacidad: {{ $mesa->Capacidad ?? 4 }} pers.)
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-weight: bold; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 6px;">Fecha y Hora:</label>
          <input type="datetime-local" name="fechaHora" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary);" />
        </div>
        <button type="submit" class="btn-primary" style="padding: 12px; margin-top: 10px;">
          📅 Reservar Ahora
        </button>
      </form>
    </div>

    <!-- Lista de Reservaciones del usuario -->
    <div class="card" style="padding: 25px;">
      <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">📋 Mis Reservaciones</h3>
      <div class="table-wrap">
        <table aria-label="Tabla de mis reservaciones" style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: var(--bg-secondary);">
              <th style="padding: 12px; text-align: left; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Mesa</th>
              <th style="padding: 12px; text-align: center; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Fecha y Hora</th>
              <th style="padding: 12px; text-align: center; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estado</th>
              <th style="padding: 12px; text-align: center; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Boleto</th>
            </tr>
          </thead>
          <tbody>
            @if ($reservaciones->isEmpty())
              <tr>
                <td colspan="4" style="text-align: center; padding: 30px; opacity: 0.5;">No tiene reservaciones programadas.</td>
              </tr>
            @else
              @foreach ($reservaciones as $r)
                <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.88rem;">
                  <td style="padding: 12px; font-weight: 700; color: var(--accent-navy);">Mesa {{ $r->mesa->NumeroMesa ?? $r->MesaId }}</td>
                  <td style="padding: 12px; text-align: center;">{{ date('d/m/Y H:i', strtotime($r->FechaHora)) }}</td>
                  <td style="padding: 12px; text-align: center;">
                    <span style="background: #4CAF5015; color: #4CAF50; padding: 3px 10px; border-radius: 99px; font-size: 0.78rem; font-weight: bold; border: 1px solid #4CAF5030;">
                      CONFIRMADA
                    </span>
                  </td>
                  <td style="padding: 12px; text-align: center;">
                    <a href="/reservaciones/{{ $r->ReservacionId }}/descargar-boleto" target="_blank" class="btn-primary" style="padding: 5px 10px; font-size: 0.75rem; text-decoration: none; width: auto; background: var(--accent-navy);">
                      📥 Boleto PDF
                    </a>
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
