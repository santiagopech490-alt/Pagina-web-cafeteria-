@extends('layouts.app')

@section('title', 'Órdenes de Compra y Proveedores')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">📦</span>
    <div>
      <h1 class="section-title">
        Órdenes de Compra (Proveedores)
        <button class="help-btn-trigger" onclick="abrirAyuda('Órdenes de Compra', 'Módulo de suministros de materias primas. Muestra las órdenes de compra emitidas a tus proveedores. Si los insumos ya llegaron físicamente a la cafetería, presiona el botón \'📥 Recibir Mercancía\' para cargar automáticamente la cantidad pedida al stock de inventario y marcar la orden como Recibida.')">❓</button>
      </h1>
      <p class="section-desc">Monitoree el estatus de las órdenes y cargue la materia prima e insumos recibidos directamente al stock del inventario.</p>
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

  <!-- Botones de Acción -->
  <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
    <button class="btn-primary" onclick="toggleForm('form-nueva-oc')" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
      ➕ Emitir Nueva Orden de Compra
    </button>
    <a href="/admin/proveedores" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem; width: auto; background: var(--accent-navy);">
      🏢 Directorio de Proveedores
    </a>
  </div>

  <!-- Formulario Nueva Orden de Compra -->
  <div id="form-nueva-oc" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-navy);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">📦 Emitir Orden de Compra a Proveedor</h3>
    <form action="/admin/ordenes-compra/crear" method="POST">
      @csrf
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Proveedor Seleccionado</label>
          <select name="proveedorId" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
            @foreach ($proveedores as $prov)
              <option value="{{ $prov->ProveedorId }}">🏢 {{ $prov->RazonSocial }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Insumo / Producto a Solicitar</label>
          <select name="productoId" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
            @foreach ($productos as $prod)
              <option value="{{ $prod->ProductoId }}">{{ $prod->Codigo }} — {{ $prod->Nombre }} (Stock actual: {{ $prod->Existencia }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Cantidad a Solicitar (Unidades)</label>
          <input type="number" name="cantidad" min="1" value="20" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: bold;">
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Generar Orden de Compra</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nueva-oc')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">📋 Bitácora de Suministros</h2>

    <div class="table-wrap">
      <table aria-label="Órdenes de Compra de Proveedores" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Folio / ID</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Proveedor</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Detalle de Insumos</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acción</th>
          </tr>
        </thead>
        <tbody>
          @if ($filas->isEmpty())
            <tr>
              <td colspan="5" style="text-align: center; padding: 40px; opacity: 0.5;">No hay órdenes de compra configuradas en el sistema.</td>
            </tr>
          @else
            @foreach ($filas as $f)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #{{ $f->FolioOrden }}
                  <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal; margin-top: 3px;">
                    Registrado: {{ date('d/m/Y H:i', strtotime($f->FechaCreacion ?? now())) }}
                  </div>
                </td>
                <td style="padding: 16px 14px; font-weight: 600;">
                  🏢 {{ $f->RazonSocial }}
                </td>
                <td style="padding: 16px 14px;">
                  <div style="display: flex; flex-direction: column; gap: 4px;">
                    @foreach ($f->detalles as $d)
                      <span style="font-size: 0.85rem; color: var(--text-primary);">
                        🔹 <strong>{{ intval($d->CantidadPedida) }} uds.</strong> de {{ $d->Nombre }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  @if (($f->Estado ?? 'PENDIENTE') === 'RECIBIDA')
                    <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      🟢 Recibida
                    </span>
                  @else
                    <span style="background: rgba(255,193,7,0.15); color: #b7791f; border: 1px solid rgba(255,193,7,0.3); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                      🟡 Pendiente
                    </span>
                  @endif
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  @if (($f->Estado ?? 'PENDIENTE') === 'PENDIENTE')
                    <form action="/admin/ordenes-compra/{{ $f->OrdenId }}/recibir" method="POST" onsubmit="return confirm('¿Confirma la recepción física de esta mercancía? Se incrementará el stock del inventario automáticamente.')">
                      @csrf
                      <button type="submit" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; width: auto; font-weight: bold; background: #28a745;">
                        📥 Recibir Mercancía
                      </button>
                    </form>
                  @else
                    <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">
                      Cargada al stock
                    </span>
                  @endif
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
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
