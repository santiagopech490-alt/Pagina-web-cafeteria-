@extends('layouts.app')

@section('title', 'Métodos de Pago - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">💳</span>
    <div>
      <h1 class="section-title">
        Métodos de Pago Autorizados
        <button class="help-btn-trigger" onclick="abrirAyuda('Métodos de Pago', 'Módulo de configuración de pasarelas y medios de cobro. Habilite o deshabilite los métodos de pago aceptados en caja (Efectivo, Tarjetas bancarias, Puntos de lealtad, SPEI).')">❓</button>
      </h1>
      <p class="section-desc">Administre los medios de cobro disponibles para los clientes en la terminal de punto de venta y tienda en línea.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Medios de Pago Registrados en el Sistema
    </h2>

    <div class="table-wrap">
      <table aria-label="Métodos de Pago Autorizados" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Clave Interna</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Etiqueta / Nombre Visible</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus Actual</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acciones (Activar/Desactivar)</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($metodos as $m)
            @php $activo = $m->Activo ?? 1; @endphp
            <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
              <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                <code>{{ $m->Clave }}</code>
              </td>
              <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                💳 {{ $m->Etiqueta }}
              </td>
              <td style="padding: 16px 14px; text-align: center;">
                @if ($activo == 1)
                  <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    🟢 Habilitado
                  </span>
                @else
                  <span style="background: rgba(220,53,69,0.1); color: var(--accent-err); border: 1px solid rgba(220,53,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    🔴 Deshabilitado
                  </span>
                @endif
              </td>
              <td style="padding: 16px 14px; text-align: center;">
                <form action="/admin/metodos-pago/{{ $m->MetodoPagoId }}/toggle" method="POST" style="display: inline;">
                  @csrf
                  @if ($activo == 1)
                    <button type="submit" class="btn-primary" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: var(--accent-err); border: 1px solid var(--accent-err);">
                      🔴 Desactivar
                    </button>
                  @else
                    <button type="submit" class="btn-primary" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: #28a745; border: 1px solid #28a745;">
                      🟢 Activar
                    </button>
                  @endif
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
