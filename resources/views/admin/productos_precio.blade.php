@extends('layouts.app')

@section('title', 'Ajustar Precios - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🏷️</span>
    <div>
      <h1 class="section-title">
        Ajustar Precios de Productos
        <button class="help-btn-trigger" onclick="abrirAyuda('Ajustar Precios', 'Módulo enfocado exclusivamente en la modificación de precios de venta. Modifique el costo unitario de cualquier platillo o bebida e ingrese el nuevo precio para actualizar la carta en tiempo real.')">❓</button>
      </h1>
      <p class="section-desc">Actualice la carta de precios de la cafetería de forma directa e individual.</p>
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

  <!-- Tabla de Ajuste de Precios -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Modificación de Tarifas y Menú
    </h2>

    <div class="table-wrap">
      <table aria-label="Tarifario de Productos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Código</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre del Producto</th>
            <th style="padding: 14px; text-align: right; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Precio Actual ($)</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Establecer Nuevo Precio</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($productos as $p)
            <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
              <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">{{ $p->Codigo }}</td>
              <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">{{ $p->Nombre }}</td>
              <td style="padding: 16px 14px; text-align: right; font-weight: bold; font-size: 1.05rem; color: var(--accent-gold-dark);">
                ${{ number_format($p->Precio, 2) }}
              </td>
              <td style="padding: 16px 14px; text-align: center;">
                <form action="/admin/precio/{{ $p->ProductoId }}" method="POST" style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                  @csrf
                  <span style="font-weight: bold; color: var(--text-muted);">$</span>
                  <input type="number" step="0.01" min="0.01" name="precio" value="{{ $p->Precio }}" required style="width: 110px; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold; text-align: right;">
                  <button type="submit" class="btn-primary" style="padding: 6px 14px; font-size: 0.8rem; width: auto; background: var(--accent-navy);">
                    🏷️ Actualizar Precio
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
