@extends('layouts.app')

@section('title', 'Editar Nombre y Categoría - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">✏️</span>
    <div>
      <h1 class="section-title">
        Editar Nombre y Categoría
        <button class="help-btn-trigger" onclick="abrirAyuda('Editar Nombre', 'Módulo enfocado exclusivamente en la corrección o actualización de los nombres y categorías de los platillos. Modifique el texto del nombre y guarde los cambios inmediatamente.')">❓</button>
      </h1>
      <p class="section-desc">Actualice la denominación y clasificación de los platillos del menú.</p>
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

  <!-- Tabla de Edición de Nombres y Categorías -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">
      📋 Edición de Nombres y Categorización
    </h2>

    <div class="table-wrap">
      <table aria-label="Nombres de Productos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Código</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre Actual del Producto</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Categoría Asignada</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acción</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($productos as $p)
            <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
              <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">{{ $p->Codigo }}</td>
              
              <td colspan="3" style="padding: 12px 14px;">
                <form action="/admin/editar/{{ $p->ProductoId }}" method="POST" style="display: grid; grid-template-columns: 2fr 1.5fr auto; gap: 12px; align-items: center;">
                  @csrf
                  <div>
                    <input type="text" name="nombre" value="{{ $p->Nombre }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: 600; color: var(--accent-navy);">
                  </div>
                  <div>
                    <select name="categoriaId" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                      <option value="">-- Sin categoría --</option>
                      @foreach ($categorias as $cat)
                        <option value="{{ $cat->CategoriaId }}" {{ $p->CategoriaId == $cat->CategoriaId ? 'selected' : '' }}>
                          {{ $cat->Nombre }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div>
                    <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 0.8rem; width: auto; background: var(--accent-navy);">
                      ✏️ Guardar Nombre
                    </button>
                  </div>
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
