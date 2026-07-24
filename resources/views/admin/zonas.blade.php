@extends('layouts.app')

@section('title', 'Mesas & Reservas - Café Parisien')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">🪑</span>
    <div>
      <h1 class="section-title">
        Mesas & Reservas
        <button class="help-btn-trigger" onclick="abrirAyuda('Mesas y Reservas', 'Módulo integral de control del salón. Visualice en tiempo real el mapa interactivo de mesas organizadas por zonas, consulte las reservaciones activas de los clientes, cambie el estatus de las mesas (Disponibles, Ocupadas, Reservadas) y gestione la distribución del restaurante.')">❓</button>
      </h1>
      <p class="section-desc">Mapa interactivo de mesas en tiempo real, control de ocupación y gestión de reservaciones activas.</p>
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

  <!-- Métricas Rápidas del Salón -->
  <div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
      <div class="stat-icon-wrap purple">🪑</div>
      <div>
        <div class="stat-value">{{ $totalMesas }}</div>
        <div class="stat-label">Total de Mesas</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap orange" style="background: rgba(40,167,69,0.15); color: #28a745;">🟢</div>
      <div>
        <div class="stat-value" style="color: #28a745;">{{ $mesasDisponibles }}</div>
        <div class="stat-label">Mesas Disponibles</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap orange" style="background: rgba(255,193,7,0.15); color: #b7791f;">🟡</div>
      <div>
        <div class="stat-value" style="color: #b7791f;">{{ $mesasReservadas }}</div>
        <div class="stat-label">Mesas Reservadas</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap orange" style="background: rgba(220,53,69,0.15); color: var(--accent-err);">🔴</div>
      <div>
        <div class="stat-value" style="color: var(--accent-err);">{{ $mesasOcupadas }}</div>
        <div class="stat-label">Mesas Ocupadas</div>
      </div>
    </div>
  </div>

  <!-- Botones de Acción para Crear Zonas/Mesas -->
  <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
    <button class="btn-primary" onclick="toggleForm('form-nueva-zona')" style="width: auto; padding: 10px 20px; font-size: 0.9rem; background: var(--accent-navy);">
      ➕ Crear Nueva Zona / Área
    </button>
    <button class="btn-primary" onclick="toggleForm('form-nueva-mesa')" style="width: auto; padding: 10px 20px; font-size: 0.9rem; background: var(--accent-gold-dark);">
      🪑 Añadir Mesa al Mapa
    </button>
  </div>

  <!-- Formulario Nueva Zona -->
  <div id="form-nueva-zona" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-navy);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">🏛️ Crear Nueva Zona del Restaurante</h3>
    <form action="/admin/zonas/crear" method="POST">
      @csrf
      <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 15px; margin-bottom: 15px;">
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Nombre de Zona</label>
          <input type="text" name="nombre" class="search-input" placeholder="Ej. Terraza VIP" required style="width: 100%; border: 1px solid var(--border);">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Descripción</label>
          <input type="text" name="descripcion" class="search-input" placeholder="Ej. Área con pérgola y calentadores..." style="width: 100%; border: 1px solid var(--border);">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Icono (Emoji)</label>
          <input type="text" name="icono" class="search-input" placeholder="Ej. 🌿" style="width: 100%; border: 1px solid var(--border);">
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Guardar Zona</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nueva-zona')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <!-- Formulario Nueva Mesa -->
  <div id="form-nueva-mesa" class="card" style="display: none; padding: 25px; margin-bottom: 25px; border-left: 4px solid var(--accent-gold);">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: var(--accent-navy); margin-bottom: 15px;">🪑 Añadir Mesa al Mapa</h3>
    <form action="/admin/mesas/crear" method="POST">
      @csrf
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Número / Identificador de Mesa</label>
          <input type="text" name="numeroMesa" class="search-input" placeholder="Ej. Mesa 12" required style="width: 100%; border: 1px solid var(--border);">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Zona Asignada</label>
          <select name="zonaId" class="search-input" required style="width: 100%; border: 1px solid var(--border);">
            @foreach ($zonas as $z)
              <option value="{{ $z->ZonaId }}">{{ $z->Icono }} {{ $z->Nombre }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Capacidad (Personas)</label>
          <input type="number" name="capacidad" min="1" value="4" required class="search-input" style="width: 100%; border: 1px solid var(--border);">
        </div>
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Ubicación Específica</label>
          <input type="text" name="ubicacion" class="search-input" placeholder="Ej. Junto a la ventana principal" style="width: 100%; border: 1px solid var(--border);">
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 8px 18px;">Añadir Mesa</button>
        <button type="button" class="btn-secondary" onclick="toggleForm('form-nueva-mesa')" style="width: auto; padding: 8px 18px;">Cancelar</button>
      </div>
    </form>
  </div>

  <!-- 🗺️ MAPA INTERACTIVO DE MESAS DEL RESTAURANTE -->
  <div class="card" style="padding: 30px; margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 12px; flex-wrap: wrap; gap: 15px;">
      <div>
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--accent-navy); margin: 0;">
          🗺️ Mapa Interactivo del Salón y Ocupación
        </h2>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 4px 0 0 0;">
          Pase el cursor sobre cualquier mesa para consultar detalles o cambiar su estado en tiempo real.
        </p>
      </div>

      <!-- Filtro por Zona -->
      <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <button class="btn-primary btn-filtro-mapa active" onclick="filtrarMapaZona('todas')" id="btn-filtro-todas" style="padding: 6px 14px; font-size: 0.8rem; width: auto; background: var(--accent-navy);">
          Todas las Zonas
        </button>
        @foreach ($zonas as $z)
          <button class="btn-secondary btn-filtro-mapa" onclick="filtrarMapaZona('zona-{{ $z->ZonaId }}')" id="btn-filtro-zona-{{ $z->ZonaId }}" style="padding: 6px 14px; font-size: 0.8rem; width: auto;">
            {{ $z->Icono }} {{ $z->Nombre }}
          </button>
        @endforeach
      </div>
    </div>

    <!-- Grid de Mesas del Mapa -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
      @foreach ($mesas as $m)
        @php
          $bgCard = '#ffffff';
          $borderColor = '#28a745';
          $statusBadgeBg = 'rgba(40,167,69,0.1)';
          $statusBadgeColor = '#28a745';
          $statusText = '🟢 Desocupada / Disponible';

          if ($m->estadoEfectivo == 'Reservada') {
            $borderColor = '#b7791f';
            $statusBadgeBg = 'rgba(255,193,7,0.15)';
            $statusBadgeColor = '#b7791f';
            $statusText = '🟡 Ocupada / Con Reserva';
          } elseif ($m->estadoEfectivo == 'Ocupada') {
            $borderColor = 'var(--accent-err)';
            $statusBadgeBg = 'rgba(220,53,69,0.1)';
            $statusBadgeColor = 'var(--accent-err)';
            $statusText = '🔴 Ocupada / En Servicio';
          }
        @endphp

        <div class="card card-mesa-item zona-item-card zona-{{ $m->ZonaId }}" style="padding: 18px; border: 2px solid {{ $borderColor }}; border-radius: var(--radius-md); background: {{ $bgCard }}; position: relative; transition: all 0.25s ease-in-out;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
            <div style="font-weight: bold; font-size: 1.1rem; color: var(--accent-navy);">
              {{ $m->IconoZona ?? '🪑' }} {{ $m->NumeroMesa }}
            </div>
            <span style="font-size: 0.72rem; font-weight: bold; background: {{ $statusBadgeBg }}; color: {{ $statusBadgeColor }}; border: 1px solid {{ $borderColor }}; padding: 3px 8px; border-radius: 99px;">
              {{ $statusText }}
            </span>
          </div>

          <div style="font-size: 0.85rem; color: var(--text-primary); line-height: 1.6; margin-bottom: 12px;">
            <div><strong>Zona:</strong> {{ $m->NombreZona ?? 'Sin zona asignada' }}</div>
            <div><strong>Capacidad:</strong> 👥 {{ $m->Capacidad }} personas</div>
            @if ($m->Ubicacion)
              <div style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">📍 {{ $m->Ubicacion }}</div>
            @endif
          </div>

          <!-- Si tiene Reservación Activa -->
          @if ($m->reservacionActiva)
            <div style="background: rgba(255,193,7,0.12); border: 1px solid rgba(255,193,7,0.3); border-radius: var(--radius-sm); padding: 10px; font-size: 0.8rem; margin-bottom: 12px; color: #744210;">
              <strong>🎫 Reserva Activa:</strong> #RES-{{ $m->reservacionActiva->ReservacionId }}<br/>
              <strong>Cliente:</strong> {{ $m->reservacionActiva->NombreCliente }}<br/>
              <strong>Cita:</strong> 🕒 {{ date('d/m/Y H:i', strtotime($m->reservacionActiva->FechaHora)) }} hrs
            </div>
          @endif

          <!-- Selector de Estado de la Mesa -->
          <div style="border-top: 1px dashed var(--border); padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.75rem; font-weight: bold; color: var(--text-secondary);">Cambiar Estatus:</span>
            <form action="/admin/mesas/{{ $m->MesaId }}/estado" method="POST" style="display: flex; gap: 6px;">
              @csrf
              <select name="estado" onchange="this.form.submit()" style="padding: 4px 8px; font-size: 0.76rem; border-radius: var(--radius-sm); border: 1px solid var(--border); font-weight: bold; background: var(--bg-card); color: var(--text-primary);">
                <option value="Disponible" {{ $m->Estado == 'Disponible' ? 'selected' : '' }}>🟢 Disponible</option>
                <option value="Ocupada" {{ $m->Estado == 'Ocupada' ? 'selected' : '' }}>🔴 Ocupada</option>
                <option value="Reservada" {{ $m->Estado == 'Reservada' ? 'selected' : '' }}>🟡 Reservada</option>
              </select>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 📋 TABLA DE RESERVACIONES ACTIVAS -->
  <div class="card" style="padding: 30px;">
    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <span>📅 Listado de Reservaciones Activas</span>
      <span style="font-size: 0.85rem; background: rgba(196,164,106,0.15); color: var(--accent-gold-dark); border: 1px solid var(--accent-gold); padding: 4px 12px; border-radius: 99px; font-weight: bold;">
        {{ count($reservacionesActivas) }} Reservas Confirmadas
      </span>
    </h2>

    <div class="table-wrap">
      <table aria-label="Tabla de Reservaciones Activas" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--bg-secondary);">
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Folio Reserva</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Nombre del Cliente</th>
            <th style="padding: 14px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Mesa / Zona</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Fecha y Hora Programada</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Estatus</th>
            <th style="padding: 14px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--accent-gold-dark);">Acciones (Boleto PDF / Liberar)</th>
          </tr>
        </thead>
        <tbody>
          @if ($reservacionesActivas->isEmpty())
            <tr>
              <td colspan="6" style="text-align: center; padding: 40px; opacity: 0.5;">No hay reservaciones activas programadas en este momento.</td>
            </tr>
          @else
            @foreach ($reservacionesActivas as $res)
              <tr style="border-bottom: 1px solid var(--border); background: var(--bg-card); font-size: 0.92rem;">
                <td style="padding: 16px 14px; font-weight: bold; color: var(--accent-navy);">
                  #RES-{{ $res->ReservacionId }}
                </td>
                <td style="padding: 16px 14px; font-weight: 600; color: var(--accent-navy);">
                  👤 {{ $res->NombreCliente }}
                </td>
                <td style="padding: 16px 14px; font-weight: 500;">
                  {{ $res->IconoZona ?? '🪑' }} Mesa {{ $res->NumeroMesa }} — <span style="color: var(--text-muted); font-size: 0.85rem;">{{ $res->NombreZona ?? 'Salón' }}</span>
                </td>
                <td style="padding: 16px 14px; text-align: center; font-weight: bold; color: var(--accent-navy);">
                  🕒 {{ date('d/m/Y \a \l\a\s H:i \h\r\s', strtotime($res->FechaHora)) }}
                </td>
                <td style="padding: 16px 14px; text-align: center;">
                  <span style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.25); padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: bold;">
                    🟢 {{ $res->Estado }}
                  </span>
                </td>
                <td style="padding: 16px 14px; text-align: center; display: flex; gap: 8px; justify-content: center;">
                  <a href="/reservaciones/{{ $res->ReservacionId }}/descargar-boleto" target="_blank" class="btn-primary" style="padding: 6px 12px; font-size: 0.78rem; text-decoration: none; width: auto; background: var(--accent-navy);">
                    📥 Boleto PDF
                  </a>
                  <form action="/admin/reservaciones/{{ $res->ReservacionId }}/cancelar" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-primary" onclick="return confirm('¿Está seguro de cancelar / liberar la reservación #RES-{{ $res->ReservacionId }}?')" style="padding: 6px 12px; font-size: 0.78rem; width: auto; background: var(--accent-err); border: 1px solid var(--accent-err);">
                      ❌ Liberar Mesa
                    </button>
                  </form>
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

  function filtrarMapaZona(claseZona) {
    const items = document.querySelectorAll('.card-mesa-item');
    const btns = document.querySelectorAll('.btn-filtro-mapa');

    btns.forEach(b => {
      b.classList.remove('active');
      b.style.background = '';
      b.style.color = '';
    });

    const btnClick = document.getElementById('btn-filtro-' + claseZona);
    if (btnClick) {
      btnClick.classList.add('active');
      btnClick.style.background = 'var(--accent-navy)';
      btnClick.style.color = '#ffffff';
    }

    items.forEach(item => {
      if (claseZona === 'todas' || item.classList.contains(claseZona)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }
</script>
@endsection
