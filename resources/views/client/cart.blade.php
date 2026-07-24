@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🛒</span>
    <div>
      <h1 class="section-title">
        Carrito de Compras
        <button class="help-btn-trigger" onclick="abrirAyuda('Carrito de Compras', 'Bandeja de preparación de la compra. Aquí puedes verificar los productos agregados y sus modificadores de barra, aplicar cupones promocionales, canjear tus puntos club acumulados, elegir el método de entrega (mesa, llevar o a domicilio) y realizar tu pago de forma segura.')">❓</button>
      </h1>
      <p class="section-desc">Revise sus art&iacute;culos, ajuste las cantidades y finalice su compra.</p>
    </div>
  </div>

  <div class="card" style="padding: 40px;">
    <div id="cart-page-content">
      <!-- Se carga din&aacute;micamente -->
    </div>
  </div>
</div>

<!-- Modal del Ticket -->
<div id="ticket-modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.65); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
  <div id="ticket-modal" style="background:var(--bg-card); border-radius:var(--radius-lg); max-width:480px; width:90%; max-height:90vh; overflow-y:auto; padding:0; box-shadow:0 20px 60px rgba(0,0,0,0.4); border:1px solid var(--border); animation: fadeInUp 0.4s ease;">
    <div id="ticket-content" style="padding:40px 36px;">
      <!-- ticket se genera din&aacute;micamente -->
    </div>
    <div style="padding:16px 36px 28px; text-align:center; border-top:1px dashed var(--border); display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
      <button class="btn-primary" onclick="descargarTicketPDF()" style="padding:12px 24px; font-size:0.95rem; background:linear-gradient(135deg, #1b2a4a, #2c3e66); color:#fff; border:1px solid var(--accent-gold);">
        📥 Descargar Ticket (PDF)
      </button>
      <button class="btn-primary" onclick="cerrarTicketModal()" style="padding:12px 28px; font-size:0.95rem; background:var(--bg-secondary); color:var(--text-primary); border:1px solid var(--border);">
        ⚜️ Cerrar
      </button>
    </div>
  </div>
</div>

<style>
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .pago-option {
    display: flex; align-items: center; gap: 10px; padding: 14px 18px;
    border: 2px solid var(--border); border-radius: var(--radius-md);
    cursor: pointer; transition: all 0.25s ease; background: var(--bg-card);
  }
  .pago-option:hover { border-color: var(--accent-gold); background: var(--bg-secondary); }
  .pago-option.selected { border-color: var(--accent-gold-dark); background: rgba(196,164,106,0.1); }
  .pago-option input[type="radio"] { accent-color: var(--accent-gold-dark); width: 18px; height: 18px; }
  .pago-option .pago-icon { font-size: 1.5rem; }
  .pago-option .pago-label { font-weight: 700; font-size: 0.95rem; color: var(--text-primary); }
  .pago-option .pago-desc { font-size: 0.8rem; color: var(--text-secondary); }
  .puntos-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #f5e6c8, #c4a46a); color: #3a2a10;
    padding: 6px 14px; border-radius: 99px; font-weight: 700; font-size: 0.85rem;
  }

  /* Ticket Styles */
  .ticket-header { text-align: center; margin-bottom: 20px; }
  .ticket-header h2 { font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--accent-navy); margin: 0; }
  .ticket-header p { color: var(--text-secondary); font-size: 0.85rem; margin: 4px 0 0; }
  .ticket-separator { border: none; border-top: 2px dashed var(--border); margin: 16px 0; }
  .ticket-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 0.95rem; }
  .ticket-row.total { font-weight: 800; font-size: 1.2rem; color: var(--accent-navy); border-top: 2px solid var(--accent-gold-dark); padding-top: 12px; margin-top: 8px; }
  .ticket-row .label { color: var(--text-secondary); }
  .ticket-row .value { font-weight: 600; color: var(--text-primary); }
  .ticket-puntos-badge { display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #d4edda, #28a745); color: #fff; padding: 8px 16px; border-radius: var(--radius-md); font-weight: 700; font-size: 0.9rem; margin-top: 12px; }
</style>
@endsection

@section('scripts')
<script>
  let cart = [];
  let metodoPagoSeleccionado = 'EFECTIVO';
  let puntosUsuario = {{ $puntosDisponibles }};
  let cuponAplicado = null;
  let usarPuntos = false;

  function loadCart() {
    cart = JSON.parse(localStorage.getItem('cafe_cart') || '[]');
  }

  function saveCart() {
    localStorage.setItem('cafe_cart', JSON.stringify(cart));
  }

  function removeFromCart(itemKey) {
    const item = cart.find(i => i.itemKey === itemKey);
    const nombre = item ? item.nombre : 'Producto';
    cart = cart.filter(i => i.itemKey !== itemKey);
    saveCart();
    renderCartPage();
    showToast(`${nombre} eliminado del carrito`, "success");
  }

  function changeQty(itemKey, delta) {
    const item = cart.find(i => i.itemKey === itemKey);
    if (!item) return;

    item.cantidad += delta;
    if (item.cantidad <= 0) {
      removeFromCart(itemKey);
      return;
    }
    saveCart();
    renderCartPage();
  }

  let OscarMock = 0; // Padding


  function seleccionarMetodoPago(clave) {
    metodoPagoSeleccionado = clave;
    document.querySelectorAll('.pago-option').forEach(el => {
      el.classList.toggle('selected', el.dataset.clave === clave);
    });
    const radio = document.querySelector(`input[name="metodo_pago"][value="${clave}"]`);
    if (radio) radio.checked = true;
    
    usarPuntos = (clave === 'PUNTOS');
    renderCartPage();
  }

  let entregaTipo = 'MESA';
  function toggleEntregaTipo(tipo) {
    entregaTipo = tipo;
    const mesaCont = document.getElementById('mesa-input-container');
    const dirCont = document.getElementById('direccion-input-container');
    if (mesaCont && dirCont) {
      mesaCont.style.display = tipo === 'MESA' ? 'block' : 'none';
      dirCont.style.display = tipo === 'DOMICILIO' ? 'block' : 'none';
    }
  }

  async function aplicarCupon() {
    const input = document.getElementById('cupon_codigo');
    const msg = document.getElementById('cupon-status-msg');
    if (!input || !msg) return;

    const codigo = input.value.trim().toUpperCase();
    if (!codigo) {
      showToast('Por favor introduce un código de cupón.', 'error');
      return;
    }

    try {
      const response = await fetch(`/api/cupones/validar/${encodeURIComponent(codigo)}`);
      const data = await response.json();

      if (response.ok && data.valido) {
        cuponAplicado = data;
        msg.style.display = 'block';
        msg.style.color = '#28a745';
        msg.innerHTML = `<strong>Cup&oacute;n aplicado:</strong> ${data.descripcion}`;
        showToast('Cup&oacute;n aplicado con &eacute;xito.', 'success');
        renderCartPage();
      } else {
        msg.style.display = 'block';
        msg.style.color = 'var(--accent-err)';
        msg.innerText = data.mensaje || 'Error al validar cup&oacute;n.';
        showToast(data.mensaje || 'Cup&oacute;n no v&aacute;lido.', 'error');
      }
    } catch {
      showToast('Error al conectar con el servidor para validar cup&oacute;n.', 'error');
    }
  }

  function quitarCupon() {
    cuponAplicado = null;
    const msg = document.getElementById('cupon-status-msg');
    if (msg) {
      msg.style.display = 'none';
      msg.innerText = '';
    }
    showToast('Cup&oacute;n removido.', 'success');
    renderCartPage();
  }

  function renderCartPage() {
    const container = document.getElementById('cart-page-content');
    if (cart.length === 0) {
      container.innerHTML = `
        <div style="text-align: center; padding: 60px 20px;">
          <div style="font-size: 4rem; margin-bottom: 20px;">🛒</div>
          <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--accent-navy); margin-bottom: 10px;">Tu carrito est&aacute; vac&iacute;o</h2>
          <p style="color: var(--text-secondary); margin-bottom: 30px;">A&uacute;n no has a&ntilde;adido ning&uacute;n producto del cat&aacute;logo.</p>
          <a href="/menu" class="btn-primary" style="text-decoration: none;">📋 Ver Especialidades</a>
        </div>`;
      return;
    }

    let html = `
      <div class="table-wrap" style="margin-bottom: 30px; border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden;">
        <table aria-label="Productos en el carrito" style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: var(--bg-secondary);">
              <th style="padding: 18px 24px; text-align: left; font-size: 0.95rem; text-transform: uppercase; color: var(--accent-gold-dark); font-weight: 700;">Producto</th>
              <th style="padding: 18px 24px; text-align: left; font-size: 0.95rem; text-transform: uppercase; color: var(--accent-gold-dark); font-weight: 700;">Precio</th>
              <th style="padding: 18px 24px; text-align: center; font-size: 0.95rem; text-transform: uppercase; color: var(--accent-gold-dark); font-weight: 700;">Cantidad</th>
              <th style="padding: 18px 24px; text-align: right; font-size: 0.95rem; text-transform: uppercase; color: var(--accent-gold-dark); font-weight: 700;">Subtotal</th>
              <th style="padding: 18px 24px; text-align: center; font-size: 0.95rem; text-transform: uppercase; color: var(--accent-gold-dark); font-weight: 700;">Acciones</th>
            </tr>
          </thead>
          <tbody>`;

    let total = 0;
    cart.forEach(item => {
      const subtotal = item.precio * item.cantidad;
      total += subtotal;

      // Crear cadena con los modificadores elegidos
      let modsHtml = '';
      if (item.modificadores && item.modificadores.length > 0) {
        const names = item.modificadores.map(m => m.nombre).join(', ');
        modsHtml = `<div style="font-size: 0.85rem; color: var(--accent-gold-dark); font-style: italic; margin-top: 4px;">(${names})</div>`;
      }

      html += `
        <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card);">
          <td style="padding: 20px 24px;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <img src="${item.imgUrl}" alt="${item.nombre}" style="width: 70px; height: 70px; object-fit: cover; border-radius: var(--radius-lg); border: 1px solid var(--border);" />
              <div>
                <div style="font-weight: 700; font-size: 1.1rem; color: var(--accent-navy);">${item.nombre}</div>
                ${modsHtml}
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">C&oacute;digo: ${item.codigo}</div>
              </div>
            </div>
          </td>
          <td style="padding: 20px 24px; font-weight: 600; color: var(--text-primary); font-size: 1.05rem;">$${item.precio.toFixed(2)}</td>
          <td style="padding: 20px 24px; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--bg-secondary); padding: 6px 12px; border-radius: 99px; border: 1px solid var(--border);">
              <button class="qty-btn" onclick="changeQty('${item.itemKey}', -1)" style="width: 32px; height: 32px; font-size: 1.1rem;">−</button>
              <span style="font-weight: 700; font-size: 1.1rem; min-width: 24px; text-align: center;">${item.cantidad}</span>
              <button class="qty-btn" onclick="changeQty('${item.itemKey}', 1)" style="width: 32px; height: 32px; font-size: 1.1rem;">+</button>
            </div>
          </td>
          <td style="padding: 20px 24px; text-align: right; font-weight: 700; font-size: 1.25rem; color: var(--accent-gold-dark); font-family: 'Cormorant Garamond', serif;">$${subtotal.toFixed(2)}</td>
          <td style="padding: 20px 24px; text-align: center;">
            <button class="qty-btn remove" onclick="removeFromCart('${item.itemKey}')" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem;">🗑&nbsp;</button>
          </td>
        </tr>`;
    });

    let descuentoCup = 0;
    if (cuponAplicado) {
      if (cuponAplicado.tipoDescuento === 'PORCENTAJE') {
        descuentoCup = total * (cuponAplicado.valorDescuento / 100);
      } else {
        descuentoCup = cuponAplicado.valorDescuento;
      }
      descuentoCup = Math.min(descuentoCup, total);
    }

    let descuentoPuntos = 0;
    if (usarPuntos && puntosUsuario > 0) {
      const maxDescuentoPosible = total - descuentoCup;
      descuentoPuntos = Math.min(puntosUsuario * 1.0, maxDescuentoPosible);
    }

    const totalFinal = total - descuentoCup - descuentoPuntos;

    html += `
          </tbody>
        </table>
      </div>
      
      <div style="display: flex; gap: 30px; margin-top: 30px; flex-wrap: wrap;">

        <!-- Columna izquierda: Entrega y Notas -->
        <div style="flex: 1; min-width: 300px;">
          <div style="padding: 25px; background: var(--bg-secondary); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 20px;">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">⚜️ M&eacute;todo de Entrega</h3>
            <div style="display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap;">
              <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                <input type="radio" name="entrega_tipo" value="MESA" ${entregaTipo === 'MESA' ? 'checked' : ''} onchange="toggleEntregaTipo('MESA')" style="accent-color: var(--accent-gold-dark);" />
                Consumo en Mesa
              </label>
              <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                <input type="radio" name="entrega_tipo" value="LLEVAR" ${entregaTipo === 'LLEVAR' ? 'checked' : ''} onchange="toggleEntregaTipo('LLEVAR')" style="accent-color: var(--accent-gold-dark);" />
                Para Llevar (Pick-Up)
              </label>
              <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                <input type="radio" name="entrega_tipo" value="DOMICILIO" ${entregaTipo === 'DOMICILIO' ? 'checked' : ''} onchange="toggleEntregaTipo('DOMICILIO')" style="accent-color: var(--accent-gold-dark);" />
                Pedido en Casa
              </label>
            </div>
            <div id="mesa-input-container" style="display: ${entregaTipo === 'MESA' ? 'block' : 'none'};">
              <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px;">Seleccionar Mesa:</label>
              <select id="numero_mesa" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); color: var(--text-primary); font-weight: bold;">
                @foreach ($mesas as $mesa)
                  <option value="{{ $mesa->NumeroMesa }}">Mesa {{ $mesa->NumeroMesa }}</option>
                @endforeach
              </select>
            </div>
            <div id="direccion-input-container" style="display: ${entregaTipo === 'DOMICILIO' ? 'block' : 'none'}; margin-top: 10px;">
              <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px;">Direcci&oacute;n de Entrega:</label>
              <input type="text" id="direccion_entrega" placeholder="Calle, N&uacute;mero, Colonia, C.P. ..." style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.95rem; background: var(--bg-card); color: var(--text-primary);" />
              <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; margin-top: 10px;">Tel&eacute;fono de contacto:</label>
              <input type="text" id="telefono_contacto" placeholder="Ej. 5512345678" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.95rem; background: var(--bg-card); color: var(--text-primary);" />
            </div>
          </div>
          <div style="padding: 25px; background: var(--bg-secondary); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 20px;">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">🎟️ Cup&oacute;n de Descuento</h3>
            <div id="cupon-input-wrapper" style="display: flex; gap: 10px;">
              <input type="text" id="cupon_codigo" placeholder="Ej. DESCUENTO10" style="flex: 1; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.95rem; font-weight: bold; background: var(--bg-card); color: var(--text-primary); text-transform: uppercase;" />
              <button class="btn-primary" id="cupon_btn" onclick="aplicarCupon()" style="padding: 10px 20px; font-size: 0.95rem; width: auto; min-width: 90px;">Aplicar</button>
            </div>
            <div id="cupon-status-msg" style="margin-top: 10px; font-size: 0.85rem; display: ${cuponAplicado ? 'block' : 'none'}; line-height: 1.4; color: #28a745;">
              ${cuponAplicado ? `<strong>Cup&oacute;n aplicado:</strong> ${cuponAplicado.descripcion}` : ''}
            </div>
          </div>
          <div style="padding: 25px; background: var(--bg-secondary); border-radius: var(--radius-lg); border: 1px solid var(--border);">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">📝 Notas Especiales</h3>
            <textarea id="notas_pedido" placeholder="Instrucciones especiales para el chef (ej. sin az&uacute;car, leche de almendra, etc.)..." style="width: 100%; height: 80px; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-family: inherit; font-size: 0.95rem; resize: none; background: var(--bg-card); color: var(--text-primary);"></textarea>
          </div>
        </div>

        <!-- Columna derecha: Método de Pago -->
        <div style="flex: 1; min-width: 300px;">
          <div style="padding: 25px; background: var(--bg-secondary); border-radius: var(--radius-lg); border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
              <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin: 0;">💳 M&eacute;todo de Pago</h3>
              @auth
                <div id="puntos-badge-container">
                  <span class="puntos-badge">⭐ ${puntosUsuario} pts</span>
                </div>
              @endauth
            </div>
            <div id="metodos-pago-container" style="display: flex; flex-direction: column; gap: 12px;">
              @foreach ($metodosPago as $m)
                <div class="pago-option ${metodoPagoSeleccionado === '{{ $m->Clave }}' ? 'selected' : ''}" data-clave="{{ $m->Clave }}" onclick="seleccionarMetodoPago('{{ $m->Clave }}')">
                  <input type="radio" name="metodo_pago" value="{{ $m->Clave }}" ${metodoPagoSeleccionado === '{{ $m->Clave }}' ? 'checked' : ''} style="pointer-events: none;" />
                  <div>
                    <span class="pago-label">{{ $m->Etiqueta }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

      </div>
      
      <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 25px; border-top: 2px dashed var(--border); margin-top: 25px; flex-wrap: wrap; gap: 20px;">
        <div>
          <a href="/menu" class="btn-secondary" style="text-decoration: none; padding: 14px 28px; font-size: 1rem; width: auto; display: inline-flex;">🛍️ Seguir Comprando</a>
        </div>
        <div style="display: flex; align-items: center; gap: 40px; text-align: right;">
          <div style="display: flex; flex-direction: column;">
            ${descuentoCup > 0 ? `
              <span style="font-size: 0.95rem; color: var(--text-secondary); text-decoration: line-through; margin-bottom: 2px;">Subtotal: $${total.toFixed(2)}</span>
              <span style="font-size: 0.95rem; color: #28a745; font-weight: bold; margin-bottom: 6px;">Descuento Cup&oacute;n: -$${descuentoCup.toFixed(2)}</span>
            ` : ''}
            ${descuentoPuntos > 0 ? `
              <span style="font-size: 0.95rem; color: #28a745; font-weight: bold; margin-bottom: 6px;">Descuento Puntos: -$${descuentoPuntos.toFixed(2)}</span>
            ` : ''}
            <span style="font-size: 1rem; color: var(--text-secondary); font-weight: 600;">Total a pagar:</span>
            <span style="font-family: 'Cormorant Garamond', serif; font-size: 2.8rem; font-weight: 700; color: var(--accent-navy);">$${totalFinal.toFixed(2)}</span>
          </div>
          <button class="btn-primary" onclick="finalizarCompraPage()" style="padding: 16px 36px; font-size: 1.05rem;">✅ Finalizar Compra</button>
        </div>
      </div>`;

    container.innerHTML = html;
  }

  async function finalizarCompraPage() {
    if (cart.length === 0) return;
    
    const numeroMesa = document.getElementById('numero_mesa') ? document.getElementById('numero_mesa').value : null;
    const direccion = document.getElementById('direccion_entrega') ? document.getElementById('direccion_entrega').value : null;
    const telefono = document.getElementById('telefono_contacto') ? document.getElementById('telefono_contacto').value : null;

    if (entregaTipo === 'DOMICILIO' && (!direccion || !telefono)) {
      showToast('Por favor ingrese la direcci&oacute;n y tel&eacute;fono de contacto.', 'error');
      return;
    }

    const payload = {
      items: cart,
      metodoPago: metodoPagoSeleccionado,
      entrega: entregaTipo,
      numeroMesa: numeroMesa,
      direccion: direccion,
      telefono: telefono,
      cupon: cuponAplicado ? cuponAplicado.codigo : null,
      usarPuntos: usarPuntos
    };

    try {
      const response = await fetch('/Carrito/Checkout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
      });

      const data = await response.json();

      if (response.ok && data.exito) {
        showToast(data.mensaje, 'success');
        mostrarTicket(data.ticket);
        cart = [];
        saveCart();
      } else {
        showToast(data.mensaje || 'Error al procesar compra.', 'error');
      }
    } catch {
      showToast('Error de red al intentar finalizar la compra.', 'error');
    }
  }

  let currentTicket = null;

  function mostrarTicket(ticket) {
    currentTicket = ticket;
    const content = document.getElementById('ticket-content');
    if (!content) return;

    let itemsHtml = '';
    ticket.productos.forEach(i => {
      itemsHtml += `
        <div class="ticket-row">
          <span class="label">${i.nombre} x${i.cantidad}</span>
          <span class="value">$${(i.precio * i.cantidad).toFixed(2)}</span>
        </div>`;
    });

    content.innerHTML = `
      <div class="ticket-header">
        <h2>⚜️ TICKET DE COMPRA ⚜️</h2>
        <p>Caf&eacute; Parisien - L'&Eacute;l&eacute;gance</p>
        <p>Folio: <strong>${ticket.folio}</strong></p>
        <p>Fecha: ${ticket.fecha}</p>
      </div>
      <hr class="ticket-separator" />
      ${itemsHtml}
      <hr class="ticket-separator" />
      <div class="ticket-row">
        <span class="label">Subtotal</span>
        <span class="value">$${ticket.subtotal.toFixed(2)}</span>
      </div>
      ${ticket.descuento > 0 ? `
        <div class="ticket-row" style="color: #28a745; font-weight: bold;">
          <span class="label">Descuento</span>
          <span class="value">-$${ticket.descuento.toFixed(2)}</span>
        </div>
      ` : ''}
      <div class="ticket-row">
        <span class="label">IVA Incluido (16%)</span>
        <span class="value">$${ticket.iva.toFixed(2)}</span>
      </div>
      <div class="ticket-row total">
        <span>TOTAL</span>
        <span>$${ticket.total.toFixed(2)}</span>
      </div>
      <hr class="ticket-separator" />
      <div style="text-align: center; color: var(--text-secondary); font-size: 0.85rem; font-style: italic;">
        M&eacute;todo: ${ticket.metodoPago} | Entrega: ${ticket.entrega}
        ${ticket.mesa ? `<br/>Mesa: ${ticket.mesa}` : ''}
        ${ticket.direccion ? `<br/>Direcci&oacute;n: ${ticket.direccion}` : ''}
        <br/><br/>
        <div style="text-align: center; margin: 12px 0;">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${encodeURIComponent('CAFEPARISIEN|COMPRA|' + ticket.folio + '|TOTAL:' + ticket.total.toFixed(2))}" alt="QR Ticket" style="width: 120px; height: 120px; border-radius: 6px; border: 1px solid var(--border); padding: 4px; background: #fff;" />
          <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px; font-family: monospace;">FOLIO QR: ${ticket.folio}</div>
        </div>
        ¡Merci beaucoup pour votre visite! 🥖☕
      </div>`;

    document.getElementById('ticket-modal-overlay').style.display = 'flex';
  }

  function descargarTicketPDF() {
    if (!currentTicket) {
      showToast('No se encontró información del ticket.', 'error');
      return;
    }

    const pdfWrap = document.createElement('div');
    pdfWrap.style.padding = '30px';
    pdfWrap.style.background = '#ffffff';
    pdfWrap.style.color = '#111827';
    pdfWrap.style.fontFamily = "'Helvetica Neue', Arial, sans-serif";
    pdfWrap.style.width = '500px';

    let itemsRows = '';
    currentTicket.productos.forEach(i => {
      itemsRows += `
        <tr style="border-bottom: 1px solid #e5e7eb;">
          <td style="padding: 10px 0; font-size: 14px; color: #111827;"><strong>${i.nombre}</strong> x${i.cantidad}</td>
          <td style="padding: 10px 0; font-size: 14px; text-align: right; font-weight: bold; color: #111827;">$${(i.precio * i.cantidad).toFixed(2)}</td>
        </tr>`;
    });

    pdfWrap.innerHTML = `
      <div style="text-align: center; border-bottom: 2px dashed #9ca3af; padding-bottom: 18px; margin-bottom: 18px;">
        <h1 style="margin: 0; font-size: 26px; color: #1b2a4a; font-family: Georgia, serif;">⚜️ CAFÉ PARISIEN ⚜️</h1>
        <p style="margin: 4px 0; font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1.5px; font-weight: bold;">L'Élégance - Comprobante de Compra</p>
        <div style="margin-top: 12px; font-size: 13px; color: #374151; background: #f9fafb; padding: 10px; border-radius: 6px; border: 1px solid #f3f4f6;">
          <strong>Folio:</strong> ${currentTicket.folio}<br/>
          <strong>Fecha de Emisión:</strong> ${currentTicket.fecha}
        </div>
      </div>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
          <tr style="border-bottom: 2px solid #1b2a4a; text-align: left; font-size: 12px; color: #6b7280; text-transform: uppercase;">
            <th style="padding-bottom: 8px;">Concepto / Producto</th>
            <th style="padding-bottom: 8px; text-align: right;">Importe</th>
          </tr>
        </thead>
        <tbody>
          ${itemsRows}
        </tbody>
      </table>

      <div style="border-top: 2px solid #1b2a4a; padding-top: 14px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 6px; color: #374151;">
          <span>Subtotal:</span>
          <span>$${currentTicket.subtotal.toFixed(2)}</span>
        </div>
        ${currentTicket.descuento > 0 ? `
          <div style="display: flex; justify-content: space-between; font-size: 14px; color: #059669; font-weight: bold; margin-bottom: 6px;">
            <span>Descuento Aplicado:</span>
            <span>-$${currentTicket.descuento.toFixed(2)}</span>
          </div>
        ` : ''}
        <div style="display: flex; justify-content: space-between; font-size: 13px; color: #6b7280; margin-bottom: 8px;">
          <span>IVA Incluido (16%):</span>
          <span>$${currentTicket.iva.toFixed(2)}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #1b2a4a; border-top: 2px solid #e5e7eb; padding-top: 10px;">
          <span>TOTAL PAGADO:</span>
          <span>$${currentTicket.total.toFixed(2)}</span>
        </div>
      </div>

      <div style="background: #f3f4f6; padding: 14px; border-radius: 8px; font-size: 13px; color: #374151; border: 1px solid #e5e7eb; margin-bottom: 20px;">
        <strong>Método de Pago:</strong> ${currentTicket.metodoPago}<br/>
        <strong>Forma de Entrega:</strong> ${currentTicket.entrega}
        ${currentTicket.mesa ? `<br/><strong>Mesa Asignada:</strong> Mesa ${currentTicket.mesa}` : ''}
        ${currentTicket.direccion ? `<br/><strong>Dirección Entrega:</strong> ${currentTicket.direccion}` : ''}
      </div>

      <div style="text-align: center; margin: 18px 0; padding: 12px; background: #ffffff; border: 2px dashed #1b2a4a; border-radius: 8px;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=${encodeURIComponent('CAFEPARISIEN|COMPRA|FOLIO:' + currentTicket.folio + '|TOTAL:$' + currentTicket.total.toFixed(2))}" alt="Código QR Ticket" style="width: 130px; height: 130px; border-radius: 4px;" />
        <div style="font-size: 11px; color: #1b2a4a; margin-top: 4px; font-weight: bold; font-family: monospace;">
          FOLIO QR: ${currentTicket.folio}
        </div>
      </div>

      <div style="text-align: center; font-size: 12px; color: #6b7280; font-style: italic; border-top: 1px dashed #d1d5db; padding-top: 14px;">
        ¡Merci beaucoup pour votre visite! 🥖☕<br/>
        Café Parisien - Conserve este comprobante para cualquier aclaración.
      </div>
    `;

    if (typeof html2pdf !== 'undefined') {
      const opt = {
        margin:       [10, 10, 10, 10],
        filename:     `Ticket_CafeParisien_${currentTicket.folio || 'compra'}.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };

      html2pdf().set(opt).from(pdfWrap).save().then(() => {
        showToast('¡Ticket PDF guardado en sus descargas!', 'success');
      });
    } else {
      const printWin = window.open('', '', 'width=650,height=850');
      printWin.document.write('<html><head><title>Ticket - Café Parisien</title></head><body style="padding:20px;">' + pdfWrap.innerHTML + '</body></html>');
      printWin.document.close();
      printWin.focus();
      setTimeout(() => printWin.print(), 300);
    }
  }

  function cerrarTicketModal() {
    document.getElementById('ticket-modal-overlay').style.display = 'none';
    window.location.href = '/menu';
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadCart();
    renderCartPage();
  });
</script>
@endsection