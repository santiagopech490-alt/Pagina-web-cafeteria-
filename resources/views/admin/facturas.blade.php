@extends('layouts.app')

@section('title', 'Facturación - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🧾</span>
    <div>
      <h1 class="section-title">
        Facturación
        <button class="help-btn-trigger" onclick="abrirAyuda('Facturación', 'Historial de comprobantes de venta emitidos automáticamente al finalizar cada compra. Muestra el folio fiscal autogenerado, el número del pedido de cocina enlazado y el importe total facturado.')">❓</button>
      </h1>
      <p class="section-desc">Consulte y descargue el historial de comprobantes de ventas emitidos.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Registro de Facturas</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Facturas Emitidas" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Factura ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Pedido ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Folio Fiscal</th>
            <th style="padding: 14px; text-align: right; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Total Facturado</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="4" style="text-align: center; padding: 40px; opacity: 0.5;">No hay facturas emitidas registradas.</td>
            </tr>
          @else
            @foreach ($filas as $f)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $f->FacturaId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 500;">
                  #{{ $f->PedidoId }}
                </td>
                <td style="padding: 16px 14px; font-family: monospace; font-weight: bold; color: var(--accent-gold-dark);">
                  {{ $f->Folio }}
                </td>
                <td style="padding: 16px 14px; text-align: right; font-weight: bold; color: var(--accent-navy);">
                  ${{ number_format($f->Total, 2) }}
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
