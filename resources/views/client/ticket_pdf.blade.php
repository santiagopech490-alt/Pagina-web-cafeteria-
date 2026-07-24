<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ticket {{ $pedido->Folio }} - Café Parisien</title>
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
      width: 480px;
      padding: 36px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      border: 1px solid #e5e7eb;
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
    <button class="btn btn-navy" onclick="generarPDF()">📥 Descargar Ticket (PDF)</button>
    <a href="/MisPedidos" class="btn btn-gold">⬅️ Volver a Mis Pedidos</a>
  </div>

  <div id="ticket-pdf-content" class="ticket-card">
    <div style="text-align: center; border-bottom: 2px dashed #9ca3af; padding-bottom: 18px; margin-bottom: 18px;">
      <h1 style="margin: 0; font-size: 26px; color: #1b2a4a; font-family: Georgia, serif;">⚜️ CAFÉ PARISIEN ⚜️</h1>
      <p style="margin: 4px 0; font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1.5px; font-weight: bold;">L'Élégance - Comprobante Digital</p>
      <div style="margin-top: 12px; font-size: 13px; color: #374151; background: #f9fafb; padding: 10px; border-radius: 6px; border: 1px solid #f3f4f6;">
        <strong>Folio:</strong> {{ $pedido->Folio }}<br/>
        <strong>Estatus:</strong> {{ $pedido->EstadoId == 1 ? 'En Cocina' : ($pedido->EstadoId == 2 ? 'Listo' : 'Entregado') }}
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
        @foreach ($detalles as $det)
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 10px 0; font-size: 14px; color: #111827;"><strong>{{ $det->Nombre }}</strong> x{{ $det->Cantidad }}</td>
            <td style="padding: 10px 0; font-size: 14px; text-align: right; font-weight: bold; color: #111827;">${{ number_format($det->Precio * $det->Cantidad, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div style="border-top: 2px solid #1b2a4a; padding-top: 14px; margin-bottom: 20px;">
      <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #1b2a4a; border-top: 2px solid #e5e7eb; padding-top: 10px;">
        <span>TOTAL COMPRA:</span>
        <span>${{ number_format($pedido->Total, 2) }}</span>
      </div>
    </div>

    <div style="background: #f3f4f6; padding: 14px; border-radius: 8px; font-size: 13px; color: #374151; border: 1px solid #e5e7eb; margin-bottom: 20px;">
      <strong>Forma de Entrega:</strong> {{ $pedido->MetodoEntregaId == 1 ? 'Para Llevar' : ($pedido->MetodoEntregaId == 2 ? 'Consumo en Mesa' : 'A Domicilio') }}
      @if ($pedido->NumeroMesa) <br/><strong>Mesa:</strong> Mesa {{ $pedido->NumeroMesa }} @endif
      @if ($pedido->Direccion) <br/><strong>Dirección:</strong> {{ $pedido->Direccion }} @endif
      @if ($pedido->Notas) <br/><strong>Notas / Tel:</strong> {{ $pedido->Notas }} @endif
    </div>

    <!-- Código QR de Verificación de Compra -->
    <div style="text-align: center; margin: 18px 0; padding: 12px; background: #ffffff; border: 2px dashed #1b2a4a; border-radius: 8px;">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data={{ urlencode('CAFEPARISIEN|COMPRA|FOLIO:' . $pedido->Folio . '|TOTAL:$' . number_format($pedido->Total, 2)) }}" alt="Código QR Ticket" style="width: 130px; height: 130px; border-radius: 4px;" />
      <div style="font-size: 11px; color: #1b2a4a; margin-top: 4px; font-weight: bold; font-family: monospace;">
        FOLIO QR: {{ $pedido->Folio }}
      </div>
    </div>

    <div style="text-align: center; font-size: 12px; color: #6b7280; font-style: italic; border-top: 1px dashed #d1d5db; padding-top: 14px;">
      ¡Merci beaucoup pour votre visite! 🥖☕<br/>
      Café Parisien - Conserve este comprobante para cualquier aclaración.
    </div>
  </div>

  <script>
    function generarPDF() {
      const element = document.getElementById('ticket-pdf-content');
      const opt = {
        margin:       [10, 10, 10, 10],
        filename:     'Ticket_CafeParisien_{{ $pedido->Folio }}.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };
      html2pdf().set(opt).from(element).save();
    }

    // Disparar descarga automática al cargar
    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(generarPDF, 500);
    });
  </script>
</body>
</html>
