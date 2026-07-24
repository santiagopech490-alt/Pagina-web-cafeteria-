@extends('layouts.app')

@section('title', 'Bitácora de Auditoría - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">📜</span>
    <div>
      <h1 class="section-title">
        Logs de Auditoría
        <button class="help-btn-trigger" onclick="abrirAyuda('Logs de Auditoría', 'Bitácora histórica del sistema. Registra las acciones críticas y eventos del servidor con propósitos de seguridad (por ejemplo: cierres Z de caja, recepciones de compras o modificaciones administrativas), indicando quién lo hizo, qué cambió y la fecha exacta del suceso.')">❓</button>
      </h1>
      <p class="section-desc">Consulte el registro histórico de acciones críticas, modificaciones e inicio de sesión de usuarios.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Bitácora del Sistema</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Bitácora de Auditoría del Sistema" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Usuario ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acción / Evento</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Detalles</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Fecha y Hora</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="5" style="text-align: center; padding: 40px; opacity: 0.5;">No hay logs de auditoría registrados en MySQL.</td>
            </tr>
          @else
            @foreach ($filas as $f)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $f->LogId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 500;">
                  #{{ $f->UsuarioId ?? 'Sistema' }}
                </td>
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  {{ $f->Accion }}
                </td>
                <td style="padding: 16px 14px; color: var(--text-primary);">
                  {{ $f->Detalle }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  {{ date('d/m/Y H:i:s', strtotime($f->Fecha)) }}
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
