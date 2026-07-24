@extends('layouts.app')

@section('title', 'Categorías de Productos - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">📁</span>
    <div>
      <h1 class="section-title">
        Categorías del Menú
        <button class="help-btn-trigger" onclick="abrirAyuda('Categorías del Menú', 'Catálogo organizativo de alimentos, cafés y postres. Las categorías dividen el menú en el catálogo del cliente (ej: Bebidas Calientes, Panadería). Cada una tiene un icono distintivo que ayuda a los clientes a navegar de forma intuitiva.')">❓</button>
      </h1>
      <p class="section-desc">Estructura organizativa de alimentos y bebidas ofrecidos a los clientes.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Listado de Categorías</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Categorías de Menú" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre de la Categoría</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Icono</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="4" style="text-align: center; padding: 40px; opacity: 0.5;">No hay categorías configuradas en el sistema.</td>
            </tr>
          @else
            @foreach ($filas as $fila)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $fila->CategoriaId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                  {{ $fila->Nombre }}
                </td>
                <td style="padding: 16px 14px; text-align: center; font-size: 1.3rem;">
                  {{ $fila->Icono ?? '📁' }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    🟢 Activa
                  </span>
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
