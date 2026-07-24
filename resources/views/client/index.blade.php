@extends('layouts.app')

@section('title', 'Especialidades')

@section('content')
@php
  function obtenerImagenProducto($nombreProducto) {
      $carpetaExterna = "C:\\XD\\Examen-de-programacion\\imagenes para diseño de la pagia";
      if (is_dir($carpetaExterna)) {
          $archivos = scandir($carpetaExterna);
          foreach ($archivos as $archivo) {
              if ($archivo === "." || $archivo === "..") continue;
              $pathInfo = pathinfo($archivo);
              if (strcasecmp($pathInfo['filename'], trim($nombreProducto)) === 0) {
                  $dest = public_path("images/externo/" . $archivo);
                  if (!file_exists(dirname($dest))) {
                      mkdir(dirname($dest), 0755, true);
                  }
                  if (!file_exists($dest)) {
                      copy($carpetaExterna . "/" . $archivo, $dest);
                  }
                  return "/images/externo/" . $archivo;
              }
          }
      }
      
      // Fallback
      $hash = abs(crc32($nombreProducto));
      $unsplashIds = [
          "photo-1509042239860-f550ce710b93",
          "photo-1495474472287-4d71bcdd2085",
          "photo-1517248135467-4c7edcad34c4",
          "photo-1504674900247-0877df9cc836",
          "photo-1555507036-ab1f4038024a",
          "photo-1414235077428-338989a2e8c0",
          "photo-1476224203421-9ac39bcb3327",
          "photo-1559622214-f8a9850a5ac6"
      ];
      $id = $unsplashIds[$hash % count($unsplashIds)];
      return "https://images.unsplash.com/" . $id . "?auto=format&fit=crop&q=80&w=600&h=400";
  }

  // Obtener lista plana de productos para JS
  $todosLosProductos = [];
  foreach ($productosPorCategoria as $catName => $prods) {
      foreach ($prods as $p) {
          $todosLosProductos[] = $p;
      }
  }
@endphp

<div class="section">
  <div class="section-header">
    <span class="section-icon">⚜️</span>
    <div>
      <h1 class="section-title">
        Nuestras Especialidades
        <button class="help-btn-trigger" onclick="abrirAyuda('Nuestras Especialidades', 'Este es el catálogo digital e interactivo de la cafetería. Aquí puedes buscar y filtrar especialidades, consultar la existencia en tiempo real y hacer clic en el botón \'Añadir\' para personalizar los modificadores de tu bebida (tipos de leche, jarabes extras) antes de agregarlos al carrito.')">❓</button>
      </h1>
      <p class="section-desc">Descubra nuestra exquisita selección de especialidades artesanales.</p>
    </div>
  </div>

  <!-- Barra de búsqueda -->
  <div class="search-bar-wrap">
    <span class="search-icon">🔍</span>
    <input type="text" id="searchInput" class="search-input" placeholder="Buscar platillo o bebida..." autocomplete="off" />
  </div>

  <!-- Estadísticas compactas -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrap purple">🧁</div>
      <div>
        <div class="stat-value">{{ count($todosLosProductos) }}</div>
        <div class="stat-label">Especialidades</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap orange">⚠</div>
      <div>
        <div class="stat-value">{{ collect($todosLosProductos)->where('Existencia', '<=', 10)->count() }}</div>
        <div class="stat-label">Disponibilidad limitada</div>
      </div>
    </div>
  </div>

  <!-- Cuadrícula Visual de Productos -->
  @if (empty($todosLosProductos))
    <div class="card" style="text-align: center; padding: 60px 30px;">
      <p style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--text-muted); font-style: italic;">
        El catálogo de especialidades está siendo preparado...
      </p>
    </div>
  @else
    <div class="product-grid" id="productGrid">
      @foreach ($todosLosProductos as $p)
        @php
          $imgUrl = obtenerImagenProducto($p->Nombre);
          $stockClass = $p->Existencia == 0 ? "stock-vacio" : ($p->Existencia <= 10 ? "stock-bajo" : "stock-ok");
          $isAgotado = $p->Existencia == 0;
        @endphp

        <div class="product-card" data-name="{{ strtolower($p->Nombre) }}">
          <div class="product-img-wrap">
            <img src="{{ $imgUrl }}" alt="{{ $p->Nombre }}" class="product-img" loading="lazy" />
            <span class="product-badge-code">{{ $p->Codigo }}</span>
          </div>
          <div class="product-info">
            <h3 class="product-name">{{ $p->Nombre }}</h3>
            <div class="product-meta">
              <span class="product-price">${{ number_format($p->Precio, 2) }}</span>
              <span class="product-stock {{ $stockClass }}" id="stock-{{ $p->Codigo }}">
                @if ($p->Existencia == 0)
                  🔴 Agotado
                @elseif ($p->Existencia <= 10)
                  🟡 Quedan <span id="stock-val-{{ $p->Codigo }}">{{ $p->Existencia }}</span> uds.
                @else
                  🟢 <span id="stock-val-{{ $p->Codigo }}">{{ $p->Existencia }}</span> uds. disponibles
                @endif
              </span>
            </div>
            <button class="btn-add-cart {{ $isAgotado ? 'disabled' : '' }}"
                    {{ $isAgotado ? 'disabled' : '' }}
                    id="btn-add-{{ $p->Codigo }}"
                    onclick="addToCart('{{ $p->Codigo }}', '{{ addslashes($p->Nombre) }}', {{ $p->Precio }}, '{{ $imgUrl }}')"
                    aria-label="Añadir {{ $p->Nombre }} al carrito">
              <span>🛒</span> {{ $isAgotado ? 'Agotado' : 'Añadir al carrito' }}
            </button>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<!-- MODAL DEL CARRITO -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-panel" id="cartPanel">
  <div class="cart-header">
    <h2>🛒 Tu Pedido</h2>
    <button class="cart-close-btn" onclick="closeCart()" aria-label="Cerrar carrito">✕</button>
  </div>

  <div class="cart-items" id="cartItems">
    <!-- El mensaje vacío se inserta dinámicamente -->
  </div>

  <div class="cart-footer" id="cartFooter" style="display:none;">
    <div class="cart-total">
      <span>Total:</span>
      <span class="cart-total-amount" id="cartTotal">$0.00</span>
    </div>
    <button class="btn-primary cart-btn-finish" onclick="window.location.href='/Carrito'">
      ✅ Proceder al Pago (Checkout)
    </button>
    <button class="btn-secondary cart-btn-continue" onclick="closeCart()">
      🛍️ Seguir comprando
    </button>
  </div>
</div>

<!-- MODAL DE MODIFICADORES Y EXTRAS -->
<div class="cart-overlay" id="modifierOverlay" onclick="closeModifierModal()"></div>
<div class="card" id="modifierModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1100; width: 90%; max-width: 480px; padding: 25px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); background: #ffffff; border: 1px solid var(--accent);">
  <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--accent); padding-bottom: 12px; margin-bottom: 18px;">
    <h3 id="modifierProductTitle" style="margin: 0; font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--primary);">☕ Personaliza tu Bebida</h3>
    <button onclick="closeModifierModal()" style="background: none; border: none; font-size: 1.3rem; color: var(--text-muted); cursor: pointer; padding: 5px;">✕</button>
  </div>
  
  <div id="modifierOptionsContainer" style="max-height: 320px; overflow-y: auto; padding-right: 5px; margin-bottom: 20px;">
    <!-- Las opciones de modificadores se cargan aquí dinámicamente -->
  </div>

  <div style="display: flex; gap: 10px; margin-top: 15px;">
    <button class="btn-primary" id="btnConfirmModifiers" style="flex: 1; padding: 12px; font-weight: bold; border-radius: 6px;">
      🛒 Añadir al Pedido
    </button>
    <button class="btn-secondary" onclick="closeModifierModal()" style="padding: 12px; border-radius: 6px;">
      Cancelar
    </button>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // ---- Modificadores y mapeos de base de datos ----
  const productModifiers = {!! json_encode($modificadoresMap ?? []) !!};
  const productIdsByCode = {
    @foreach ($todosLosProductos as $p)
      "{{ $p->Codigo }}": {{ $p->ProductoId }},
    @endforeach
  };

  // ---- Estado del inventario local en la página ----
  const initialStocks = {
    @foreach ($todosLosProductos as $p)
      "{{ $p->Codigo }}": {{ $p->Existencia }},
    @endforeach
  };

  // ---- Estado del carrito (sincronizado con localStorage) ----
  let cart = JSON.parse(localStorage.getItem('cafe_cart') || '[]');

  function saveCart() {
    localStorage.setItem('cafe_cart', JSON.stringify(cart));
  }

  // --- Lógica del Modal de Modificadores ---
  let activeProductForModifiers = null;

  function closeModifierModal() {
    document.getElementById('modifierOverlay').style.display = 'none';
    document.getElementById('modifierModal').style.display = 'none';
    activeProductForModifiers = null;
  }

  function addToCart(codigo, nombre, precio, imgUrl) {
    const productId = productIdsByCode[codigo];
    const modifiers = productModifiers[productId];

    // Si tiene modificadores, abrimos el modal para que el cliente configure la bebida
    if (modifiers) {
      activeProductForModifiers = { codigo, nombre, precio, imgUrl };
      
      document.getElementById('modifierProductTitle').textContent = `☕ Personaliza tu ${nombre}`;
      const container = document.getElementById('modifierOptionsContainer');
      container.innerHTML = '';

      // Iterar sobre los tipos de modificadores asignados al producto
      for (const tipoId in modifiers) {
        const group = modifiers[tipoId];
        let groupHtml = `<div style="margin-bottom: 15px;">
          <h4 style="margin: 0 0 8px 0; font-family: 'Arial', sans-serif; font-size: 1rem; color: var(--primary); font-weight: bold;">${group.Nombre}</h4>
          <div style="display: flex; flex-direction: column; gap: 8px;">`;

        group.Opciones.forEach(opt => {
          const inputType = group.TipoId === 1 ? 'radio' : 'checkbox';
          const inputName = `mod_group_${group.TipoId}`;
          const isChecked = group.TipoId === 1 && opt.OpcionId === group.Opciones[0].OpcionId ? 'checked' : '';
          
          groupHtml += `
            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.95rem; cursor: pointer;">
              <input type="${inputType}" name="${inputName}" value="${opt.OpcionId}" data-name="${opt.Nombre}" ${isChecked} />
              <span>${opt.Nombre}</span>
            </label>`;
        });

        groupHtml += `</div></div>`;
        container.innerHTML += groupHtml;
      }

      // Configurar acción del botón Confirmar
      document.getElementById('btnConfirmModifiers').onclick = () => {
        confirmAddToCartWithModifiers();
      };

      document.getElementById('modifierOverlay').style.display = 'block';
      document.getElementById('modifierModal').style.display = 'block';
      return;
    }

    // Si no tiene modificadores, agregar directo
    confirmAddToCartDirect(codigo, nombre, precio, imgUrl, []);
  }

  function confirmAddToCartWithModifiers() {
    if (!activeProductForModifiers) return;
    const { codigo, nombre, precio, imgUrl } = activeProductForModifiers;
    const productId = productIdsByCode[codigo];
    const modifiers = productModifiers[productId];
    const seleccionados = [];

    // Recoger los inputs marcados
    for (const tipoId in modifiers) {
      const group = modifiers[tipoId];
      const inputs = document.querySelectorAll(`input[name="mod_group_${group.TipoId}"]:checked`);
      
      // Validación de obligatoriedad (por ejemplo, TipoId = 1 es tipo de leche)
      if (group.TipoId === 1 && inputs.length === 0) {
        showToast(`Por favor, selecciona una opción para ${group.Nombre}`, 'error');
        return;
      }

      inputs.forEach(input => {
        seleccionados.push({
          opcionId: parseInt(input.value),
          nombre: input.getAttribute('data-name')
        });
      });
    }

    confirmAddToCartDirect(codigo, nombre, precio, imgUrl, seleccionados);
    closeModifierModal();
  }

  function confirmAddToCartDirect(codigo, nombre, precio, imgUrl, modificadores) {
    const initial = initialStocks[codigo] || 0;
    
    // Generar una clave única para el carrito basada en el código y los modificadores seleccionados
    // para que si eligen el mismo producto con modificadores diferentes, queden en filas separadas
    const modsKey = modificadores.map(m => m.opcionId).sort().join('_');
    const itemKey = `${codigo}_${modsKey}`;
    
    const existing = cart.find(i => i.itemKey === itemKey);
    const currentQty = existing ? existing.cantidad : 0;

    if (currentQty >= initial) {
      showToast(`No quedan más unidades de ${nombre}`, 'error');
      return;
    }

    if (existing) {
      existing.cantidad++;
    } else {
      cart.push({ 
        itemKey, 
        codigo, 
        nombre, 
        precio: parseFloat(precio), 
        imgUrl, 
        cantidad: 1,
        modificadores 
      });
    }
    saveCart();
    updateCartUI();
    showToast(`${nombre} añadido al carrito`, 'success');
  }

  function removeFromCart(itemKey) {
    cart = cart.filter(i => i.itemKey !== itemKey);
    saveCart();
    updateCartUI();
  }

  function changeQty(itemKey, delta) {
    const item = cart.find(i => i.itemKey === itemKey);
    if (!item) return;

    if (delta > 0) {
      const initial = initialStocks[item.codigo] || 0;
      // Sumar todas las cantidades de este producto en el carrito para validar stock total
      const totalCant = cart.filter(i => i.codigo === item.codigo).reduce((sum, i) => sum + i.cantidad, 0);
      if (totalCant >= initial) {
        showToast(`No quedan más unidades de ${item.nombre}`, 'error');
        return;
      }
    }

    item.cantidad += delta;
    if (item.cantidad <= 0) {
      removeFromCart(itemKey);
      return;
    }
    saveCart();
    updateCartUI();
  }

  function updateProductCardsUI() {
    for (const codigo in initialStocks) {
      const initial = initialStocks[codigo];
      // Sumar cantidades de todas las variantes de este producto
      const cantInCart = cart.filter(i => i.codigo === codigo).reduce((sum, i) => sum + i.cantidad, 0);
      const disponible = initial - cantInCart;

      const stockEl = document.getElementById(`stock-${codigo}`);
      const btnEl = document.getElementById(`btn-add-${codigo}`);

      if (disponible <= 0) {
        if (stockEl) {
          stockEl.className = "product-stock stock-vacio";
          stockEl.innerHTML = "🔴 Agotado";
        }
        if (btnEl) {
          btnEl.className = "btn-add-cart disabled";
          btnEl.disabled = true;
          btnEl.innerHTML = "<span>🔴</span> Agotado";
        }
      } else if (disponible <= 10) {
        if (stockEl) {
          stockEl.className = "product-stock stock-bajo";
          stockEl.innerHTML = `🟡 Quedan <span id="stock-val-${codigo}">${disponible}</span> uds.`;
        }
        if (btnEl) {
          btnEl.className = "btn-add-cart";
          btnEl.disabled = false;
          btnEl.innerHTML = "<span>🛒</span> Añadir al carrito";
        }
      } else {
        if (stockEl) {
          stockEl.className = "product-stock stock-ok";
          stockEl.innerHTML = `🟢 <span id="stock-val-${codigo}">${disponible}</span> uds. disponibles`;
        }
        if (btnEl) {
          btnEl.className = "btn-add-cart";
          btnEl.disabled = false;
          btnEl.innerHTML = "<span>🛒</span> Añadir al carrito";
        }
      }
    }
  }

  function updateCartUI() {
    const badge = document.getElementById('cartBadge');
    const itemsContainer = document.getElementById('cartItems');
    const footer = document.getElementById('cartFooter');
    const totalEl = document.getElementById('cartTotal');

    const totalItems = cart.reduce((sum, i) => sum + i.cantidad, 0);
    if (badge) {
      badge.textContent = totalItems;
      badge.style.display = totalItems > 0 ? 'flex' : 'none';
    }

    updateProductCardsUI();

    if (cart.length === 0) {
      itemsContainer.innerHTML = '<p class="cart-empty">Tu carrito está vacío.</p>';
      footer.style.display = 'none';
      return;
    }

    footer.style.display = 'block';

    let html = '';
    let total = 0;
    cart.forEach(item => {
      const subtotal = item.precio * item.cantidad;
      total += subtotal;

      // Crear cadena con los modificadores elegidos
      let modsHtml = '';
      if (item.modificadores && item.modificadores.length > 0) {
        const names = item.modificadores.map(m => m.nombre).join(', ');
        modsHtml = `<div style="font-size: 0.85rem; color: var(--accent); font-style: italic; margin-top: 2px;">(${names})</div>`;
      }

      html += `
        <div class="cart-item">
          <img src="${item.imgUrl}" alt="${item.nombre}" class="cart-item-img" />
          <div class="cart-item-details">
            <span class="cart-item-name">${item.nombre}</span>
            ${modsHtml}
            <span class="cart-item-price">$${item.precio.toFixed(2)} x ${item.cantidad}</span>
          </div>
          <div class="cart-item-actions">
            <button class="qty-btn" onclick="changeQty('${item.itemKey}', -1)">-</button>
            <span class="qty-value">${item.cantidad}</span>
            <button class="qty-btn" onclick="changeQty('${item.itemKey}', 1)">+</button>
            <button class="qty-btn remove" onclick="removeFromCart('${item.itemKey}')">🗑️</button>
          </div>
          <span class="cart-item-subtotal">$${subtotal.toFixed(2)}</span>
        </div>`;
    });

    itemsContainer.innerHTML = html;
    totalEl.textContent = `$${total.toFixed(2)}`;
  }

  function openCart() {
    updateCartUI();
    document.getElementById('cartOverlay').classList.add('active');
    document.getElementById('cartPanel').classList.add('active');
  }

  function closeCart() {
    document.getElementById('cartOverlay').classList.remove('active');
    document.getElementById('cartPanel').classList.remove('active');
  }

  document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
          const name = card.getAttribute('data-name') || '';
          card.style.display = name.includes(query) ? '' : 'none';
        });
      });
    }
  });
</script>
@endsection