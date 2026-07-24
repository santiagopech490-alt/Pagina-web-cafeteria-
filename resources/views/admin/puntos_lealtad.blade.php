@extends('layouts.app')

@section('title', 'Puntos Club de Clientes - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🏆</span>
    <div>
      <h1 class="section-title">
        Puntos Club de Clientes
        <button class="help-btn-trigger" onclick="abrirAyuda('Puntos Club de Clientes', 'Muestra el saldo disponible de puntos acumulados por cada usuario en el Club de Fidelidad. Los clientes ganan puntos equivalentes al 10% del total de sus compras. Estos puntos actúan como saldo de efectivo para canjes directos de platillos o bebidas en sus próximas compras.')">❓</button>
      </h1>
      <p class="section-desc">Consulte los saldos de acumulación de puntos de fidelidad para canje de productos.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Saldos de Fidelidad</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Saldos de Puntos de Fidelidad" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Puntos ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Usuario ID</th>
            <th style="padding: 14px; text-align: right; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Puntos Disponibles</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="3" style="text-align: center; padding: 40px; opacity: 0.5;">No hay registros de puntos acumulados.</td>
            </tr>
          @else
            @foreach ($filas as $f)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $f->PuntosId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 500;">
                  #{{ $f->UsuarioId }}
                </td>
                <td style="padding: 16px 14px; text-align: right; font-weight: bold; color: var(--accent-gold-dark);">
                  🌟 {{ $f->Puntos }} pts
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
