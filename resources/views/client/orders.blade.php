@extends('layouts.app')

@section('title', 'Mis Pedidos - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">☕</span>
    <div>
      <h1 class="section-title">
        Mis Pedidos
        <button class="help-btn-trigger" onclick="abrirAyuda('Mis Pedidos', 'Listado de tus compras y comandas en el sistema. Puedes comprobar en tiempo real el estado de tu pedido: si aún se está preparando en la barra (\'En Cocina\') o si ya está listo para recoger (\'Listo\').')">❓</button>
      </h1>
      <p class="section-desc">Consulte el estado de preparación y despacho de sus pedidos en tiempo real.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <div class="table-wrap">
      <table aria-label="Historial de Pedidos del Cliente" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Folio</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estado</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Mesa / Entrega</th>
            <th style="padding: 14px; text-align: right; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Total</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Comprobante</th>
          </tr>
        </thead>
        <tbody>
          @if ($pedidos->isEmpty())
            <tr>
              <td colspan="5" style="text-align: center; padding: 40px; opacity: 0.6; font-size: 0.95rem;">
                No ha realizado ningún pedido aún.
              </td>
            </tr>
          @else
            @foreach ($pedidos as $p)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $p->Folio }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  @if ($p->EstadoId == 1)
                    <span style="background: rgba(255,193,7,0.15); color: #b7791f; border: 1px solid rgba(255,193,7,0.3); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      En Cocina
                    </span>
                  @elseif ($p->EstadoId == 2)
                    <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      Listo
                    </span>
                  @else
                    <span style="background: var(--bg-secondary); color: var(--text-muted); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      Entregado
                    </span>
                  @endif
                </td>
                <td style="padding: 16px 14px; color: var(--text-primary);">
                  @if ($p->MetodoEntregaId == 1)
                    <span>📍 Para Llevar (Mesa #{{ $p->NumeroMesa ?? 'N/A' }})</span>
                  @else
                    <span>🍽️ Consumo en Mesa #{{ $p->NumeroMesa ?? 'N/A' }}</span>
                  @endif
                  @if ($p->Notas)
                    <div style="font-size: 0.78rem; color: var(--text-muted); font-style: italic; margin-top: 4px;">
                      Nota: "{{ $p->Notas }}"
                    </div>
                  @endif
                </td>
                <td style="padding: 16px 14px; text-align: right; font-weight: bold; color: var(--accent-navy);">
                  ${{ number_format($p->Total, 2) }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  <a href="/ticket/{{ $p->Folio }}/descargar" target="_blank" class="btn-primary" style="padding: 6px 12px; font-size: 0.78rem; text-decoration: none; width: auto; background: var(--accent-navy);">
                    📥 Ticket PDF
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
@endsection
