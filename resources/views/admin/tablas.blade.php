@extends('layouts.app')

@section('title', 'Gestor de Base de Datos')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🗄️</span>
    <div>
      <h1 class="section-title">Gestor de Tablas de Base de Datos</h1>
      <p class="section-desc">Administre de forma directa y din&aacute;mica todas las tablas de MySQL en tiempo real.</p>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 280px 1fr; gap: 30px; align-items: start; flex-wrap: wrap;">
    
    <!-- MENU LATERAL DE SELECCION DE TABLAS -->
    <div class="card" style="padding: 20px; max-height: 80vh; overflow-y: auto;">
      <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; color: var(--accent-navy); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
        📁 Tablas del Sistema
      </h3>
      <div style="display: flex; flex-direction: column; gap: 6px;">
        @foreach ($tablas->sortBy('etiqueta') as $tbl)
          <button class="tabla-menu-item {{ $tablaSeleccionada == $tbl->nombre ? 'active' : '' }}" onclick="seleccionarTabla('{{ $tbl->nombre }}')" id="btn-tabla-{{ $tbl->nombre }}" style="text-align: left; background: none; border: 1px solid transparent; border-radius: var(--radius-sm); padding: 10px 14px; font-weight: 600; font-size: 0.9rem; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all 0.2s;">
            <span style="font-size: 1.1rem; opacity: 0.7;">📁</span>
            <span>{{ $tbl->etiqueta }}</span>
          </button>
        @endforeach
      </div>
    </div>

    <!-- VISOR Y CRUD DINAMICO -->
    <div class="card" style="padding: 30px; min-height: 400px; display: flex; flex-direction: column;">
      <div id="visor-header" style="{{ $tablaSeleccionada ? 'display: flex;' : 'display: none;' }} justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid var(--accent-gold); padding-bottom: 12px; flex-wrap: wrap; gap: 15px;">
        <div>
          <h2 id="titulo-tabla" style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--accent-navy); margin-bottom: 4px;">
            {{ $tablaSeleccionada ? 'Tabla: ' . strtoupper($tablaSeleccionada) : 'Selecciona una tabla' }}
          </h2>
          <span id="badge-contador" style="font-size: 0.78rem; font-weight: bold; background: var(--bg-secondary); border: 1px solid var(--border); padding: 3px 10px; border-radius: 99px; color: var(--accent-gold-dark);">
            {{ count($filas) }} registros
          </span>
        </div>
        <button class="btn-primary" onclick="abrirModalNuevo()" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
          ➕ Nuevo Registro
        </button>
      </div>

      <!-- Área de Selección Inicial -->
      <div id="visor-inicial" style="{{ $tablaSeleccionada ? 'display: none;' : 'display: flex;' }} text-align: center; padding: 100px 20px; flex-grow: 1; flex-direction: column; align-items: center; justify-content: center; opacity: 0.6;">
        <span style="font-size: 5rem; display: block; margin-bottom: 20px;">🗄️</span>
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--accent-navy); margin-bottom: 8px;">Consola de Administraci&oacute;n de Datos</h2>
        <p style="color: var(--text-secondary); max-width: 450px; font-size: 0.95rem;">Seleccione una tabla del men&uacute; de la izquierda para examinar sus registros, a&ntilde;adir filas o modificarlas.</p>
      </div>

      <!-- Cargador -->
      <div id="visor-cargador" style="display: none; text-align: center; padding: 80px 20px; flex-grow: 1;">
        <span style="font-size: 2.5rem; display: block; animation: spin 1s infinite linear; margin-bottom: 15px;">🔄</span>
        <span style="font-weight: bold; color: var(--text-muted);">Consultando MySQL...</span>
      </div>

      <!-- Tabla de Datos -->
      <div id="visor-tabla-wrap" class="table-wrap" style="{{ $tablaSeleccionada ? 'display: block;' : 'display: none;' }} flex-grow: 1; border: 1px solid var(--border); border-radius: var(--radius-lg); overflow-x: auto;">
        <table aria-label="Visor din&aacute;mico de registros" id="tabla-dinamica" style="width: 100%; border-collapse: collapse;">
          <thead id="tabla-dinamica-head">
            @if ($tablaSeleccionada)
              <tr style="background: var(--bg-secondary);">
                @foreach ($columnas as $col)
                  <th style="padding: 12px 15px; text-align: left; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark); font-weight: 700;">{{ $col }}</th>
                @endforeach
                <th style="padding: 12px 15px; text-align: center; font-size: 0.82rem; text-transform: uppercase; color: var(--accent-gold-dark); font-weight: 700;">Acciones</th>
              </tr>
            @endif
          </thead>
          <tbody id="tabla-dinamica-body">
            @if ($tablaSeleccionada)
              @if ($filas->isEmpty())
                <tr><td colspan="{{ count($columnas) + 1 }}" style="text-align:center; padding:30px; opacity:0.5;">No hay registros en esta tabla.</td></tr>
              @else
                @foreach ($filas as $idx => $fila)
                  <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.88rem;">
                    @foreach ($columnas as $col)
                      @php
                        $val = $fila->$col;
                        if ($val === null) {
                          $display = '<span style="opacity:0.3;">NULL</span>';
                        } elseif (is_bool($val)) {
                          $display = $val ? '🟢 Si' : '🔴 No';
                        } elseif (str_contains(strtolower($col), 'fecha') && $val) {
                          $display = date('d/m/Y H:i', strtotime($val));
                        } else {
                          $display = htmlspecialchars($val);
                        }
                      @endphp
                      <td style="padding: 12px 15px; color: var(--text-primary); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{!! $display !!}</td>
                    @endforeach
                    @php
                      $pkCol = $columnas[0];
                      $pkVal = $fila->$pkCol;
                    @endphp
                    <td style="padding: 12px 15px; text-align: center; white-space: nowrap;">
                      <button class="qty-btn" onclick="abrirModalEditar({{ $idx }})" style="width: auto; padding: 6px 10px; font-size: 0.85rem; margin-right: 6px;" title="Editar">✏️</button>
                      <button class="qty-btn remove" onclick="eliminarFila('{{ $pkCol }}', '{{ $pkVal }}')" style="width: auto; padding: 6px 10px; font-size: 0.85rem;" title="Eliminar">🗑️</button>
                    </td>
                  </tr>
                @endforeach
              @endif
            @endif
          </tbody>
        </table>
      </div>

    </div>

  </div>
</div>

<!-- Modal CRUD Dinámico (Formulario dinámico) -->
<div id="crudModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
  <div class="card" style="width: 90%; max-width: 550px; max-height: 85vh; overflow-y: auto; padding: 30px; position: relative; animation: slideIn 0.25s ease-out;">
    <button onclick="cerrarModalCrud()" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
    
    <h2 id="modal-titulo" style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px solid var(--accent-gold); padding-bottom: 6px;">🗄️ Registro</h2>
    
    <form id="modal-form" onsubmit="guardarRegistro(event)" style="display: flex; flex-direction: column; gap: 15px;">
      <!-- Contenedor de inputs inyectados por JS -->
      <div id="modal-form-inputs" style="display: flex; flex-direction: column; gap: 15px;"></div>
      
      <button type="submit" class="btn-primary" style="margin-top: 15px; padding: 12px; font-size: 0.95rem;">
        ⚜️ Guardar Registro
      </button>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<style>
  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
  @keyframes slideIn {
    from { transform: translateY(20deg); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }

  .tabla-menu-item:hover {
    background: var(--bg-secondary) !important;
    color: var(--accent-navy) !important;
  }
  .tabla-menu-item.active {
    background: var(--accent-navy) !important;
    color: #fff !important;
    border-color: var(--accent-gold) !important;
  }
</style>

<script>
  let tablaActiva = '{{ $tablaSeleccionada }}';
  let metadataColumnas = @json($columnas);
  let registrosLocales = @json($filas);
  let filaEdicion = null; // null = nuevo, de lo contrario guarda la fila

  function seleccionarTabla(nombreTabla) {
    window.location.href = `/admin/tablas?tabla=${nombreTabla}`;
  }

  function abrirModalNuevo() {
    filaEdicion = null;
    document.getElementById('modal-titulo').innerText = `➕ A&ntilde;adir a ${tablaActiva.toUpperCase()}`;
    generarCamposFormulario({});
    document.getElementById('crudModal').style.display = 'flex';
  }

  function abrirModalEditar(idx) {
    filaEdicion = registrosLocales[idx];
    document.getElementById('modal-titulo').innerText = `✏️ Editar en ${tablaActiva.toUpperCase()}`;
    generarCamposFormulario(filaEdicion);
    document.getElementById('crudModal').style.display = 'flex';
  }

  function generarCamposFormulario(valoresDef) {
    const container = document.getElementById('modal-form-inputs');
    container.innerHTML = '';

    metadataColumnas.forEach((col, idx) => {
      const esPk = (idx === 0);
      const valor = valoresDef[col] !== undefined && valoresDef[col] !== null ? valoresDef[col] : '';

      const inputWrap = document.createElement('div');
      inputWrap.style.display = 'flex';
      inputWrap.style.flexDirection = 'column';
      inputWrap.style.gap = '5px';

      const label = document.createElement('label');
      label.innerText = col + (esPk ? ' (Clave Primaria)' : '');
      label.style.fontWeight = 'bold';
      label.style.fontSize = '0.85rem';
      label.style.color = 'var(--text-secondary)';
      inputWrap.appendChild(label);

      let input;
      if (esPk) {
        input = document.createElement('input');
        input.type = 'text';
        input.value = valor;
        input.disabled = true;
        input.style.opacity = '0.6';
      } else if (typeof valor === 'boolean' || col.toLowerCase().startsWith('activo') || col.toLowerCase().startsWith('disponible') || col.toLowerCase().startsWith('leida')) {
        input = document.createElement('input');
        input.type = 'checkbox';
        input.checked = !!valor;
        input.style.alignSelf = 'start';
        input.style.width = '20px';
        input.style.height = '20px';
      } else if (col.toLowerCase().includes('fecha')) {
        input = document.createElement('input');
        input.type = 'datetime-local';
        if (valor) {
          try { input.value = new Date(valor).toISOString().substring(0, 16); } catch { input.value = ''; }
        } else {
          input.value = '';
        }
      } else {
        input = document.createElement('input');
        input.type = (typeof valor === 'number' || col.toLowerCase().includes('id') || col.toLowerCase().includes('precio') || col.toLowerCase().includes('total') || col.toLowerCase().includes('puntos') || col.toLowerCase().includes('existencia')) ? 'number' : 'text';
        if (input.type === 'number' && (col.toLowerCase().includes('precio') || col.toLowerCase().includes('total') || col.toLowerCase().includes('salario'))) {
          input.step = '0.01';
        }
        input.value = valor;
      }

      input.name = col;
      input.id = `input-crud-${col}`;
      input.style.padding = '10px';
      if (input.type !== 'checkbox') {
        input.style.border = '1px solid var(--border)';
        input.style.borderRadius = 'var(--radius-sm)';
        input.style.background = 'var(--bg-card)';
        input.style.color = 'var(--text-primary)';
      }

      inputWrap.appendChild(input);
      container.appendChild(inputWrap);
    });

    // Inyectar cargador de imagen dinámico si la tabla es 'productos'
    if (tablaActiva === 'productos') {
      const inputWrap = document.createElement('div');
      inputWrap.style.display = 'flex';
      inputWrap.style.flexDirection = 'column';
      inputWrap.style.gap = '5px';
      inputWrap.style.marginTop = '10px';

      const label = document.createElement('label');
      label.innerText = 'Subir Imagen del Producto (.jpg, .png)';
      label.style.fontWeight = 'bold';
      label.style.fontSize = '0.85rem';
      label.style.color = 'var(--text-secondary)';
      inputWrap.appendChild(label);

      const input = document.createElement('input');
      input.type = 'file';
      input.id = 'input-crud-imagen_producto';
      input.name = 'imagen_producto';
      input.accept = 'image/*';
      input.style.padding = '8px';
      input.style.border = '1px dashed var(--accent-gold-dark)';
      input.style.borderRadius = 'var(--radius-sm)';
      input.style.background = 'var(--bg-secondary)';
      input.style.color = 'var(--text-primary)';

      inputWrap.appendChild(input);
      container.appendChild(inputWrap);
    }
  }

  function cerrarModalCrud() {
    document.getElementById('crudModal').style.display = 'none';
  }

  async function guardarRegistro(event) {
    event.preventDefault();

    const formData = new FormData();
    metadataColumnas.forEach((col, idx) => {
      if (idx === 0 && !filaEdicion) return;

      const input = document.getElementById(`input-crud-${col}`);
      if (input.type === 'checkbox') {
        formData.append(col, input.checked ? 1 : 0);
      } else {
        formData.append(col, input.value === '' ? '' : input.value);
      }
    });

    if (tablaActiva === 'productos') {
      const fileInput = document.getElementById('input-crud-imagen_producto');
      if (fileInput && fileInput.files[0]) {
        formData.append('imagen_producto', fileInput.files[0]);
      }
    }

    try {
      let url = `/admin/tablas/${tablaActiva}`;
      let method = 'POST'; // Siempre POST para soportar subidas de archivos en Laravel

      if (filaEdicion) {
        const pkCol = metadataColumnas[0];
        const pkVal = filaEdicion[pkCol];
        url = `/admin/tablas/${tablaActiva}/${pkCol}/${pkVal}`;
        formData.append('_method', 'PUT'); // Simulación de PUT para multipart/form-data en Laravel
      }

      const response = await fetch(url, {
        method: method,
        headers: { 
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
          // Dejar que el navegador configure automáticamente el Content-Type multipart/form-data con boundary
        },
        body: formData
      });

      const data = await response.json();
      if (response.ok) {
        showToast(data.mensaje, 'success');
        cerrarModalCrud();
        setTimeout(() => window.location.reload(), 800);
      } else {
        showToast(data.mensaje || 'Error al guardar registro.', 'error');
      }
    } catch {
      showToast('Error de red al guardar en la base de datos.', 'error');
    }
  }

  async function eliminarFila(pkColumna, pkValor) {
    if (!confirm(`¿Estás seguro de que deseas eliminar este registro con ID ${pkValor}?\n\nEsta operación puede tener repercusiones en registros encadenados en otras tablas.`)) {
      return;
    }

    try {
      const response = await fetch(`/admin/tablas/${tablaActiva}/${pkColumna}/${pkValor}`, {
        method: 'DELETE',
        headers: { 
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      });
      const data = await response.json();
      
      if (response.ok) {
        showToast(data.mensaje, 'success');
        setTimeout(() => window.location.reload(), 800);
      } else {
        showToast(data.mensaje || 'Error al eliminar el registro.', 'error');
      }
    } catch {
      showToast('Error de red al intentar eliminar el registro.', 'error');
    }
  }
</script>
@endsection