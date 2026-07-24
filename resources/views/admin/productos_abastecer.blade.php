@extends('layouts.app')

@section('title', 'Abastecer Inventario - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🔄</span>
    <div>
      <h1 class="section-title">
        Abastecer Inventario
        <button class="help-btn-trigger" onclick="abrirAyuda('Abastecer Inventario', 'Módulo enfocado exclusivamente en la recarga de existencias de productos. Seleccione cualquier platillo o bebida de la lista e ingrese la cantidad a sumar al stock actual.')">❓</button>
      </h1>
      <p class="section-desc">Gestione el inventario y reabastezca las existencias de insumos y platillos rápidamente.</p>
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

  <!-- Tabla de Inventario y Reabastecimiento -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Inventario Actual y Recarga de Stock
    </h2>

    <div class="table-wrap">
      <table aria-label="Inventario de Productos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Código</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Producto</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Stock Actual</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estado del Stock</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Añadir Unidades (+ Stock)</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($productos as $p)
            <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
              <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">{{ $p->Codigo }}</td>
              <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">{{ $p->Nombre }}</td>
              <td style="padding: 16px 14px; text-align: center; font-weight: bold; font-size: 1.05rem;">
                {{ $p->Existencia }} un.
              </td>
              <td style="padding: 16px 14px; text-align: center;">
                @if ($p->Existencia <= 15)
                  <span style="background: rgba(220,53,69,0.1); color: var(--accent-err); border: 1px solid rgba(220,53,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    ⚠️ Stock Crítico
                  </span>
                @elseif ($p->Existencia <= 35)
                  <span style="background: rgba(255,193,7,0.15); color: #b7791f; border: 1px solid rgba(255,193,7,0.3); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    ⚡ Stock Medio
                  </span>
                @else
                  <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    🟢 Stock Óptimo
                  </span>
                @endif
              </td>
              <td style="padding: 16px 14px; text-align: center;">
                <form action="/admin/reabastecer" method="POST" style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                  @csrf
                  <input type="hidden" name="productoId" value="{{ $p->ProductoId }}">
                  <input type="number" name="cantidad" min="1" placeholder="+ Cant" required style="width: 90px; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold; text-align: center;">
                  <button type="submit" class="btn-primary" style="padding: 6px 14px; font-size: 0.8rem; width: auto; background: #28a745; border: 1px solid #28a745;">
                    🔄 Abastecer
                  </button>
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
