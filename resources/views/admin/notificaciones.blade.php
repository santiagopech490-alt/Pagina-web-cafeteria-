@extends('layouts.app')

@section('title', 'Centro de Notificaciones - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🔔</span>
    <div>
      <h1 class="section-title">
        Centro de Notificaciones
        <button class="help-btn-trigger" onclick="abrirAyuda('Centro de Notificaciones', 'Bitácora centralizada de alertas y eventos del sistema. Muestra avisos de recepción de mercancía, cambios de estatus de órdenes, cierres de caja y eventos de auditoría.')">❓</button>
      </h1>
      <p class="section-desc">Bitácora de alertas, notificaciones y avisos automáticos generados por la plataforma.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Historial de Notificaciones Emitidas
    </h2>

    <div class="table-wrap">
      <table aria-label="Historial de Notificaciones" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Título / Asunto</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Mensaje / Contenido</th>
          </tr>
        </thead>
        <tbody>
          @if ($notificaciones->isEmpty())
            <tr>
              <td colspan="3" style="text-align: center; padding: 40px; opacity: 0.5;">No hay notificaciones registradas en el sistema.</td>
            </tr>
          @else
            @foreach ($notificaciones as $n)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $n->NotificacionId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                  🔔 {{ $n->Titulo }}
                </td>
                <td style="padding: 16px 14px; color: var(--text-primary);">
                  {{ $n->Cuerpo }}
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
