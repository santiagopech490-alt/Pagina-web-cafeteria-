<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                return redirect('/')->withErrors(['unauthorized' => 'Acceso denegado. Se requieren privilegios de administrador.']);
            }
            return $next($request);
        });
    }

    private static $tablasPermitidas = [
        "roles", "usuarios", "categorias", "productos", "tiposmodificador", "opcionesmodificador",
        "productomodificadores", "estadospedido", "metodosentrega", "metodospago", "cupones",
        "pedidos", "detallespedido", "detallesmodificadores", "historialestadospedido", "puntoscliente",
        "transaccionespuntos", "logsauditoria", "configuracionsistema", "proveedores", "ordenescompra",
        "detallesordencompra", "mesas", "reservaciones", "empleados", "turnos", "asignacionturnos",
        "facturas", "detallesfactura", "resenasproductos", "notificaciones"
    ];

    private function validarTabla($tabla)
    {
        return in_array(strtolower($tabla), self::$tablasPermitidas);
    }

    public function index()
    {
        $productos = Producto::all();
        return view("admin.index", compact("productos"));
    }

    // --- Secciones Dedicadas ---

    public function empleados()
    {
        $filas = DB::table("empleados")->get();
        $columnas = Schema::getColumnListing("empleados");
        return view("admin.empleados", compact("filas", "columnas"));
    }

    public function usuariosRoles()
    {
        $filas = DB::table("usuarios")->get();
        return view("admin.usuarios_roles", compact("filas"));
    }

    public function logsAuditoria()
    {
        $filas = DB::table("logsauditoria")->orderBy("LogId", "desc")->get();
        return view("admin.logs_auditoria", compact("filas"));
    }

    public function facturas()
    {
        $filas = DB::table("facturas")->orderBy("FacturaId", "desc")->get();
        return view("admin.facturas", compact("filas"));
    }

    public function ordenesCompra()
    {
        $filas = DB::table("ordenescompra")
            ->join("proveedores", "ordenescompra.ProveedorId", "=", "proveedores.ProveedorId")
            ->select("ordenescompra.*", "proveedores.RazonSocial")
            ->orderBy("ordenescompra.OrdenId", "desc")
            ->get();

        foreach ($filas as $f) {
            $f->detalles = DB::table("detallesordencompra")
                ->join("productos", "detallesordencompra.ProductoId", "=", "productos.ProductoId")
                ->where("detallesordencompra.OrdenId", $f->OrdenId)
                ->select("productos.Nombre", "detallesordencompra.CantidadPedida")
                ->get();
        }

        $proveedores = DB::table("proveedores")->where("Activo", 1)->get();
        $productos = DB::table("productos")->get();

        return view("admin.ordenes_compra", compact("filas", "proveedores", "productos"));
    }

    public function guardarOrdenCompra(Request $request)
    {
        $request->validate([
            'proveedorId' => 'required|integer',
            'productoId' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        $folio = 'OC-' . date('Y') . '-' . rand(100, 999);

        DB::beginTransaction();
        try {
            $ordenId = DB::table('ordenescompra')->insertGetId([
                'ProveedorId' => $request->input('proveedorId'),
                'FolioOrden' => $folio,
                'FechaCreacion' => now(),
                'Estado' => 'PENDIENTE'
            ]);

            DB::table('detallesordencompra')->insert([
                'OrdenId' => $ordenId,
                'ProductoId' => $request->input('productoId'),
                'CantidadPedida' => $request->input('cantidad')
            ]);

            DB::table('logsauditoria')->insert([
                'UsuarioId' => auth()->id(),
                'Accion' => 'CREAR_ORDEN_COMPRA',
                'Detalle' => "Emitida orden de compra {$folio} para proveedor ID: {$request->input('proveedorId')}.",
                'Fecha' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', "¡Orden de compra #{$folio} generada con éxito!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al generar orden de compra: ' . $e->getMessage()]);
        }
    }

    public function cupones()
    {
        $filas = DB::table("cupones")->orderBy("CuponId", "desc")->get();
        return view("admin.cupones", compact("filas"));
    }

    public function categorias()
    {
        $filas = DB::table("categorias")->orderBy("CategoriaId")->get();
        return view("admin.categorias", compact("filas"));
    }

    public function puntosLealtad()
    {
        $filas = DB::table("puntoscliente")->get();
        return view("admin.puntos_lealtad", compact("filas"));
    }

    public function resenasProductos()
    {
        $filas = DB::table("resenasproductos")->orderBy("ResenaId", "desc")->get();
        return view("admin.resenas_productos", compact("filas"));
    }

    // --- Gestor de Tablas Dinámico ---

    public function tablas(Request $request)
    {
        $tablaSeleccionada = $request->query("tabla");

        if (in_array(strtolower($tablaSeleccionada), ['mesas', 'zonas'])) {
            return redirect('/admin/zonas');
        }
        if (strtolower($tablaSeleccionada) === 'roles') {
            return redirect('/admin/roles');
        }
        if (strtolower($tablaSeleccionada) === 'empleados') {
            return redirect('/admin/empleados');
        }
        if (strtolower($tablaSeleccionada) === 'usuarios') {
            return redirect('/admin/usuarios-roles');
        }
        if (strtolower($tablaSeleccionada) === 'productos') {
            return redirect('/admin/registrar');
        }

        $filas = [];
        $columnas = [];

        if ($tablaSeleccionada && $this->validarTabla($tablaSeleccionada)) {
            $filas = DB::table($tablaSeleccionada)->limit(500)->get();
            $columnas = Schema::getColumnListing($tablaSeleccionada);
        }

        $tablasOcultas = ['mesas', 'zonas', 'roles', 'usuarios', 'empleados', 'productos'];
        $tablas = collect(self::$tablasPermitidas)
            ->reject(fn($t) => in_array(strtolower($t), $tablasOcultas))
            ->map(function ($t) {
                return (object)[
                    "nombre" => $t,
                    "etiqueta" => ucfirst($t)
                ];
            });

        return view("admin.tablas", compact("tablas", "tablaSeleccionada", "filas", "columnas"));
    }

    /**
     * Preprocesa datos para normalizar booleanos (si/no, true/false) y cadenas vacías a nulos.
     */
    private function preprocesarDatos(array $datos)
    {
        foreach ($datos as $key => $value) {
            if (is_string($value)) {
                $trimmed = strtolower(trim($value));
                if ($trimmed === 'si' || $trimmed === 'yes' || $trimmed === 'true') {
                    $datos[$key] = 1;
                } elseif ($trimmed === 'no' || $trimmed === 'false') {
                    $datos[$key] = 0;
                } elseif ($value === '') {
                    $datos[$key] = null;
                }
            }
        }
        return $datos;
    }

    /**
     * Guarda la imagen cargada asociándola al producto.
     */
    private function guardarImagenProducto(Request $request, $nombreProducto)
    {
        if ($request->hasFile('imagen_producto') && $nombreProducto) {
            $file = $request->file('imagen_producto');
            $ext = $file->getClientOriginalExtension();
            $filename = trim($nombreProducto) . '.' . $ext;

            // Carpeta pública
            $destDir = public_path('images/externo');
            if (!file_exists($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            $file->move($destDir, $filename);

            // Copia en carpeta externa de diseño de la página
            $carpetaExterna = "C:\\XD\\Examen-de-programacion\\imagenes para diseño de la pagia";
            if (is_dir($carpetaExterna)) {
                copy($destDir . '/' . $filename, $carpetaExterna . '/' . $filename);
            }
        }
    }

    public function insertarFila(Request $request, $tabla)
    {
        if (!$this->validarTabla($tabla)) {
            return response()->json(["mensaje" => "Tabla no permitida."], 400);
        }

        $datos = $request->except(["_token", "_method", "imagen_producto"]);
        $datos = $this->preprocesarDatos($datos);
        
        try {
            DB::table($tabla)->insert($datos);

            if ($tabla === 'productos') {
                $this->guardarImagenProducto($request, $datos['Nombre'] ?? null);
            }

            return response()->json(["mensaje" => "Registro guardado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(["mensaje" => "Error al insertar: " . $e->getMessage()], 500);
        }
    }

    public function actualizarFila(Request $request, $tabla, $idColumna, $idValor)
    {
        if (!$this->validarTabla($tabla)) {
            return response()->json(["mensaje" => "Tabla no permitida."], 400);
        }

        $datos = $request->except(["_token", "_method", "imagen_producto"]);
        $datos = $this->preprocesarDatos($datos);

        try {
            DB::table($tabla)->where($idColumna, $idValor)->update($datos);

            if ($tabla === 'productos') {
                $nombreProducto = $datos['Nombre'] ?? null;
                if (!$nombreProducto) {
                    $prod = DB::table('productos')->where($idColumna, $idValor)->first();
                    $nombreProducto = $prod ? $prod->Nombre : null;
                }
                $this->guardarImagenProducto($request, $nombreProducto);
            }

            return response()->json(["mensaje" => "Registro actualizado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(["mensaje" => "Error al actualizar: " . $e->getMessage()], 500);
        }
    }

    public function eliminarFila($tabla, $idColumna, $idValor)
    {
        if (!$this->validarTabla($tabla)) {
            return response()->json(["mensaje" => "Tabla no permitida."], 400);
        }

        try {
            DB::table($tabla)->where($idColumna, $idValor)->delete();
            return response()->json(["mensaje" => "Registro eliminado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(["mensaje" => "Error al eliminar: " . $e->getMessage()], 500);
        }
    }

    public function corteCaja()
    {
        $totalVentas = DB::table('facturas')
            ->join('pedidos', 'facturas.PedidoId', '=', 'pedidos.PedidoId')
            ->whereDate('pedidos.FechaPedido', now()->toDateString())
            ->sum('facturas.Total');

        if (!$totalVentas || $totalVentas == 0) {
            $totalVentas = DB::table('facturas')->sum('Total');
        }

        $totalEfectivo = $totalVentas;
        $descuentosAplicados = 0;

        return view('admin.corte_caja', compact('totalEfectivo', 'totalVentas', 'descuentosAplicados'));
    }

    public function proveedoresView()
    {
        $proveedores = DB::table('proveedores')->orderBy('ProveedorId', 'desc')->get();
        return view('admin.proveedores', compact('proveedores'));
    }

    public function guardarProveedor(Request $request)
    {
        $request->validate([
            'razonSocial' => 'required|string|max:150',
        ]);

        DB::table('proveedores')->insert([
            'RazonSocial' => $request->input('razonSocial'),
            'Activo' => 1
        ]);

        return redirect()->back()->with('success', '¡Nuevo proveedor registrado con éxito!');
    }

    public function toggleProveedor($id)
    {
        $prov = DB::table('proveedores')->where('ProveedorId', $id)->first();
        if (!$prov) {
            return redirect()->back()->withErrors(['error' => 'Proveedor no encontrado.']);
        }

        $nuevoEstatus = ($prov->Activo == 1) ? 0 : 1;
        $estadoTexto = ($nuevoEstatus == 1) ? 'ACTIVADO' : 'DESACTIVADO';

        DB::table('proveedores')->where('ProveedorId', $id)->update([
            'Activo' => $nuevoEstatus
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'CAMBIO_ESTATUS_PROVEEDOR',
            'Detalle' => "Proveedor '{$prov->RazonSocial}' (ID: {$id}) ha sido {$estadoTexto}.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "El estatus del proveedor '{$prov->RazonSocial}' ha sido " . strtolower($estadoTexto) . " con éxito.");
    }

    public function guardarCorteCaja(Request $request)
    {
        $request->validate([
            'efectivo_declarado' => 'required|numeric|min:0',
            'efectivo_esperado' => 'required|numeric|min:0',
        ]);

        $declarado = floatval($request->input('efectivo_declarado'));
        $esperado = floatval($request->input('efectivo_esperado'));
        $discrepancia = $declarado - $esperado;

        DB::beginTransaction();
        try {
            // 1. Guardar auditoría del Corte Z
            $detalleCierre = json_encode([
                'Fecha' => now()->toDateString(),
                'Esperado' => $esperado,
                'Declarado' => $declarado,
                'Discrepancia' => $discrepancia,
                'TotalVentas' => $esperado
            ]);

            DB::table('logsauditoria')->insert([
                'UsuarioId' => auth()->id(),
                'Accion' => 'CIERRE_CAJA_Z',
                'Detalle' => $detalleCierre,
                'Fecha' => now()
            ]);

            // 2. Guardar en configuracion de sistema el último corte
            $existeConfig = DB::table('configuracionsistema')->where('Clave', 'ULTIMO_CORTE_Z')->first();
            if ($existeConfig) {
                DB::table('configuracionsistema')
                    ->where('Clave', 'ULTIMO_CORTE_Z')
                    ->update(['Valor' => now()->toDateString()]);
            } else {
                DB::table('configuracionsistema')->insert([
                    'Clave' => 'ULTIMO_CORTE_Z',
                    'Valor' => now()->toDateString()
                ]);
            }

            DB::commit();
            return redirect()->route('admin.corte_caja')->with('success', 'Corte Z de caja realizado y guardado con éxito. Discrepancia calculada: $' . number_format($discrepancia, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.corte_caja')->withErrors(['error' => 'Error al guardar el corte: ' . $e->getMessage()]);
        }
    }

    public function kdsView()
    {
        return view('admin.kds');
    }

    public function pedidosActivosKds()
    {
        // Obtener pedidos con EstadoId = 1 (RECIBIDO / EN COCINA)
        $pedidos = DB::table('pedidos')
            ->where('EstadoId', 1)
            ->orderBy('FechaPedido', 'asc')
            ->get();

        $resultado = [];
        foreach ($pedidos as $p) {
            // Cargar los items del pedido
            $items = DB::table('detallespedido')
                ->join('productos', 'detallespedido.ProductoId', '=', 'productos.ProductoId')
                ->where('detallespedido.PedidoId', $p->PedidoId)
                ->select('detallespedido.DetalleId', 'productos.Nombre as ProductoNombre', 'detallespedido.Cantidad')
                ->get();

            foreach ($items as $item) {
                // Obtener modificadores seleccionados para este ítem
                $item->Modificadores = DB::table('detallesmodificadores')
                    ->join('opcionesmodificador', 'detallesmodificadores.OpcionId', '=', 'opcionesmodificador.OpcionId')
                    ->where('detallesmodificadores.DetalleId', $item->DetalleId)
                    ->pluck('opcionesmodificador.Nombre')
                    ->toArray();
            }

            $nombreEntrega = 'Para Llevar';
            if (isset($p->MetodoEntregaId) && $p->MetodoEntregaId == 2) $nombreEntrega = 'Consumo en Mesa';
            if (isset($p->MetodoEntregaId) && $p->MetodoEntregaId == 4) $nombreEntrega = 'A Domicilio';

            $fechaStr = isset($p->FechaPedido) && $p->FechaPedido ? $p->FechaPedido : date('Y-m-d H:i:s');
            $minutos = abs((int)now()->diffInMinutes(\Carbon\Carbon::parse($fechaStr)));

            $resultado[] = [
                'PedidoId' => $p->PedidoId,
                'Folio' => $p->Folio,
                'FechaPedido' => $fechaStr,
                'MetodoEntrega' => $nombreEntrega,
                'NumeroMesa' => $p->NumeroMesa,
                'items' => $items,
                'MinutosTranscurridos' => $minutos
            ];
        }

        return response()->json($resultado);
    }

    public function completarPedidoKds($id)
    {
        DB::beginTransaction();
        try {
            // 1. Actualizar el estado del pedido a EstadoId = 2 (LISTO)
            DB::table('pedidos')
                ->where('PedidoId', $id)
                ->update(['EstadoId' => 2]);

            // 2. Registrar en historial de estados
            DB::table('historialestadospedido')->insert([
                'PedidoId' => $id,
                'EstadoId' => 2,
                'CambiadoPor' => auth()->id() ?? 1, // Si es anónimo o default
                'CambiadoEn' => now()
            ]);

            DB::commit();
            return response()->json(['exito' => true, 'mensaje' => 'Pedido completado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['exito' => false, 'mensaje' => 'Error al actualizar estado: ' . $e->getMessage()], 500);
        }
    }

    public function recibirOrdenCompra($id)
    {
        $orden = DB::table('ordenescompra')->where('OrdenId', $id)->first();
        if (!$orden) {
            return redirect()->back()->withErrors(['error' => 'La orden de compra solicitada no existe.']);
        }

        if ($orden->Estado === 'RECIBIDA') {
            return redirect()->back()->withErrors(['error' => 'Esta orden de compra ya ha sido recibida anteriormente.']);
        }

        DB::beginTransaction();
        try {
            // 1. Obtener detalles de la orden
            $detalles = DB::table('detallesordencompra')->where('OrdenId', $id)->get();

            // 2. Incrementar stock de los productos/insumos correspondientes
            foreach ($detalles as $det) {
                $producto = Producto::find($det->ProductoId);
                if ($producto) {
                    $producto->Existencia += $det->CantidadPedida;
                    $producto->save();
                }
            }

            // 3. Cambiar estado de la orden
            DB::table('ordenescompra')->where('OrdenId', $id)->update([
                'Estado' => 'RECIBIDA'
            ]);

            // 4. Loggear auditoría
            DB::table('logsauditoria')->insert([
                'UsuarioId' => auth()->id(),
                'Accion' => 'ORDEN_COMPRA_RECIBIDA',
                'Detalle' => 'Recibida orden de compra ID: ' . $id . ' Folio: ' . $orden->FolioOrden,
                'Fecha' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Mercancía recibida e inyectada al stock de inventario con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al procesar recepción de mercancía: ' . $e->getMessage()]);
        }
    }

    public function zonasView()
    {
        $zonas = DB::table('zonas')->get();
        
        $reservacionesActivas = DB::table('reservaciones')
            ->leftJoin('mesas', 'reservaciones.MesaId', '=', 'mesas.MesaId')
            ->leftJoin('zonas', 'mesas.ZonaId', '=', 'zonas.ZonaId')
            ->where('reservaciones.Estado', 'CONFIRMADA')
            ->select('reservaciones.*', 'mesas.NumeroMesa', 'zonas.Nombre as NombreZona', 'zonas.Icono as IconoZona')
            ->orderBy('reservaciones.FechaHora', 'asc')
            ->get();

        $mesas = DB::table('mesas')
            ->leftJoin('zonas', 'mesas.ZonaId', '=', 'zonas.ZonaId')
            ->select('mesas.*', 'zonas.Nombre as NombreZona', 'zonas.Icono as IconoZona')
            ->orderBy('mesas.MesaId', 'asc')
            ->get();

        // Mapear reservaciones activas a cada mesa
        foreach ($mesas as $m) {
            $resMesa = $reservacionesActivas->firstWhere('MesaId', $m->MesaId);
            $m->reservacionActiva = $resMesa;
            
            // Determinar estado efectivo
            if ($m->Estado == 'Ocupada') {
                $m->estadoEfectivo = 'Ocupada';
            } elseif ($resMesa || $m->Estado == 'Reservada') {
                $m->estadoEfectivo = 'Reservada';
            } else {
                $m->estadoEfectivo = 'Disponible';
            }
        }

        foreach ($zonas as $z) {
            $z->mesas = $mesas->where('ZonaId', $z->ZonaId);
        }

        $mesasSinZona = $mesas->whereNull('ZonaId');

        $totalMesas = $mesas->count();
        $mesasDisponibles = $mesas->where('estadoEfectivo', 'Disponible')->count();
        $mesasReservadas = $mesas->where('estadoEfectivo', 'Reservada')->count();
        $mesasOcupadas = $mesas->where('estadoEfectivo', 'Ocupada')->count();

        return view('admin.zonas', compact(
            'zonas', 'mesas', 'mesasSinZona', 'reservacionesActivas',
            'totalMesas', 'mesasDisponibles', 'mesasReservadas', 'mesasOcupadas'
        ));
    }

    public function cambiarEstadoMesa(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:Disponible,Ocupada,Reservada'
        ]);

        $mesa = DB::table('mesas')->where('MesaId', $id)->first();
        if (!$mesa) {
            return redirect()->back()->withErrors(['error' => 'Mesa no encontrada.']);
        }

        DB::table('mesas')->where('MesaId', $id)->update([
            'Estado' => $request->input('estado')
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'CAMBIO_ESTADO_MESA',
            'Detalle' => "Mesa ID: {$id} ({$mesa->NumeroMesa}) cambió a estado {$request->input('estado')}.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "¡Estatus de Mesa {$mesa->NumeroMesa} actualizado a '{$request->input('estado')}'!");
    }

    public function cancelarReservacionAdmin($id)
    {
        $res = DB::table('reservaciones')->where('ReservacionId', $id)->first();
        if (!$res) {
            return redirect()->back()->withErrors(['error' => 'Reservación no encontrada.']);
        }

        DB::table('reservaciones')->where('ReservacionId', $id)->update([
            'Estado' => 'CANCELADA'
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'CANCELAR_RESERVACION',
            'Detalle' => "Reservación #RES-{$id} cancelada / liberada por el administrador.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "La reservación #RES-{$id} ha sido liberada / cancelada con éxito.");
    }

    public function guardarZona(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string|max:20',
        ]);

        DB::table('zonas')->insert([
            'Nombre' => $request->input('nombre'),
            'Descripcion' => $request->input('descripcion'),
            'Icono' => $request->input('icono') ?? '🏛️',
            'Activa' => 1
        ]);

        return redirect()->back()->with('success', '¡Zona del restaurante creada con éxito!');
    }

    public function guardarMesa(Request $request)
    {
        $request->validate([
            'numeroMesa' => 'required|string|max:50',
            'zonaId' => 'required|integer',
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:100',
        ]);

        DB::table('mesas')->insert([
            'NumeroMesa' => $request->input('numeroMesa'),
            'ZonaId' => $request->input('zonaId'),
            'Capacidad' => $request->input('capacidad'),
            'Ubicacion' => $request->input('ubicacion'),
            'Estado' => 'Disponible'
        ]);

        return redirect()->back()->with('success', '¡Nueva mesa añadida a la zona con éxito!');
    }

    public function rolesView()
    {
        $roles = DB::table('roles')->get();

        foreach ($roles as $r) {
            $r->totalUsuarios = DB::table('usuarios')->where('RolId', $r->RolId)->count();
        }

        return view('admin.roles', compact('roles'));
    }

    public function toggleRol($id)
    {
        $rol = DB::table('roles')->where('RolId', $id)->first();
        if (!$rol) {
            return redirect()->back()->withErrors(['error' => 'Rol no encontrado.']);
        }

        $nuevoEstatus = ($rol->Activo == 1) ? 0 : 1;
        $estadoTexto = ($nuevoEstatus == 1) ? 'ACTIVADO' : 'DESACTIVADO';

        DB::table('roles')->where('RolId', $id)->update([
            'Activo' => $nuevoEstatus
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'CAMBIO_ESTATUS_ROL',
            'Detalle' => "Rol '{$rol->Nombre}' (ID: {$id}) ha sido {$estadoTexto}.",
            'Fecha' => now()
        ]);

        $msg = "El rol '{$rol->Nombre}' ha sido " . strtolower($estadoTexto) . " con éxito.";
        return redirect()->back()->with('success', $msg);
    }

    public function guardarRol(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        DB::table('roles')->insert([
            'Nombre' => $request->input('nombre'),
            'Descripcion' => $request->input('descripcion'),
            'Activo' => 1
        ]);

        return redirect()->back()->with('success', '¡Nuevo rol de usuario creado con éxito!');
    }

    public function toggleUsuario($id)
    {
        $usr = DB::table('usuarios')->where('UsuarioId', $id)->first();
        if (!$usr) {
            return redirect()->back()->withErrors(['error' => 'Usuario no encontrado.']);
        }

        $nuevoEstatus = ($usr->Activo == 1) ? 0 : 1;
        $estadoTexto = ($nuevoEstatus == 1) ? 'ACTIVADO' : 'DESACTIVADO';

        DB::table('usuarios')->where('UsuarioId', $id)->update([
            'Activo' => $nuevoEstatus
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'CAMBIO_ESTATUS_USUARIO',
            'Detalle' => "Usuario '{$usr->Username}' (ID: {$id}) ha sido {$estadoTexto}.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "El acceso del usuario '{$usr->Username}' ha sido " . strtolower($estadoTexto) . " con éxito.");
    }

    public function toggleEmpleado($id)
    {
        $emp = DB::table('empleados')->where('EmpleadoId', $id)->first();
        if (!$emp) {
            return redirect()->back()->withErrors(['error' => 'Empleado no encontrado.']);
        }

        $nuevoEstatus = ($emp->Activo == 1) ? 0 : 1;
        $estadoTexto = ($nuevoEstatus == 1) ? 'ACTIVADO' : 'DESACTIVADO';

        DB::table('empleados')->where('EmpleadoId', $id)->update([
            'Activo' => $nuevoEstatus
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'CAMBIO_ESTATUS_EMPLEADO',
            'Detalle' => "Empleado '{$emp->Nombre}' (ID: {$id}) ha sido {$estadoTexto}.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "El estatus del empleado '{$emp->Nombre}' ha sido " . strtolower($estadoTexto) . " con éxito.");
    }

    // --- Módulos Específicos de Productos ---

    public function registrarProductoView()
    {
        $productos = DB::table('productos')->orderBy('ProductoId', 'desc')->get();
        $categorias = DB::table('categorias')->get();
        return view('admin.productos_registrar', compact('productos', 'categorias'));
    }

    public function guardarProducto(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0.01',
            'existencia' => 'required|integer|min:0',
            'categoriaId' => 'nullable|integer',
        ]);

        $codigo = strtoupper(trim($request->input('codigo')));
        $existe = DB::table('productos')->where('Codigo', $codigo)->first();
        if ($existe) {
            return redirect()->back()->withErrors(['error' => "El código {$codigo} ya está registrado."]);
        }

        DB::table('productos')->insert([
            'Codigo' => $codigo,
            'Nombre' => $request->input('nombre'),
            'Precio' => $request->input('precio'),
            'Existencia' => $request->input('existencia'),
            'CategoriaId' => $request->input('categoriaId'),
            'Disponible' => 1,
            'Destacado' => 0
        ]);

        return redirect()->back()->with('success', "¡Producto '{$request->input('nombre')}' registrado con éxito!");
    }

    public function abastecerProductoView()
    {
        $productos = DB::table('productos')->orderBy('Nombre', 'asc')->get();
        return view('admin.productos_abastecer', compact('productos'));
    }

    public function guardarAbastecer(Request $request)
    {
        $request->validate([
            'productoId' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        $prod = DB::table('productos')->where('ProductoId', $request->input('productoId'))->first();
        if (!$prod) {
            return redirect()->back()->withErrors(['error' => 'Producto no encontrado.']);
        }

        $nuevaExistencia = $prod->Existencia + $request->input('cantidad');

        DB::table('productos')->where('ProductoId', $prod->ProductoId)->update([
            'Existencia' => $nuevaExistencia
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'REABASTECER_PRODUCTO',
            'Detalle' => "Abastecido '{$prod->Nombre}' con +{$request->input('cantidad')} unidades (Total: {$nuevaExistencia}).",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "¡Stock de '{$prod->Nombre}' actualizado (+{$request->input('cantidad')} unidades). Existencia total: {$nuevaExistencia}!");
    }

    public function ajustarPrecioView()
    {
        $productos = DB::table('productos')->orderBy('Nombre', 'asc')->get();
        return view('admin.productos_precio', compact('productos'));
    }

    public function guardarPrecio(Request $request, $id)
    {
        $request->validate([
            'precio' => 'required|numeric|min:0.01',
        ]);

        $prod = DB::table('productos')->where('ProductoId', $id)->first();
        if (!$prod) {
            return redirect()->back()->withErrors(['error' => 'Producto no encontrado.']);
        }

        $precioAnterior = $prod->Precio;
        $nuevoPrecio = $request->input('precio');

        DB::table('productos')->where('ProductoId', $id)->update([
            'Precio' => $nuevoPrecio
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'CAMBIO_PRECIO_PRODUCTO',
            'Detalle' => "Precio de '{$prod->Nombre}' modificado de \${$precioAnterior} a \${$nuevoPrecio}.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "¡Precio de '{$prod->Nombre}' ajustado a \${$nuevoPrecio} exitosamente!");
    }

    public function editarNombreView()
    {
        $productos = DB::table('productos')->orderBy('Nombre', 'asc')->get();
        $categorias = DB::table('categorias')->get();
        return view('admin.productos_editar', compact('productos', 'categorias'));
    }

    public function guardarEditarNombre(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'categoriaId' => 'nullable|integer',
        ]);

        $prod = DB::table('productos')->where('ProductoId', $id)->first();
        if (!$prod) {
            return redirect()->back()->withErrors(['error' => 'Producto no encontrado.']);
        }

        $nombreAnterior = $prod->Nombre;
        $nuevoNombre = $request->input('nombre');

        DB::table('productos')->where('ProductoId', $id)->update([
            'Nombre' => $nuevoNombre,
            'CategoriaId' => $request->input('categoriaId')
        ]);

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'EDITAR_NOMBRE_PRODUCTO',
            'Detalle' => "Nombre de producto cambiado de '{$nombreAnterior}' a '{$nuevoNombre}'.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', "¡Nombre del producto actualizado a '{$nuevoNombre}' exitosamente!");
    }

    public function eliminarProductoView()
    {
        $productos = DB::table('productos')->orderBy('Nombre', 'asc')->get();
        return view('admin.productos_eliminar', compact('productos'));
    }

    public function ejecutarEliminarProducto($id)
    {
        $prod = DB::table('productos')->where('ProductoId', $id)->first();
        if (!$prod) {
            return redirect()->back()->withErrors(['error' => 'Producto no encontrado.']);
        }

        $enUso = DB::table('detallespedido')->where('ProductoId', $id)->exists();

        if ($enUso) {
            DB::table('productos')->where('ProductoId', $id)->update(['Disponible' => 0]);
            $msg = "El producto '{$prod->Nombre}' tiene historial de ventas; ha sido marcado como INHABILITADO.";
        } else {
            DB::table('productos')->where('ProductoId', $id)->delete();
            $msg = "El producto '{$prod->Nombre}' ha sido ELIMINADO permanentemente del catálogo.";
        }

        DB::table('logsauditoria')->insert([
            'UsuarioId' => auth()->id(),
            'Accion' => 'ELIMINAR_PRODUCTO',
            'Detalle' => "Producto '{$prod->Nombre}' (ID: {$id}) eliminado / inhabilitado.",
            'Fecha' => now()
        ]);

        return redirect()->back()->with('success', $msg);
    }

    // --- Módulos Dedicados del Sistema ---

    public function configuracionView()
    {
        $configs = DB::table('configuracionsistema')->get();
        return view('admin.configuracion', compact('configs'));
    }

    public function guardarConfiguracion(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:100',
            'valor' => 'required|string',
        ]);

        $clave = strtoupper(trim($request->input('clave')));
        $existe = DB::table('configuracionsistema')->where('Clave', $clave)->first();

        if ($existe) {
            DB::table('configuracionsistema')->where('Clave', $clave)->update(['Valor' => $request->input('valor')]);
        } else {
            DB::table('configuracionsistema')->insert(['Clave' => $clave, 'Valor' => $request->input('valor')]);
        }

        return redirect()->back()->with('success', "¡Parámetro de configuración '{$clave}' guardado con éxito!");
    }

    public function notificacionesView()
    {
        $notificaciones = DB::table('notificaciones')->orderBy('NotificacionId', 'desc')->get();
        return view('admin.notificaciones', compact('notificaciones'));
    }

    public function modificadoresView()
    {
        $tipos = DB::table('tiposmodificador')->get();
        foreach ($tipos as $t) {
            $t->opciones = DB::table('opcionesmodificador')->where('TipoId', $t->TipoId)->get();
        }
        return view('admin.modificadores', compact('tipos'));
    }

    public function guardarModificador(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        DB::table('tiposmodificador')->insert(['Nombre' => $request->input('nombre')]);
        return redirect()->back()->with('success', "¡Nuevo grupo de modificador '{$request->input('nombre')}' creado!");
    }

    public function metodosPagoView()
    {
        $metodos = DB::table('metodospago')->get();
        return view('admin.metodos_pago', compact('metodos'));
    }

    public function toggleMetodoPago($id)
    {
        $m = DB::table('metodospago')->where('MetodoPagoId', $id)->first();
        if (!$m) return redirect()->back()->withErrors(['error' => 'Método de pago no encontrado.']);

        $nuevo = ($m->Activo == 1) ? 0 : 1;
        DB::table('metodospago')->where('MetodoPagoId', $id)->update(['Activo' => $nuevo]);

        return redirect()->back()->with('success', "Estatus de '{$m->Etiqueta}' actualizado con éxito.");
    }

    public function turnosView()
    {
        $turnos = DB::table('turnos')->get();
        return view('admin.turnos', compact('turnos'));
    }

    public function guardarTurno(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'horaInicio' => 'required|string',
            'horaFin' => 'required|string',
        ]);

        DB::table('turnos')->insert([
            'Nombre' => $request->input('nombre'),
            'HoraInicio' => $request->input('horaInicio'),
            'HoraFin' => $request->input('horaFin'),
        ]);

        return redirect()->back()->with('success', "¡Turno '{$request->input('nombre')}' registrado con éxito!");
    }

    public function asignacionturnosView()
    {
        $asignaciones = DB::table('asignacionturnos')
            ->leftJoin('empleados', 'asignacionturnos.EmpleadoId', '=', 'empleados.EmpleadoId')
            ->leftJoin('turnos', 'asignacionturnos.TurnoId', '=', 'turnos.TurnoId')
            ->select('asignacionturnos.*', 'empleados.Nombre as EmpleadoNombre', 'turnos.Nombre as TurnoNombre')
            ->orderBy('asignacionturnos.AsignacionId', 'desc')
            ->get();

        $empleados = DB::table('empleados')->get();
        $turnos = DB::table('turnos')->get();

        return view('admin.asignacionturnos', compact('asignaciones', 'empleados', 'turnos'));
    }

    public function guardarAsignacionTurno(Request $request)
    {
        $request->validate([
            'empleadoId' => 'required|integer',
            'turnoId' => 'required|integer',
            'fecha' => 'required|string',
        ]);

        DB::table('asignacionturnos')->insert([
            'EmpleadoId' => $request->input('empleadoId'),
            'TurnoId' => $request->input('turnoId'),
            'Fecha' => $request->input('fecha'),
        ]);

        return redirect()->back()->with('success', '¡Turno asignado al colaborador con éxito!');
    }
}