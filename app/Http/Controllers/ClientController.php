<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Mesa;
use App\Models\Reservacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function landing()
    {
        // Obtener 3 productos destacados para la landing page
        $destacados = Producto::where('Disponible', 1)->limit(3)->get();
        return view('client.landing', compact('destacados'));
    }

    public function index()
    {
        $categorias = Categoria::orderBy('CategoriaId')->get();
        $productos = Producto::where('Disponible', 1)->get();
        
        $productosPorCategoria = $productos->groupBy('CategoriaId');
        
        // Cargar modificadores asociados a los productos
        $modificadoresRaw = DB::table('productomodificadores')
            ->join('tiposmodificador', 'productomodificadores.TipoId', '=', 'tiposmodificador.TipoId')
            ->join('opcionesmodificador', 'tiposmodificador.TipoId', '=', 'opcionesmodificador.TipoId')
            ->select(
                'productomodificadores.ProductoId', 
                'tiposmodificador.TipoId', 
                'tiposmodificador.Nombre as TipoNombre', 
                'opcionesmodificador.OpcionId', 
                'opcionesmodificador.Nombre as OpcionNombre'
            )
            ->get();
            
        $modificadoresMap = [];
        foreach ($modificadoresRaw as $m) {
            if (!isset($modificadoresMap[$m->ProductoId])) {
                $modificadoresMap[$m->ProductoId] = [];
            }
            if (!isset($modificadoresMap[$m->ProductoId][$m->TipoId])) {
                $modificadoresMap[$m->ProductoId][$m->TipoId] = [
                    'TipoId' => $m->TipoId,
                    'Nombre' => $m->TipoNombre,
                    'Opciones' => []
                ];
            }
            $modificadoresMap[$m->ProductoId][$m->TipoId]['Opciones'][] = [
                'OpcionId' => $m->OpcionId,
                'Nombre' => $m->OpcionNombre
            ];
        }
        
        return view('client.index', compact('categorias', 'productosPorCategoria', 'modificadoresMap'));
    }

    public function showCart()
    {
        $puntosDisponibles = 0;
        if (auth()->check()) {
            $p = DB::table('puntoscliente')->where('UsuarioId', auth()->id())->first();
            $puntosDisponibles = $p ? $p->Puntos : 0;
        }
        $metodosEntrega = DB::table('metodosentrega')->get();
        $mesas = Mesa::all();
        $metodosPago = DB::table('metodospago')->where('Activo', 1)->get();
        return view('client.cart', compact('puntosDisponibles', 'metodosEntrega', 'mesas', 'metodosPago'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'metodoPago' => 'required|string',
            'entrega' => 'required|string',
        ]);

        // VALIDACIÓN: Verificar si el día de hoy ya tiene Corte Z cerrado
        $ultimoCorte = DB::table('configuracionsistema')->where('Clave', 'ULTIMO_CORTE_Z')->first();
        if ($ultimoCorte && $ultimoCorte->Valor === now()->toDateString()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'La caja del día de hoy ya se encuentra cerrada (Corte Z realizado). No se permiten nuevas compras.'
            ], 400);
        }

        $items = $request->input('items');
        $metodoPago = $request->input('metodoPago');
        $entrega = $request->input('entrega');
        $numeroMesa = $request->input('numeroMesa');
        $direccion = $request->input('direccion');
        $telefono = $request->input('telefono');
        $cuponCodigo = $request->input('cupon');
        $usarPuntos = $request->input('usarPuntos', false);

        $subtotal = 0;
        $detalles = [];

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                $producto = Producto::where('Codigo', $item['codigo'])->lockForUpdate()->first();
                if (!$producto) {
                    throw new \Exception("Producto no encontrado: " . $item['codigo']);
                }

                // Buscar si el producto tiene receta (BOM) configurada en la tabla recetas
                $receta = DB::table('recetas')->where('ProductoId', $producto->ProductoId)->get();

                if ($receta->isNotEmpty()) {
                    // Descuenta existencias de los insumos proporcionales de la receta
                    foreach ($receta as $ingrediente) {
                        $insumo = Producto::where('ProductoId', $ingrediente->InsumoProductoId)->lockForUpdate()->first();
                        $cantidadRequeridaTotal = floatval($ingrediente->CantidadRequerida) * intval($item['cantidad']);

                        if (!$insumo || $insumo->Existencia < $cantidadRequeridaTotal) {
                            $insumoNombre = $insumo ? $insumo->Nombre : 'materia prima';
                            throw new \Exception("Insumos insuficientes de {$insumoNombre} para preparar: " . $producto->Nombre);
                        }

                        $insumo->Existencia -= $cantidadRequeridaTotal;
                        $insumo->save();
                    }
                } else {
                    // Descuento tradicional de producto final (repostería ya elaborada, botellas, etc.)
                    if ($producto->Existencia < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para: " . $producto->Nombre);
                    }
                    $producto->Existencia -= $item['cantidad'];
                    $producto->save();
                }

                $itemSubtotal = $producto->Precio * $item['cantidad'];
                $subtotal += $itemSubtotal;

                $detalles[] = [
                    'ProductoId' => $producto->ProductoId,
                    'Cantidad' => $item['cantidad'],
                    'PrecioUnitario' => $producto->Precio,
                    'Subtotal' => $itemSubtotal,
                    '_modificadores' => isset($item['modificadores']) ? $item['modificadores'] : [],
                ];
            }

            $descuento = 0;
            if ($cuponCodigo) {
                $cupon = DB::table('cupones')->where('Codigo', $cuponCodigo)->where('Activo', 1)->first();
                if ($cupon) {
                    if ($cupon->TipoDescuento == 'PORCENTAJE') {
                        $descuento = $subtotal * ($cupon->ValorDescuento / 100.0);
                    } else {
                        $descuento = $cupon->ValorDescuento;
                    }
                    DB::table('cupones')->where('CuponId', $cupon->CuponId)->increment('UsosActuales');
                }
            }

            $puntosCanjeados = 0;
            $descuentoPuntos = 0;
            if ($usarPuntos && auth()->check()) {
                $puntosCliente = DB::table('puntoscliente')->where('UsuarioId', auth()->id())->first();
                if ($puntosCliente && $puntosCliente->Puntos > 0) {
                    $maxDescuentoPosible = $subtotal - $descuento;
                    $puntosRequeridos = intval($maxDescuentoPosible);
                    
                    if ($puntosCliente->Puntos >= $puntosRequeridos) {
                        $puntosCanjeados = $puntosRequeridos;
                        $descuentoPuntos = $puntosRequeridos * 1.0;
                    } else {
                        $puntosCanjeados = $puntosCliente->Puntos;
                        $descuentoPuntos = $puntosCanjeados * 1.0;
                    }

                    DB::table('puntoscliente')->where('UsuarioId', auth()->id())->decrement('Puntos', $puntosCanjeados);
                    
                    DB::table('transaccionespuntos')->insert([
                        'UsuarioId' => auth()->id(),
                        'Puntos' => -$puntosCanjeados,
                        'TipoMovimiento' => 'CANJE_PEDIDO',
                        'FechaMovimiento' => now()
                    ]);
                }
            }

            $total = $subtotal - $descuento - $descuentoPuntos;
            if ($total < 0) $total = 0;

            $iva = $total * 0.16;

            $folio = 'FAC-' . strtoupper(uniqid());

            $metodoId = 3; // Default LLEVAR
            if ($entrega == 'MESA') $metodoId = 2;
            if ($entrega == 'DOMICILIO') $metodoId = 4;

            $cuponIdVal = null;
            if ($cuponCodigo) {
                $cupon = DB::table('cupones')->where('Codigo', $cuponCodigo)->where('Activo', 1)->first();
                if ($cupon) {
                    $cuponIdVal = $cupon->CuponId;
                }
            }

            $pedidoId = DB::table('pedidos')->insertGetId([
                'UsuarioId' => auth()->check() ? auth()->id() : null,
                'Folio' => $folio,
                'EstadoId' => 1, // RECIBIDO
                'Total' => $total,
                'MetodoEntregaId' => $metodoId,
                'Direccion' => $entrega == 'DOMICILIO' ? $direccion : null,
                'NumeroMesa' => $entrega == 'MESA' ? $numeroMesa : null,
                'Notas' => $entrega == 'DOMICILIO' ? "Tel: " . $telefono : null,
                'CuponId' => $cuponIdVal,
            ]);

            foreach ($detalles as $det) {
                $modificadoresItem = $det['_modificadores'];
                
                $detalleId = DB::table('detallespedido')->insertGetId([
                    'PedidoId' => $pedidoId,
                    'ProductoId' => $det['ProductoId'],
                    'Cantidad' => $det['Cantidad']
                ]);
                
                foreach ($modificadoresItem as $m) {
                    DB::table('detallesmodificadores')->insert([
                        'DetalleId' => $detalleId,
                        'OpcionId' => $m['opcionId']
                    ]);
                }
            }

            $facturaId = DB::table('facturas')->insertGetId([
                'PedidoId' => $pedidoId,
                'Folio' => $folio,
                'Total' => $total,
            ]);

            if (auth()->check() && $metodoPago != 'PUNTOS') {
                $puntosGanados = intval($total / 10);
                if ($puntosGanados > 0) {
                    $existePuntos = DB::table('puntoscliente')->where('UsuarioId', auth()->id())->first();
                    if ($existePuntos) {
                        DB::table('puntoscliente')->where('UsuarioId', auth()->id())->increment('Puntos', $puntosGanados);
                    } else {
                        DB::table('puntoscliente')->insert([
                            'UsuarioId' => auth()->id(),
                            'Puntos' => $puntosGanados
                        ]);
                    }

                    DB::table('transaccionespuntos')->insert([
                        'UsuarioId' => auth()->id(),
                        'Puntos' => $puntosGanados,
                        'TipoMovimiento' => 'COMPRA_ACUMULA',
                        'FechaMovimiento' => now()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'exito' => true,
                'mensaje' => '¡Su pedido ha sido procesado de forma exitosa!',
                'ticket' => [
                    'folio' => $folio,
                    'fecha' => now()->format('d/m/Y H:i'),
                    'productos' => $items,
                    'subtotal' => $subtotal,
                    'descuento' => $descuento + $descuentoPuntos,
                    'iva' => $iva,
                    'total' => $total,
                    'metodoPago' => $metodoPago,
                    'entrega' => $entrega,
                    'mesa' => $numeroMesa,
                    'direccion' => $direccion
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al finalizar checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    public function misPedidos()
    {
        $pedidos = DB::table('pedidos')
            ->where('UsuarioId', auth()->id())
            ->orderBy('PedidoId', 'desc')
            ->get();
        return view('client.orders', compact('pedidos'));
    }

    public function misPuntos()
    {
        $puntos = DB::table('puntoscliente')->where('UsuarioId', auth()->id())->first();
        $transacciones = DB::table('transaccionespuntos')
            ->where('UsuarioId', auth()->id())
            ->orderBy('TransaccionId', 'desc')
            ->get();
        return view('client.points', compact('puntos', 'transacciones'));
    }

    public function reservar()
    {
        $zonas = DB::table('zonas')->where('Activa', 1)->get();
        foreach ($zonas as $z) {
            $z->mesas = DB::table('mesas')
                ->where('ZonaId', $z->ZonaId)
                ->get();
        }

        $mesas = DB::table('mesas')
            ->leftJoin('zonas', 'mesas.ZonaId', '=', 'zonas.ZonaId')
            ->select('mesas.*', 'zonas.Nombre as NombreZona', 'zonas.Icono as IconoZona')
            ->get();

        $reservaciones = Reservacion::where('UsuarioId', auth()->id())
            ->orderBy('ReservacionId', 'desc')
            ->get();

        return view('client.reserve', compact('mesas', 'zonas', 'reservaciones'));
    }

    public function guardarReservacion(Request $request)
    {
        $request->validate([
            'mesaId' => 'required|integer',
            'fechaHora' => 'required|string',
        ]);

        $mesaId = $request->input('mesaId');
        $fechaHoraSolicitada = \Carbon\Carbon::parse($request->input('fechaHora'));
        
        // Ventana de 2 horas antes y después
        $inicioRango = $fechaHoraSolicitada->copy()->subHours(2)->toDateTimeString();
        $finRango = $fechaHoraSolicitada->copy()->addHours(2)->toDateTimeString();

        // Validar si existe colisión de horario
        $colision = DB::table('reservaciones')
            ->where('MesaId', $mesaId)
            ->where('Estado', 'CONFIRMADA')
            ->whereBetween('FechaHora', [$inicioRango, $finRango])
            ->first();

        if ($colision) {
            $horaConflicto = \Carbon\Carbon::parse($colision->FechaHora)->format('H:i');
            return redirect('/Reservar')->withErrors([
                'error' => "La mesa seleccionada ya está reservada en una hora conflictiva (Mesa ocupada a las {$horaConflicto}). Por favor, elija otra mesa u horario con al menos 2 horas de diferencia."
            ]);
        }

        $reservacionId = DB::table('reservaciones')->insertGetId([
            'UsuarioId' => auth()->id(),
            'MesaId' => $mesaId,
            'NombreCliente' => auth()->check() ? auth()->user()->Username : 'Cliente Anónimo',
            'FechaHora' => $fechaHoraSolicitada->toDateTimeString(),
            'Estado' => 'CONFIRMADA',
        ]);

        return redirect('/Reservar')
            ->with('msg', '¡Mesa reservada con éxito! Su boleto digital de reservación está listo.')
            ->with('nueva_reservacion_id', $reservacionId);
    }

    public function descargarBoletoPdf($id)
    {
        $reservacion = DB::table('reservaciones')->where('ReservacionId', $id)->first();
        if (!$reservacion) {
            return redirect('/Reservar')->withErrors(['error' => 'Reservación no encontrada.']);
        }

        $mesa = DB::table('mesas')
            ->leftJoin('zonas', 'mesas.ZonaId', '=', 'zonas.ZonaId')
            ->where('mesas.MesaId', $reservacion->MesaId)
            ->select('mesas.*', 'zonas.Nombre as NombreZona', 'zonas.Icono as IconoZona')
            ->first();

        return view('client.reservation_pdf', compact('reservacion', 'mesa'));
    }

    public function descargarTicketPdf($folio)
    {
        $pedido = DB::table('pedidos')->where('Folio', $folio)->first();
        if (!$pedido) {
            return redirect('/MisPedidos')->withErrors(['error' => 'Pedido no encontrado.']);
        }

        $detalles = DB::table('detallespedido')
            ->join('productos', 'detallespedido.ProductoId', '=', 'productos.ProductoId')
            ->where('detallespedido.PedidoId', $pedido->PedidoId)
            ->select('productos.Nombre', 'detallespedido.Cantidad', 'productos.Precio')
            ->get();

        $factura = DB::table('facturas')->where('PedidoId', $pedido->PedidoId)->first();

        return view('client.ticket_pdf', compact('pedido', 'detalles', 'factura'));
    }
}