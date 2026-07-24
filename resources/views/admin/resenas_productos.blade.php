@extends('layouts.app')

@section('title', 'Reseñas de Productos - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">⭐</span>
    <div>
      <h1 class="section-title">
        Reseñas de Productos
        <button class="help-btn-trigger" onclick="abrirAyuda('Reseñas de Productos', 'Muestra la retroalimentación de los comensales. Aquí se despliegan las opiniones redactadas y las valoraciones de 1 a 5 estrellas que los clientes envían sobre el café y platillos, ayudando a moderar las opiniones y evaluar la satisfacción del cliente.')">❓</button>
      </h1>
      <p class="section-desc">Consulte y modere la retroalimentación, calificaciones y comentarios enviados por clientes.</p>
    </div>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <span>📋 Opiniones de Comensales</span>
      <a href="/admin/configuracion" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; background: var(--accent-gold-dark);">
        ⚙️ Configuración del Sistema
      </a>
    </h2>

    <div class="table-wrap">
      <table aria-label="Reseñas de Productos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Producto ID</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Calificación</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Comentario</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="4" style="text-align: center; padding: 40px; opacity: 0.5;">No hay comentarios registrados.</td>
            </tr>
          @else
            @foreach ($filas as $f)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $f->ResenaId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 500;">
                  #{{ $f->ProductoId }}
                </td>
                <td style="padding: 16px 14px; text-align: center; font-weight: bold; color: var(--accent-gold-dark);">
                  {{ str_repeat('⭐', $f->Calificacion) }} ({{ $f->Calificacion }}/5)
                </td>
                <td style="padding: 16px 14px; color: var(--text-primary); font-style: italic;">
                  "{{ $f->Comentario }}"
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
