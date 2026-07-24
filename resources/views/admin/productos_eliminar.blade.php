@extends('layouts.app')

@section('title', 'Eliminar Producto - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🗑️</span>
    <div>
      <h1 class="section-title">
        Eliminar / Inhabilitar Producto
        <button class="help-btn-trigger" onclick="abrirAyuda('Eliminar Producto', 'Módulo enfocado exclusivamente en la eliminación segura de productos del catálogo. Si el producto cuenta con ventas previas, el sistema lo marcará como INHABILITADO para proteger el historial financiero.')">❓</button>
      </h1>
      <p class="section-desc">Retire productos del menú o inhabilite su disponibilidad de forma segura.</p>
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

  <!-- Tabla de Eliminación de Productos -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Catálogo de Productos y Baja de Artículos
    </h2>

    <div class="table-wrap">
      <table aria-label="Baja de Productos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Código</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre del Producto</th>
            <th style="padding: 14px; text-align: right; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Precio</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acción (Dar de Baja)</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($productos as $p)
            <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
              <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">{{ $p->Codigo }}</td>
              <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">{{ $p->Nombre }}</td>
              <td style="padding: 16px 14px; text-align: right; font-weight: bold;">${{ number_format($p->Precio, 2) }}</td>
              <td style="padding: 16px 14px; text-align: center;">
                @if ($p->Disponible == 1)
                  <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    🟢 Disponible
                  </span>
                @else
                  <span style="background: rgba(220,53,69,0.1); color: var(--accent-err); border: 1px solid rgba(220,53,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    🔴 Inhabilitado
                  </span>
                @endif
              </td>
              <td style="padding: 16px 14px; text-align: center;">
                <form action="/admin/eliminar/{{ $p->ProductoId }}" method="POST" style="display: inline;">
                  @csrf
                  <button type="submit" class="btn-primary" onclick="return confirm('¿Está completamente seguro de eliminar / dar de baja el producto {{ $p->Nombre }}?')" style="padding: 6px 14px; font-size: 0.78rem; width: auto; background: var(--accent-err); border: 1px solid var(--accent-err);">
                    🗑️ Eliminar Producto
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
