<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;

Route::get('/', [ClientController::class, 'landing'])->name('landing');
Route::get('/menu', [ClientController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de Carrito
Route::get('/Carrito', [ClientController::class, 'showCart'])->name('cart');
Route::post('/Carrito/Checkout', [ClientController::class, 'checkout'])->name('checkout');

// Rutas Protegidas del Cliente
Route::middleware(['auth'])->group(function () {
    Route::get('/MisPedidos', [ClientController::class, 'misPedidos'])->name('client.orders');
    Route::get('/MisPuntos', [ClientController::class, 'misPuntos'])->name('client.points');
    Route::get('/Reservar', [ClientController::class, 'reservar'])->name('client.reserve');
    Route::post('/Reservar', [ClientController::class, 'guardarReservacion']);
    Route::get('/ticket/{folio}/descargar', [ClientController::class, 'descargarTicketPdf'])->name('client.ticket.download');
    Route::get('/reservaciones/{id}/descargar-boleto', [ClientController::class, 'descargarBoletoPdf'])->name('client.reservation.download');
});

// --- Rutas del Panel de Administración (Protegidas para Administradores) ---
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // Apartados Dedicados
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::get('/usuarios-roles', [AdminController::class, 'usuariosRoles'])->name('admin.usuarios_roles');
    Route::get('/logs-auditoria', [AdminController::class, 'logsAuditoria'])->name('admin.logs_auditoria');
    Route::get('/facturas', [AdminController::class, 'facturas'])->name('admin.facturas');
    Route::get('/ordenes-compra', [AdminController::class, 'ordenesCompra'])->name('admin.ordenes_compra');
    Route::get('/cupones', [AdminController::class, 'cupones'])->name('admin.cupones');
    Route::get('/categorias', [AdminController::class, 'categorias'])->name('admin.categorias');
    Route::get('/puntos-lealtad', [AdminController::class, 'puntosLealtad'])->name('admin.puntos_lealtad');
    Route::get('/resenas-productos', [AdminController::class, 'resenasProductos'])->name('admin.resenas_productos');
    
    // Corte de Caja (Cierre Z)
    Route::get('/corte-caja', [AdminController::class, 'corteCaja'])->name('admin.corte_caja');
    Route::post('/corte-caja', [AdminController::class, 'guardarCorteCaja']);
    Route::post('/ordenes-compra/{id}/recibir', [AdminController::class, 'recibirOrdenCompra']);

    // Kitchen Display System (KDS)
    Route::get('/kds', [AdminController::class, 'kdsView'])->name('admin.kds');
    Route::post('/kds/pedidos/{id}/completar', [AdminController::class, 'completarPedidoKds']);
    Route::get('/api/kds/pedidos', [AdminController::class, 'pedidosActivosKds']);

    // Gestión de Zonas y Mesas de Salón
    Route::get('/zonas', [AdminController::class, 'zonasView'])->name('admin.zonas');
    Route::get('/mesas', function() { return redirect('/admin/zonas'); });
    Route::post('/zonas/crear', [AdminController::class, 'guardarZona']);
    Route::post('/mesas/crear', [AdminController::class, 'guardarMesa']);
    Route::post('/mesas/{id}/estado', [AdminController::class, 'cambiarEstadoMesa']);
    Route::post('/reservaciones/{id}/cancelar', [AdminController::class, 'cancelarReservacionAdmin']);

    // Gestión de Roles y Permisos
    Route::get('/roles', [AdminController::class, 'rolesView'])->name('admin.roles');
    Route::post('/roles/{id}/toggle', [AdminController::class, 'toggleRol']);
    Route::post('/roles/crear', [AdminController::class, 'guardarRol']);

    // Toggles de Estatus (Activar / Desactivar)
    Route::post('/usuarios/{id}/toggle', [AdminController::class, 'toggleUsuario']);
    Route::post('/empleados/{id}/toggle', [AdminController::class, 'toggleEmpleado']);

    // Gestión Dedicada de Operaciones de Productos
    Route::get('/registrar', [AdminController::class, 'registrarProductoView'])->name('admin.registrar');
    Route::post('/registrar', [AdminController::class, 'guardarProducto']);

    Route::get('/reabastecer', [AdminController::class, 'abastecerProductoView'])->name('admin.reabastecer');
    Route::post('/reabastecer', [AdminController::class, 'guardarAbastecer']);

    Route::get('/precio', [AdminController::class, 'ajustarPrecioView'])->name('admin.precio');
    Route::post('/precio/{id}', [AdminController::class, 'guardarPrecio']);

    Route::get('/editar', [AdminController::class, 'editarNombreView'])->name('admin.editar');
    Route::post('/editar/{id}', [AdminController::class, 'guardarEditarNombre']);

    Route::get('/eliminar', [AdminController::class, 'eliminarProductoView'])->name('admin.eliminar');
    Route::post('/eliminar/{id}', [AdminController::class, 'ejecutarEliminarProducto']);

    // Órdenes de Compra y Proveedores
    Route::post('/ordenes-compra/crear', [AdminController::class, 'guardarOrdenCompra']);
    Route::get('/proveedores', [AdminController::class, 'proveedoresView'])->name('admin.proveedores');
    Route::post('/proveedores/crear', [AdminController::class, 'guardarProveedor']);
    Route::post('/proveedores/{id}/toggle', [AdminController::class, 'toggleProveedor']);

    Route::get('/configuracion', [AdminController::class, 'configuracionView'])->name('admin.configuracion');
    Route::post('/configuracion/crear', [AdminController::class, 'guardarConfiguracion']);

    Route::get('/notificaciones', [AdminController::class, 'notificacionesView'])->name('admin.notificaciones');

    Route::get('/modificadores', [AdminController::class, 'modificadoresView'])->name('admin.modificadores');
    Route::post('/modificadores/crear', [AdminController::class, 'guardarModificador']);

    Route::get('/metodos-pago', [AdminController::class, 'metodosPagoView'])->name('admin.metodos_pago');
    Route::post('/metodos-pago/{id}/toggle', [AdminController::class, 'toggleMetodoPago']);

    Route::get('/turnos', [AdminController::class, 'turnosView'])->name('admin.turnos');
    Route::post('/turnos/crear', [AdminController::class, 'guardarTurno']);

    Route::get('/asignacionturnos', [AdminController::class, 'asignacionturnosView'])->name('admin.asignacionturnos');
    Route::post('/asignacionturnos/crear', [AdminController::class, 'guardarAsignacionTurno']);

    // Gestor de Tablas Dinámico
    Route::get('/tablas', [AdminController::class, 'tablas'])->name('admin.tablas');
    Route::post('/tablas/{tabla}', [AdminController::class, 'insertarFila']);
    Route::put('/tablas/{tabla}/{idColumna}/{idValor}', [AdminController::class, 'actualizarFila']);
    Route::delete('/tablas/{tabla}/{idColumna}/{idValor}', [AdminController::class, 'eliminarFila']);
});

// --- Endpoints de API rápidos para Ajax (Compatibilidad Frontend) ---
Route::get('/api/metodos-pago', function () {
    $metodos = DB::table('metodospago')->where('Activo', 1)->get(['Clave as clave', 'Etiqueta as etiqueta']);
    if ($metodos->isEmpty()) {
        return response()->json([
            ['clave' => 'EFECTIVO', 'etiqueta' => 'Pago en Efectivo'],
            ['clave' => 'TARJETA', 'etiqueta' => 'Tarjeta de Crédito / Débito'],
            ['clave' => 'PUNTOS', 'etiqueta' => 'Pago con Puntos de Lealtad']
        ]);
    }
    return response()->json($metodos);
});

Route::get('/api/mis-puntos', function () {
    if (!auth()->check()) {
        return response()->json(['puntos' => 0]);
    }
    $p = DB::table('puntoscliente')->where('UsuarioId', auth()->id())->first();
    return response()->json(['puntos' => $p ? $p->Puntos : 0]);
});

Route::get('/api/cupones/validar/{codigo}', function ($codigo) {
    $cupon = DB::table('cupones')->where('Codigo', $codigo)->where('Activo', 1)->first();
    if (!$cupon) {
        return response()->json(['valido' => false, 'mensaje' => 'Cupón no encontrado o inactivo.'], 404);
    }
    
    // Validar usos máximos
    if ($cupon->UsosMaximos && $cupon->UsosActuales >= $cupon->UsosMaximos) {
        return response()->json(['valido' => false, 'mensaje' => 'Este cupón ha agotado su límite de usos.'], 400);
    }

    // Validar vigencia
    $ahora = now();
    if ($cupon->ValidoDesde && $ahora->lt($cupon->ValidoDesde)) {
        return response()->json(['valido' => false, 'mensaje' => 'El cupón aún no está activo.'], 400);
    }
    if ($cupon->ValidoHasta && $ahora->gt($cupon->ValidoHasta)) {
        return response()->json(['valido' => false, 'mensaje' => 'El cupón ha expirado.'], 400);
    }

    return response()->json([
        'valido' => true,
        'codigo' => $cupon->Codigo,
        'descripcion' => $cupon->Descripcion,
        'tipoDescuento' => $cupon->TipoDescuento,
        'valorDescuento' => floatval($cupon->ValorDescuento),
        'mensaje' => 'Cupón aplicado con éxito.'
    ]);
});

