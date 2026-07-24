@extends('layouts.app')

@section('title', 'Modificadores de Productos - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🎨</span>
    <div>
      <h1 class="section-title">
        Modificadores de Productos
        <button class="help-btn-trigger" onclick="abrirAyuda('Modificadores de Productos', 'Módulo de personalización de bebidas y alimentos. Configure los grupos de opciones (tipos de leche, esencias, niveles de dulzor, toppings) para que los clientes puedan personalizar su orden al momento de la compra.')">❓</button>
      </h1>
      <p class="section-desc">Administre los grupos de opciones y personalizaciones disponibles para las bebidas y alimentos del menú.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  <!-- Botón Agregar Modificador -->
  <div style="margin-bottom: 25px;">
    <button class="btn-primary" onclick="toggleForm('form-nuevo-mod')" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
      ➕ Registrar Grupo de Modificador
    </button>
  </div>

  <!-- Formulario Nuevo Grupo -->
  <div id="form-nuevo-mod" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-gold);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">🎨 Crear Nuevo Grupo de Modificador</h3>
    <form action="/admin/modificadores/crear" method="POST">
      @csrf
      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Nombre del Grupo de Modificador</label>
        <input type="text" name="nombre" class="search-input" placeholder="Ej. Jarabes y Esencias Gourmet" required style="width: 100%; border: 1px solid var(--border);">
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Guardar Grupo</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nuevo-mod')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <!-- Cards de Grupos de Modificadores -->
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
    @foreach ($tipos as $t)
      <div class="card" style="padding: 24px; border: 1px solid var(--border);">
        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.35rem; color: var(--accent-navy); margin-top: 0; margin-bottom: 15px; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
          ☕ {{ $t->Nombre }}
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 8px;">
          @if ($t->opciones->isEmpty())
            <span style="font-size: 0.85rem; color: var(--text-muted); font-style: italic;">Sin opciones asociadas aún.</span>
          @else
            @foreach ($t->opciones as $op)
              <div style="display: flex; justify-content: space-between; font-size: 0.88rem; background: var(--bg-secondary); padding: 8px 12px; border-radius: var(--radius-sm);">
                <span>🔹 {{ $op->Nombre }}</span>
                @if (isset($op->PrecioExtra) && $op->PrecioExtra > 0)
                  <span style="font-weight: bold; color: var(--accent-gold-dark);">+${{ number_format($op->PrecioExtra, 2) }}</span>
                @endif
              </div>
            @endforeach
          @endif
        </div>
      </div>
    @endforeach
  </div>
</div>

<script>
  function toggleForm(id) {
    const el = document.getElementById(id);
    if (el.style.display === 'none') {
      el.style.display = 'block';
    } else {
      el.style.display = 'none';
    }
  }
</script>
@endsection
