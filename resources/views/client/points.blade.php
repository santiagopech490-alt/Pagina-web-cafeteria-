@extends('layouts.app')

@section('title', 'Mis Puntos Club - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🏆</span>
    <div>
      <h1 class="section-title">
        Mis Puntos Club
        <button class="help-btn-trigger" onclick="abrirAyuda('Mis Puntos Club', 'Consulta tus puntos acumulados en el Club de Fidelidad. Por cada compra que realizas acumulas el 10% en puntos canjeables (1 punto = $1.00 MXN). Puedes utilizarlos para pagar de forma total o parcial tus siguientes pedidos durante el checkout.')">❓</button>
      </h1>
      <p class="section-desc">Consulte su saldo acumulado y el historial de transacciones de fidelidad.</p>
    </div>
  </div>

  <div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
      <div class="stat-icon-wrap orange">🌟</div>
      <div>
        <div class="stat-value">{{ $puntos ? $puntos->Puntos : 0 }}</div>
        <div class="stat-label">Puntos Disponibles</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap purple">🎫</div>
      <div>
        <div class="stat-value">${{ number_format($puntos ? $puntos->Puntos * 1.0 : 0, 2) }}</div>
        <div class="stat-label">Equivalencia para canje</div>
      </div>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">📜 Historial de Movimientos</h3>
    <div class="table-wrap">
      <table aria-label="Tabla de transacciones de puntos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Tipo</th>
            <th style="padding: 14px; text-align: right; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Puntos</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Fecha</th>
          </tr>
        </thead>
        <tbody>
          @if ($transacciones->isEmpty())
            <tr>
              <td colspan="3" style="text-align: center; padding: 40px; opacity: 0.5;">No tiene movimientos de puntos registrados.</td>
            </tr>
          @else
            @foreach ($transacciones as $t)
              @php
                $esGanado = $t->Puntos > 0;
                $color = $esGanado ? '#28a745' : '#dc3545';
              @endphp
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card);">
                <td style="padding: 14px; font-weight: bold; color: {{ $color }};">{{ $t->TipoMovimiento }}</td>
                <td style="padding: 14px; text-align: right; font-weight: 700; color: {{ $color }};">{{ $t->Puntos > 0 ? '+' : '' }}{{ $t->Puntos }} pts</td>
                <td style="padding: 14px; text-align: center;">{{ date('d/m/Y H:i', strtotime($t->FechaMovimiento)) }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
