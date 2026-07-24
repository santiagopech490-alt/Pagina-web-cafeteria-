<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Roles
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id('RolId');
                $table->string('Nombre');
                $table->string('Descripcion')->nullable();
                $table->boolean('Activo')->default(true);
            });
        }

        // 2. Usuarios
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id('UsuarioId');
                $table->string('Username')->unique();
                $table->string('PasswordHash');
                $table->unsignedBigInteger('RolId')->default(2);
                $table->string('NombreCompleto')->nullable();
                $table->string('Email')->nullable();
                $table->boolean('Activo')->default(true);
            });
        }

        // 3. Categorías
        if (!Schema::hasTable('categorias')) {
            Schema::create('categorias', function (Blueprint $table) {
                $table->id('CategoriaId');
                $table->string('Nombre');
                $table->string('Icono')->default('🥐');
                $table->integer('Orden')->default(1);
                $table->boolean('Activa')->default(true);
            });
        }

        // 4. Productos
        if (!Schema::hasTable('productos')) {
            Schema::create('productos', function (Blueprint $table) {
                $table->id('ProductoId');
                $table->string('Codigo')->unique();
                $table->string('Nombre');
                $table->decimal('Precio', 10, 2);
                $table->integer('Existencia')->default(100);
                $table->unsignedBigInteger('CategoriaId')->nullable();
                $table->boolean('Disponible')->default(true);
                $table->boolean('Destacado')->default(false);
            });
        }

        // 5. Zonas
        if (!Schema::hasTable('zonas')) {
            Schema::create('zonas', function (Blueprint $table) {
                $table->id('ZonaId');
                $table->string('Nombre');
                $table->string('Descripcion')->nullable();
                $table->string('Icono')->nullable();
                $table->boolean('Activa')->default(true);
            });
        }

        // 6. Mesas
        if (!Schema::hasTable('mesas')) {
            Schema::create('mesas', function (Blueprint $table) {
                $table->id('MesaId');
                $table->string('NumeroMesa');
                $table->unsignedBigInteger('ZonaId')->nullable();
                $table->integer('Capacidad')->default(4);
                $table->string('Estado')->default('Disponible'); // Disponible, Ocupada, Reservada
                $table->string('Ubicacion')->nullable();
            });
        }

        // 7. Reservaciones
        if (!Schema::hasTable('reservaciones')) {
            Schema::create('reservaciones', function (Blueprint $table) {
                $table->id('ReservacionId');
                $table->unsignedBigInteger('MesaId')->nullable();
                $table->string('NombreCliente');
                $table->string('Telefono')->nullable();
                $table->integer('NumeroPersonas')->default(2);
                $table->dateTime('FechaHora');
                $table->string('Estado')->default('Confirmada');
            });
        }

        // 8. Métodos de Entrega
        if (!Schema::hasTable('metodosentrega')) {
            Schema::create('metodosentrega', function (Blueprint $table) {
                $table->id('MetodoEntregaId');
                $table->string('Nombre');
                $table->string('Descripcion')->nullable();
            });
        }

        // 9. Métodos de Pago
        if (!Schema::hasTable('metodospago')) {
            Schema::create('metodospago', function (Blueprint $table) {
                $table->id('MetodoPagoId');
                $table->string('Clave');
                $table->string('Etiqueta');
                $table->boolean('Activo')->default(true);
            });
        }

        // 10. Cupónes
        if (!Schema::hasTable('cupones')) {
            Schema::create('cupones', function (Blueprint $table) {
                $table->id('CuponId');
                $table->string('Codigo')->unique();
                $table->string('Descripcion')->nullable();
                $table->string('TipoDescuento')->default('porcentaje'); // porcentaje, fijo
                $table->decimal('ValorDescuento', 10, 2);
                $table->integer('UsosMaximos')->default(100);
                $table->integer('UsosActuales')->default(0);
                $table->date('ValidoDesde')->nullable();
                $table->date('ValidoHasta')->nullable();
                $table->boolean('Activo')->default(true);
            });
        }

        // 11. Pedidos
        if (!Schema::hasTable('pedidos')) {
            Schema::create('pedidos', function (Blueprint $table) {
                $table->id('PedidoId');
                $table->string('Folio')->unique();
                $table->dateTime('FechaPedido')->nullable()->useCurrent();
                $table->integer('EstadoId')->default(1);
                $table->decimal('Subtotal', 10, 2)->default(0.00);
                $table->decimal('Descuento', 10, 2)->default(0.00);
                $table->decimal('Total', 10, 2);
                $table->unsignedBigInteger('MetodoEntregaId')->nullable();
                $table->string('MetodoEntrega')->nullable();
                $table->string('Direccion')->nullable();
                $table->string('NumeroMesa')->nullable();
                $table->text('Notas')->nullable();
                $table->unsignedBigInteger('CuponId')->nullable();
                $table->unsignedBigInteger('UsuarioId')->nullable();
            });
        }

        // 12. Detalles de Pedido
        if (!Schema::hasTable('detallespedido')) {
            Schema::create('detallespedido', function (Blueprint $table) {
                $table->id('DetalleId');
                $table->unsignedBigInteger('PedidoId');
                $table->unsignedBigInteger('ProductoId');
                $table->integer('Cantidad');
                $table->decimal('PrecioUnitario', 10, 2)->default(0.00);
                $table->decimal('Subtotal', 10, 2)->default(0.00);
            });
        }

        // 13. Facturas
        if (!Schema::hasTable('facturas')) {
            Schema::create('facturas', function (Blueprint $table) {
                $table->id('FacturaId');
                $table->unsignedBigInteger('PedidoId')->nullable();
                $table->string('Folio')->unique();
                $table->dateTime('FechaEmision')->useCurrent();
                $table->decimal('Total', 10, 2);
                $table->string('RFC')->nullable();
                $table->string('RazonSocial')->nullable();
            });
        }

        // 14. Detalles de Factura
        if (!Schema::hasTable('detallesfactura')) {
            Schema::create('detallesfactura', function (Blueprint $table) {
                $table->id('DetalleFacturaId');
                $table->unsignedBigInteger('FacturaId');
                $table->string('Concepto');
                $table->integer('Cantidad');
                $table->decimal('PrecioUnitario', 10, 2);
                $table->decimal('Subtotal', 10, 2);
            });
        }

        // 15. Empleados
        if (!Schema::hasTable('empleados')) {
            Schema::create('empleados', function (Blueprint $table) {
                $table->id('EmpleadoId');
                $table->unsignedBigInteger('UsuarioId')->nullable();
                $table->string('Nombre');
                $table->string('ApellidoP')->nullable();
                $table->string('ApellidoM')->nullable();
                $table->string('CURP')->nullable();
                $table->string('Puesto')->default('Barista');
                $table->decimal('SalarioDia', 10, 2)->default(300.00);
                $table->string('Telefono')->nullable();
                $table->date('FechaIngreso')->nullable();
                $table->boolean('Activo')->default(true);
            });
        }

        // 16. Turnos
        if (!Schema::hasTable('turnos')) {
            Schema::create('turnos', function (Blueprint $table) {
                $table->id('TurnoId');
                $table->string('Nombre');
                $table->time('HoraInicio');
                $table->time('HoraFin');
            });
        }

        // 17. Asignación Turnos
        if (!Schema::hasTable('asignacionturnos')) {
            Schema::create('asignacionturnos', function (Blueprint $table) {
                $table->id('AsignacionId');
                $table->unsignedBigInteger('EmpleadoId');
                $table->unsignedBigInteger('TurnoId');
                $table->date('Fecha');
            });
        }

        // 18. Configuración Sistema
        if (!Schema::hasTable('configuracionsistema')) {
            Schema::create('configuracionsistema', function (Blueprint $table) {
                $table->id('ConfiguracionId');
                $table->string('Clave')->unique();
                $table->text('Valor');
            });
        }

        // 19. Notificaciones
        if (!Schema::hasTable('notificaciones')) {
            Schema::create('notificaciones', function (Blueprint $table) {
                $table->id('NotificacionId');
                $table->unsignedBigInteger('UsuarioId')->nullable();
                $table->string('Titulo');
                $table->text('Cuerpo');
                $table->boolean('Leida')->default(false);
                $table->dateTime('Fecha')->useCurrent();
            });
        }

        // 20. Logs Auditoría
        if (!Schema::hasTable('logsauditoria')) {
            Schema::create('logsauditoria', function (Blueprint $table) {
                $table->id('LogId');
                $table->unsignedBigInteger('UsuarioId')->nullable();
                $table->string('Accion');
                $table->text('Detalle')->nullable();
                $table->dateTime('Fecha')->useCurrent();
            });
        }

        // 21. Puntos Cliente
        if (!Schema::hasTable('puntoscliente')) {
            Schema::create('puntoscliente', function (Blueprint $table) {
                $table->id('PuntoId');
                $table->unsignedBigInteger('UsuarioId')->unique();
                $table->integer('PuntosAcumulados')->default(0);
            });
        }

        // 22. Transacciones Puntos
        if (!Schema::hasTable('transaccionespuntos')) {
            Schema::create('transaccionespuntos', function (Blueprint $table) {
                $table->id('TransaccionId');
                $table->unsignedBigInteger('UsuarioId');
                $table->integer('Puntos');
                $table->string('TipoTransaccion'); // ACUMULACION, CANJE
                $table->string('Concepto')->nullable();
                $table->dateTime('Fecha')->useCurrent();
            });
        }

        // 23. Reseñas Productos
        if (!Schema::hasTable('resenasproductos')) {
            Schema::create('resenasproductos', function (Blueprint $table) {
                $table->id('ResenaId');
                $table->unsignedBigInteger('ProductoId');
                $table->unsignedBigInteger('UsuarioId')->nullable();
                $table->integer('Calificacion')->default(5);
                $table->text('Comentario')->nullable();
                $table->dateTime('Fecha')->useCurrent();
            });
        }

        // 24. Proveedores
        if (!Schema::hasTable('proveedores')) {
            Schema::create('proveedores', function (Blueprint $table) {
                $table->id('ProveedorId');
                $table->string('RFC')->nullable();
                $table->string('RazonSocial');
                $table->string('Contacto')->nullable();
                $table->string('Telefono')->nullable();
                $table->string('Email')->nullable();
                $table->boolean('Activo')->default(true);
            });
        }

        // 25. Órdenes de Compra
        if (!Schema::hasTable('ordenescompra')) {
            Schema::create('ordenescompra', function (Blueprint $table) {
                $table->id('OrdenId');
                $table->unsignedBigInteger('ProveedorId');
                $table->dateTime('FechaOrden')->useCurrent();
                $table->decimal('Total', 10, 2);
                $table->string('Estado')->default('Pendiente');
            });
        }

        // 26. Modificadores
        if (!Schema::hasTable('tiposmodificador')) {
            Schema::create('tiposmodificador', function (Blueprint $table) {
                $table->id('TipoId');
                $table->string('Nombre');
            });
        }

        if (!Schema::hasTable('opcionesmodificador')) {
            Schema::create('opcionesmodificador', function (Blueprint $table) {
                $table->id('OpcionId');
                $table->unsignedBigInteger('TipoId');
                $table->string('Nombre');
                $table->decimal('PrecioExtra', 10, 2)->default(0.00);
            });
        }

        if (!Schema::hasTable('productomodificadores')) {
            Schema::create('productomodificadores', function (Blueprint $table) {
                $table->id('ProductoModificadorId');
                $table->unsignedBigInteger('ProductoId');
                $table->unsignedBigInteger('TipoId');
            });
        }

        // 29. Recetas
        if (!Schema::hasTable('recetas')) {
            Schema::create('recetas', function (Blueprint $table) {
                $table->id('RecetaId');
                $table->unsignedBigInteger('ProductoId');
                $table->unsignedBigInteger('InsumoProductoId');
                $table->decimal('CantidadRequerida', 10, 2)->default(1.00);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('recetas');
        Schema::dropIfExists('productomodificadores');
        Schema::dropIfExists('opcionesmodificador');
        Schema::dropIfExists('tiposmodificador');
        Schema::dropIfExists('tiposmodificador');
        Schema::dropIfExists('ordenescompra');
        Schema::dropIfExists('proveedores');
        Schema::dropIfExists('resenasproductos');
        Schema::dropIfExists('transaccionespuntos');
        Schema::dropIfExists('puntoscliente');
        Schema::dropIfExists('logsauditoria');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('configuracionsistema');
        Schema::dropIfExists('asignacionturnos');
        Schema::dropIfExists('turnos');
        Schema::dropIfExists('empleados');
        Schema::dropIfExists('detallesfactura');
        Schema::dropIfExists('facturas');
        Schema::dropIfExists('detallespedido');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('cupones');
        Schema::dropIfExists('metodospago');
        Schema::dropIfExists('metodosentrega');
        Schema::dropIfExists('reservaciones');
        Schema::dropIfExists('mesas');
        Schema::dropIfExists('zonas');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
    }
};
