@extends('layouts.app')

@section('title', 'Corte de Caja Z')

@section('content')
<div class="section">
  <div class="section-header">
    <span class="section-icon">💰</span>
    <div>
      <h1 class="section-title">
        Corte de Caja (Cierre Z)
        <button class="help-btn-trigger" onclick="abrirAyuda('Corte de Caja (Cierre Z)', 'Este apartado permite realizar el arqueo financiero y cierre Z al final del día. Se trata de un arqueo ciego donde debes contar y declarar el efectivo físico en caja. El sistema auditará la cifra contra las ventas reales del día en busca de sobrantes o faltantes. Al registrar el cierre, se inhabilitan nuevas transacciones extemporáneas.')">❓</button>
      </h1>
      <p class="section-desc">Realice el arqueo de caja diario declarando el efectivo en caja para cerrar la jornada fiscal.</p>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #28a745; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚜️ {{ session('success') }}
    </div>
  @endif

  @if (isset($errors) && $errors->any())
    <div style="background: rgba(220, 53, 69, 0.1); border: 1px solid var(--accent-err); color: var(--accent-err); padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: bold; font-size: 0.95rem;">
      ⚠️ {{ $errors->first() }}
    </div>
  @endif

  <div style="display: flex; gap: 30px; flex-wrap: wrap;">
    <!-- Columna Izquierda: Formulario de Arqueo -->
    <div style="flex: 1.2; min-width: 320px;">
      <div class="card" style="padding: 30px;">
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">⚜️ Declaración de Efectivo Físico</h2>
        
        <form action="/admin/corte-caja" method="POST" onsubmit="confirmarCorte(event)">
          @csrf
          <input type="hidden" name="efectivo_esperado" value="{{ $totalEfectivo }}" />

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px;">1. Conteo de Efectivo Físico en Caja ($):</label>
            <input type="number" step="0.01" name="efectivo_declarado" id="efectivo_declarado" placeholder="Ej. 1500.00" required style="width: 100%; padding: 14px 18px; border: 2px solid var(--border); border-radius: var(--radius-md); font-size: 1.2rem; font-weight: bold; background: var(--bg-card); color: var(--text-primary); outline: none; transition: border-color 0.25s;" />
            <small style="display: block; color: var(--text-muted); margin-top: 6px; font-size: 0.8rem; font-style: italic;">Ingrese el total neto contado en la gaveta física (arqueo a ciegas).</small>
          </div>

          <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary" style="width: 100%; padding: 16px; font-size: 1.1rem; font-weight: bold;">
              🔐 Confirmar y Realizar Cierre Z
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Columna Derecha: Resumen de Transacciones de Hoy -->
    <div style="flex: 1; min-width: 300px;">
      <div class="card" style="padding: 30px; background: var(--bg-secondary); border: 1px solid var(--border);">
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--accent-navy); margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 10px;">📊 Ventas del Día</h2>
        
        <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 15px;">
          <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
            <span style="color: var(--text-secondary);">Pedidos Procesados:</span>
            <span style="font-weight: bold; color: var(--text-primary);">{{ DB::table('facturas')->count() }}</span>
          </div>
          
          <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
            <span style="color: var(--text-secondary);">Total Ventas Acumulado:</span>
            <span style="font-weight: bold; color: var(--text-primary);">$ {{ number_format($totalVentas, 2) }}</span>
          </div>

          <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
            <span style="color: var(--text-secondary);">Descuentos de Cupones:</span>
            <span style="font-weight: bold; color: var(--accent-err);">- $ {{ number_format($descuentosAplicados, 2) }}</span>
          </div>

          <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700; border-top: 2px solid var(--border); padding-top: 14px; margin-top: 5px;">
            <span style="color: var(--accent-navy);">Efectivo Esperado:</span>
            <span style="color: var(--accent-gold-dark); font-family: 'Cormorant Garamond', serif; font-size: 1.45rem;">$ {{ number_format($totalEfectivo, 2) }}</span>
          </div>
        </div>

        <div style="margin-top: 25px; padding: 12px; background: rgba(196,164,106,0.1); border-left: 3px solid var(--accent-gold); border-radius: var(--radius-sm); font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">
          <strong>Nota de Auditoría:</strong> El cierre Z inhabilitará la facturación por el resto de la fecha actual y guardará una entrada en la bitácora de logs.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmarCorte(e) {
    if (!confirm("⚠️ ¿Está seguro de realizar el cierre Z? Esta acción inhabilitará nuevas ventas por el resto del día de hoy.")) {
      e.preventDefault();
    }
  }
</script>
@endsection
