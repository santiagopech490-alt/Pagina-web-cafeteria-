@extends('layouts.app')

@section('title', 'Registrar Producto - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">➕</span>
    <div>
      <h1 class="section-title">
        Registrar Producto
        <button class="help-btn-trigger" onclick="abrirAyuda('Registrar Producto', 'Módulo enfocado exclusivamente en dar de alta nuevos productos en el menú. Complete el código único de identificación, nombre del platillo/bebida, precio unitario, stock inicial y seleccione la categoría correspondiente.')">❓</button>
      </h1>
      <p class="section-desc">Dé de alta un nuevo platillo, bebida o postre en el menú de Café Parisien.</p>
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

  <!-- Formulario de Registro -->
  <div class="card" style="padding: 30px; margin-bottom: 30px; border-top: 4px solid var(--accent-navy);">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📝 Formulario de Alta de Producto
    </h2>

    <form action="/admin/registrar" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
      @csrf
      <div>
        <label style="display: block; font-size: 0.85rem; font-weight: bold; color: var(--text-secondary); margin-bottom: 6px;">Código del Producto</label>
        <input type="text" name="codigo" placeholder="Ej. P17" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary); text-transform: uppercase; font-weight: bold;">
      </div>

      <div>
        <label style="display: block; font-size: 0.85rem; font-weight: bold; color: var(--text-secondary); margin-bottom: 6px;">Nombre del Platillo / Bebida</label>
        <input type="text" name="nombre" placeholder="Ej. Croissant de Almendras" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary);">
      </div>

      <div>
        <label style="display: block; font-size: 0.85rem; font-weight: bold; color: var(--text-secondary); margin-bottom: 6px;">Precio Unitario ($)</label>
        <input type="number" step="0.01" min="0.01" name="precio" placeholder="Ej. 65.00" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary);">
      </div>

      <div>
        <label style="display: block; font-size: 0.85rem; font-weight: bold; color: var(--text-secondary); margin-bottom: 6px;">Existencia Inicial (Stock)</label>
        <input type="number" min="0" name="existencia" placeholder="Ej. 50" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary);">
      </div>

      <div>
        <label style="display: block; font-size: 0.85rem; font-weight: bold; color: var(--text-secondary); margin-bottom: 6px;">Categoría</label>
        <select name="categoriaId" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary);">
          <option value="">-- Sin categoría asignada --</option>
          @foreach ($categorias as $cat)
            <option value="{{ $cat->CategoriaId }}">{{ $cat->Nombre }}</option>
          @endforeach
        </select>
      </div>

      <div style="grid-column: 1 / -1; margin-top: 10px;">
        <button type="submit" class="btn-primary" style="padding: 12px 30px; font-size: 0.95rem; width: auto;">
          ➕ Registrar Producto Ahora
        </button>
      </div>
    </form>
  </div>

  <!-- Catálogo Registrado -->
  <div class="card" style="padding: 30px;">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
      📋 Últimos Productos Registrados
    </h3>
    <div class="table-wrap">
      <table aria-label="Últimos productos registrados" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 12px; text-align: left; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Código</th>
            <th style="padding: 12px; text-align: left; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre</th>
            <th style="padding: 12px; text-align: right; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Precio</th>
            <th style="padding: 12px; text-align: center; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark);">Stock Inicial</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($productos->take(8) as $p)
            <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.9rem;">
              <td style="padding: 12px; font-weight: bold; color: var(--accent-navy);">{{ $p->Codigo }}</td>
              <td style="padding: 12px; font-weight: 600;">{{ $p->Nombre }}</td>
              <td style="padding: 12px; text-align: right; font-weight: bold;">${{ number_format($p->Precio, 2) }}</td>
              <td style="padding: 12px; text-align: center;">{{ $p->Existencia }} unidades</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
