@extends('layouts.app')

@section('title', 'Kitchen Display System (KDS)')

@section('content')
<div class="section" style="max-width: 100%; padding: 20px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div style="display: flex; align-items: center; gap: 12px;">
      <span style="font-size: 2.2rem;">📥</span>
      <div>
        <h1 class="section-title" style="margin: 0; font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: var(--accent-navy);">
          Consola de Preparación (KDS)
          <button class="help-btn-trigger" onclick="abrirAyuda('Consola de Cocina (KDS)', 'Esta pantalla interactiva está diseñada para baristas y cocineros. Los pedidos pendientes se ordenan por antigüedad y se actualizan automáticamente mediante polling corto. El semáforo de colores indica la urgencia (Verde < 8 min, Amarillo 8-12 min, Rojo > 12 min). Presiona el botón verde \'✅ Listo\' para despachar la comanda, lo que activará un sonido de campana y cambiará el estatus del pedido.')">❓</button>
        </h1>
        <p style="margin: 3px 0 0; font-size: 0.85rem; color: var(--text-secondary);">Pantalla en tiempo real para barra y baristas. Los pedidos se actualizan automáticamente.</p>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
      <span class="puntos-badge" style="background: var(--bg-card); border: 1px solid var(--border); color: var(--text-primary); font-size: 0.85rem; padding: 8px 16px;">
        🟢 Conectado en Tiempo Real (Polling)
      </span>
      <button class="btn-secondary" onclick="fetchPedidosKds()" style="padding: 8px 16px; font-size: 0.85rem; width: auto;">
        🔄 Forzar Refresco
      </button>
    </div>
  </div>

  <!-- Sonido de comanda -->
  <audio id="bellSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-120.wav" preload="auto"></audio>

  <!-- Contenedor del Tablero KDS -->
  <div id="kdsBoard" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; align-items: start;">
    <div style="text-align: center; grid-column: 1 / -1; padding: 80px 20px; color: var(--text-secondary);">
      <div style="font-size: 3rem; margin-bottom: 10px;">☕</div>
      <h3>Consultando comanda activa...</h3>
    </div>
  </div>
</div>

<style>
  .kds-card {
    background: #1b263b; /* Color oscuro bistró para barra */
    border-radius: var(--radius-lg);
    border: 2px solid #3e5c76;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
  }
  .kds-card.alerta {
    border-color: #ffd166;
    box-shadow: 0 8px 24px rgba(255,209,102,0.25);
  }
  .kds-card.critico {
    border-color: #ef476f;
    box-shadow: 0 8px 24px rgba(239,71,111,0.35);
    animation: pulseBorder 1.5s infinite alternate;
  }

  @keyframes pulseBorder {
    from { border-color: #ef476f; }
    to { border-color: #b7094c; }
  }

  .kds-header {
    padding: 14px 18px;
    background: #0d1b2a;
    border-bottom: 1px solid #3e5c76;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .kds-body {
    padding: 18px;
    flex: 1;
    color: #e0e1dd;
  }
  .kds-footer {
    padding: 12px 18px;
    background: #0d1b2a;
    border-top: 1px solid #3e5c76;
  }
  .kds-item-row {
    padding: 8px 0;
    border-bottom: 1px solid rgba(224,225,221,0.1);
  }
  .kds-item-row:last-child {
    border-bottom: none;
  }
  .kds-btn-done {
    width: 100%;
    padding: 12px;
    background: #c4a46a;
    border: none;
    border-radius: var(--radius-sm);
    color: #3a2a10;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .kds-btn-done:hover {
    background: #e2c07d;
  }
  .kds-btn-done:active {
    transform: scale(0.98);
  }
</style>
@endsection

@section('scripts')
<script>
  let previousOrdersCount = -1;

  async function fetchPedidosKds() {
    try {
      const response = await fetch('/admin/api/kds/pedidos');
      if (!response.ok) return;

      const pedidos = await response.json();
      
      // Reproducir sonido si entra una nueva comanda
      if (previousOrdersCount !== -1 && pedidos.length > previousOrdersCount) {
        const bell = document.getElementById('bellSound');
        if (bell) {
          bell.play().catch(err => console.log("Permiso de audio pendiente del comensal."));
        }
      }
      previousOrdersCount = pedidos.length;

      renderBoard(pedidos);
    } catch (err) {
      console.error("Error al refrescar KDS:", err);
    }
  }

  function renderBoard(pedidos) {
    const board = document.getElementById('kdsBoard');
    if (pedidos.length === 0) {
      board.innerHTML = `
        <div style="text-align: center; grid-column: 1 / -1; padding: 100px 20px; color: var(--text-secondary);">
          <div style="font-size: 4rem; margin-bottom: 15px;">🎉</div>
          <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--accent-navy); margin-bottom: 5px;">¡Felicidades! Barra al día</h2>
          <p>No quedan comandas ni pedidos pendientes de preparación.</p>
        </div>`;
      return;
    }

    let html = '';
    pedidos.forEach(p => {
      // Determinar clase de semaforización basada en minutos transcurridos
      let statusClass = '';
      let timerBadge = '';
      if (p.MinutosTranscurridos >= 12) {
        statusClass = 'critico';
        timerBadge = `<span style="background:#ef476f; color:white; font-size:0.75rem; padding:4px 8px; border-radius:99px; font-weight:bold;">⏱️ ${p.MinutosTranscurridos} min (CRÍTICO)</span>`;
      } else if (p.MinutosTranscurridos >= 8) {
        statusClass = 'alerta';
        timerBadge = `<span style="background:#ffd166; color:#3a2a10; font-size:0.75rem; padding:4px 8px; border-radius:99px; font-weight:bold;">⏱️ ${p.MinutosTranscurridos} min (ALERTA)</span>`;
      } else {
        timerBadge = `<span style="background:#28a745; color:white; font-size:0.75rem; padding:4px 8px; border-radius:99px; font-weight:bold;">⏱️ ${p.MinutosTranscurridos} min</span>`;
      }

      // Detalle de los ítems de comida
      let itemsHtml = '';
      p.items.forEach(item => {
        let modsHtml = '';
        if (item.Modificadores && item.Modificadores.length > 0) {
          const names = item.Modificadores.join(', ');
          modsHtml = `<div style="font-size:0.8rem; color:#c4a46a; font-style:italic; margin-top:2px;">↳ ${names}</div>`;
        }

        itemsHtml += `
          <div class="kds-item-row">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-weight:700; font-size:1.15rem;">${item.Cantidad}x ${item.ProductoNombre}</span>
            </div>
            ${modsHtml}
          </div>`;
      });

      html += `
        <div class="kds-card ${statusClass}" id="card-pedido-${p.PedidoId}">
          <div class="kds-header">
            <div>
              <span style="font-weight:bold; color:#f8f9fa; font-size:1.1rem;">#${p.Folio.substring(p.Folio.length - 6)}</span>
              <div style="font-size:0.75rem; color:#8d99ae; margin-top:2px;">Mesa: ${p.NumeroMesa ? p.NumeroMesa : 'Llevar'}</div>
            </div>
            ${timerBadge}
          </div>
          <div class="kds-body">
            ${itemsHtml}
          </div>
          <div class="kds-footer">
            <button class="kds-btn-done" onclick="completarPedido(${p.PedidoId})">
              ✅ Listo para Servir
            </button>
          </div>
        </div>`;
    });

    board.innerHTML = html;
  }

  async function completarPedido(pedidoId) {
    const card = document.getElementById(`card-pedido-${pedidoId}`);
    if (card) {
      card.style.opacity = '0.4';
      card.style.pointerEvents = 'none';
    }

    try {
      const response = await fetch(`/admin/kds/pedidos/${pedidoId}/completar`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      });

      const data = await response.json();
      if (response.ok && data.exito) {
        showToast(data.mensaje, 'success');
        // Refrescar inmediatamente
        fetchPedidosKds();
      } else {
        showToast(data.mensaje || 'Error al completar pedido.', 'error');
        if (card) {
          card.style.opacity = '1';
          card.style.pointerEvents = 'auto';
        }
      }
    } catch {
      showToast('Error al conectar con el servidor.', 'error');
      if (card) {
        card.style.opacity = '1';
        card.style.pointerEvents = 'auto';
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchPedidosKds();
    // Polling cada 5 segundos
    setInterval(fetchPedidosKds, 5000);
  });
</script>
@endsection
