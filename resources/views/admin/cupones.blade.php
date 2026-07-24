@extends('layouts.app')

@section('title', 'Cupones de Descuento - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🎟️</span>
    <div>
      <h1 class="section-title">
        Cupones de Descuento
        <button class="help-btn-trigger" onclick="abrirAyuda('Cupones de Descuento', 'Administración de códigos de descuento promocionales. Los cupones aplican descuentos porcentuales o de monto fijo en el checkout del cliente. Puedes ver el límite de usos máximos y el número de veces que se ha canjeado cada código.')">❓</button>
      </h1>
      <p class="section-desc">Administre las campañas de promociones y descuentos aplicables en el carrito.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Listado de Cupones Activos</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Cupones de Descuento Activos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Código</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Descripción</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Descuento</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Usos</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Vence</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="5" style="text-align: center; padding: 40px; opacity: 0.5;">No hay cupones configurados en el sistema.</td>
            </tr>
          @else
            @foreach ($filas as $f)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  {{ $f->Codigo }}
                </td>
                <td style="padding: 16px 14px;">
                  {{ $f->Descripcion }}
                </td>
                <td style="padding: 16px 14px; text-align: center; font-weight: bold; color: var(--accent-gold-dark);">
                  @if ($f->TipoDescuento === 'PORCENTAJE')
                    {{ intval($f->ValorDescuento) }}%
                  @else
                    ${{ number_format($f->ValorDescuento, 2) }}
                  @endif
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  {{ $f->UsosActuales }} / {{ $f->UsosMaximos }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  @if ($f->ValidoHasta)
                    {{ date('d/m/Y', strtotime($f->ValidoHasta)) }}
                  @else
                    <span style="color: var(--text-muted); font-style: italic;">Sin límite</span>
                  @endif
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
