<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Boleto de Reservación #RES-{{ $reservacion->ReservacionId }} - Café Parisien</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 40px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .ticket-card {
      background: #ffffff;
      width: 500px;
      padding: 36px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      border: 2px solid #c4a46a;
    }
    .btn {
      display: inline-block;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
      cursor: pointer;
      font-size: 0.95rem;
      border: none;
    }
    .btn-gold { background: #c4a46a; color: #ffffff; }
    .btn-navy { background: #1b2a4a; color: #ffffff; }
  </style>
</head>
<body>

  <div style="margin-bottom: 20px; display: flex; gap: 12px;">
    <button class="btn btn-navy" onclick="generarBoletoPDF()">📥 Descargar Boleto (PDF)</button>
    <a href="/Reservar" class="btn btn-gold">⬅️ Volver a Reservaciones</a>
  </div>

  <div id="boleto-pdf-content" class="ticket-card">
    <div style="text-align: center; border-bottom: 2px dashed #c4a46a; padding-bottom: 18px; margin-bottom: 20px;">
      <h1 style="margin: 0; font-size: 26px; color: #1b2a4a; font-family: Georgia, serif;">⚜️ CAFÉ PARISIEN ⚜️</h1>
      <p style="margin: 4px 0; font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1.5px; font-weight: bold;">🎫 BOLETO DIGITAL DE RESERVACIÓN</p>
      <div style="margin-top: 12px; font-size: 14px; color: #1b2a4a; background: rgba(196, 164, 106, 0.15); padding: 10px; border-radius: 6px; border: 1px solid #c4a46a; font-weight: bold;">
        Folio de Reserva: #RES-{{ $reservacion->ReservacionId }}
      </div>
    </div>

    <div style="margin-bottom: 20px; font-size: 14px; line-height: 1.8; color: #374151;">
      <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f3f4f6; padding: 8px 0;">
        <span style="color: #6b7280;">Titular de la Reserva:</span>
        <strong style="color: #111827;">{{ $reservacion->NombreCliente }}</strong>
      </div>
      <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f3f4f6; padding: 8px 0;">
        <span style="color: #6b7280;">Fecha y Hora Programada:</span>
        <strong style="color: #1b2a4a;">{{ date('d/m/Y \a \l\a\s H:i \h\r\s', strtotime($reservacion->FechaHora)) }}</strong>
      </div>
      <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f3f4f6; padding: 8px 0;">
        <span style="color: #6b7280;">Mesa Asignada:</span>
        <strong style="color: #111827;">{{ $mesa->NumeroMesa ?? ('Mesa ' . $reservacion->MesaId) }}</strong>
      </div>
      <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f3f4f6; padding: 8px 0;">
        <span style="color: #6b7280;">Área / Zona:</span>
        <strong style="color: #1b2a4a;">{{ $mesa->IconoZona ?? '🏛️' }} {{ $mesa->NombreZona ?? 'Salón Principal' }}</strong>
      </div>
      <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f3f4f6; padding: 8px 0;">
        <span style="color: #6b7280;">Capacidad:</span>
        <strong style="color: #111827;">Hasta {{ $mesa->Capacidad ?? 4 }} personas</strong>
      </div>
      @if (isset($mesa->Ubicacion) && $mesa->Ubicacion)
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f3f4f6; padding: 8px 0;">
          <span style="color: #6b7280;">Ubicación Específica:</span>
          <span style="color: #4b5563; font-style: italic;">{{ $mesa->Ubicacion }}</span>
        </div>
      @endif
      <div style="display: flex; justify-content: space-between; padding: 8px 0; font-weight: bold;">
        <span style="color: #6b7280;">Estatus de Reserva:</span>
        <span style="color: #059669; background: #ecfdf5; padding: 2px 10px; border-radius: 99px; font-size: 13px; border: 1px solid #a7f3d0;">
          🟢 {{ $reservacion->Estado ?? 'CONFIRMADA' }}
        </span>
      </div>
    </div>

    <!-- Código QR de Validación -->
    <div style="text-align: center; margin: 18px 0; padding: 14px; background: #ffffff; border: 2px dashed #c4a46a; border-radius: 8px;">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode('CAFEPARISIEN|RESERVACION|RES-' . $reservacion->ReservacionId . '|' . $reservacion->NombreCliente . '|Mesa:' . ($mesa->NumeroMesa ?? $reservacion->MesaId) . '|' . date('Y-m-d H:i', strtotime($reservacion->FechaHora))) }}" alt="Código QR de Validación" style="width: 140px; height: 140px; border-radius: 4px;" />
      <div style="font-size: 11px; color: #1b2a4a; margin-top: 6px; font-weight: bold; font-family: monospace;">
        SCANNER ID: RES-{{ $reservacion->ReservacionId }}
      </div>
      <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">Escanee este código QR al llegar al establecimiento</div>
    </div>

    <div style="background: #f9fafb; padding: 14px; border-radius: 8px; font-size: 12px; color: #4b5563; border: 1px solid #e5e7eb; margin-bottom: 20px; text-align: center; line-height: 1.5;">
      📌 <strong>Aviso Importante:</strong> Por favor presente este boleto digital o impreso a su llegada con el capitán de meseros. Contamos con una tolerancia de 15 minutos en horario pico.
    </div>

    <div style="text-align: center; font-size: 12px; color: #6b7280; font-style: italic; border-top: 1px dashed #c4a46a; padding-top: 14px;">
      ¡Merci beaucoup! Le esperamos con los brazos abiertos. 🥖☕<br/>
      Café Parisien - L'Élégance Et Le Goût
    </div>
  </div>

  <script>
    function generarBoletoPDF() {
      const element = document.getElementById('boleto-pdf-content');
      const opt = {
        margin:       [10, 10, 10, 10],
        filename:     'Boleto_Reservacion_CafeParisien_RES-{{ $reservacion->ReservacionId }}.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };
      html2pdf().set(opt).from(element).save();
    }

    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(generarBoletoPDF, 500);
    });
  </script>
</body>
</html>
