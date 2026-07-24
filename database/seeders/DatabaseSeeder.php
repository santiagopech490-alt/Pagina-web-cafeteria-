<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        if (DB::table('roles')->count() == 0) {
            DB::table('roles')->insert([
                ['RolId' => 1, 'Nombre' => 'Administrador', 'Descripcion' => 'Acceso total al sistema', 'Activo' => 1],
                ['RolId' => 2, 'Nombre' => 'Cliente', 'Descripcion' => 'Usuario cliente final', 'Activo' => 1],
                ['RolId' => 3, 'Nombre' => 'Barista', 'Descripcion' => 'Personal de preparación y servicio', 'Activo' => 1],
            ]);
        }

        // 2. Usuarios (Admin por defecto)
        if (DB::table('usuarios')->where('Username', 'admin')->count() == 0) {
            DB::table('usuarios')->insert([
                'Username' => 'admin',
                'PasswordHash' => Hash::make('admin123'),
                'RolId' => 1,
                'NombreCompleto' => 'Administrador General',
                'Email' => 'admin@cafeparisien.com',
                'Activo' => 1,
            ]);
        }

        // 3. Categorías
        if (DB::table('categorias')->count() == 0) {
            DB::table('categorias')->insert([
                ['CategoriaId' => 1, 'Nombre' => 'Bebidas Calientes', 'Icono' => '☕', 'Orden' => 1, 'Activa' => 1],
                ['CategoriaId' => 2, 'Nombre' => 'Bebidas Frías', 'Icono' => '🍹', 'Orden' => 2, 'Activa' => 1],
                ['CategoriaId' => 3, 'Nombre' => 'Repostería Fina', 'Icono' => '🥐', 'Orden' => 3, 'Activa' => 1],
                ['CategoriaId' => 4, 'Nombre' => 'Especialidades de la Casa', 'Icono' => '⚜️', 'Orden' => 4, 'Activa' => 1],
            ]);
        }

        // 4. Productos
        if (DB::table('productos')->count() == 0) {
            DB::table('productos')->insert([
                ['Codigo' => 'P01', 'Nombre' => 'Papas pou', 'Precio' => 100.00, 'Existencia' => 100, 'CategoriaId' => 4, 'Disponible' => 1, 'Destacado' => 1],
                ['Codigo' => 'P02', 'Nombre' => 'Torta de empanizado', 'Precio' => 34.00, 'Existencia' => 60, 'CategoriaId' => 4, 'Disponible' => 1, 'Destacado' => 1],
                ['Codigo' => 'P03', 'Nombre' => 'Leche de burra', 'Precio' => 10.00, 'Existencia' => 122, 'CategoriaId' => 4, 'Disponible' => 1, 'Destacado' => 1],
                ['Codigo' => 'P04', 'Nombre' => 'Especialidad de Furina', 'Precio' => 85.00, 'Existencia' => 45, 'CategoriaId' => 4, 'Disponible' => 1, 'Destacado' => 1],
                ['Codigo' => 'P05', 'Nombre' => 'Croissant Classique', 'Precio' => 45.00, 'Existencia' => 80, 'CategoriaId' => 3, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P06', 'Nombre' => 'Pain au Chocolat', 'Precio' => 50.00, 'Existencia' => 70, 'CategoriaId' => 3, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P07', 'Nombre' => 'Café Espresso', 'Precio' => 35.00, 'Existencia' => 200, 'CategoriaId' => 1, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P08', 'Nombre' => 'Café Latte', 'Precio' => 55.00, 'Existencia' => 150, 'CategoriaId' => 1, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P09', 'Nombre' => 'Cappuccino', 'Precio' => 52.00, 'Existencia' => 150, 'CategoriaId' => 1, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P10', 'Nombre' => 'Tarte aux Pommes', 'Precio' => 75.00, 'Existencia' => 40, 'CategoriaId' => 3, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P11', 'Nombre' => 'Quiche Lorraine', 'Precio' => 90.00, 'Existencia' => 35, 'CategoriaId' => 3, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P12', 'Nombre' => 'Crème Brûlée', 'Precio' => 70.00, 'Existencia' => 50, 'CategoriaId' => 3, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P13', 'Nombre' => 'Macaron Assorti', 'Precio' => 40.00, 'Existencia' => 120, 'CategoriaId' => 3, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P14', 'Nombre' => 'Baguette Tradition', 'Precio' => 25.00, 'Existencia' => 90, 'CategoriaId' => 3, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P15', 'Nombre' => 'Chocolat Chaud', 'Precio' => 48.00, 'Existencia' => 100, 'CategoriaId' => 1, 'Disponible' => 1, 'Destacado' => 0],
                ['Codigo' => 'P16', 'Nombre' => 'Limonade Maison', 'Precio' => 38.00, 'Existencia' => 80, 'CategoriaId' => 2, 'Disponible' => 1, 'Destacado' => 0],
            ]);
        }

        // 5. Zonas
        if (DB::table('zonas')->count() == 0) {
            DB::table('zonas')->insert([
                ['ZonaId' => 1, 'Nombre' => 'Salón Principal', 'Descripcion' => 'Área central interior del restaurante'],
                ['ZonaId' => 2, 'Nombre' => 'Terraza', 'Descripcion' => 'Área al aire libre con vista al jardín'],
                ['ZonaId' => 3, 'Nombre' => 'Mezzanine VIP', 'Descripcion' => 'Zona reservada en nivel superior'],
                ['ZonaId' => 4, 'Nombre' => 'Barra de Baristas', 'Descripcion' => 'Periqueras y asientos individuales en barra'],
            ]);
        }

        // 6. Mesas
        if (DB::table('mesas')->count() == 0) {
            DB::table('mesas')->insert([
                ['NumeroMesa' => 'Mesa 01', 'ZonaId' => 1, 'Capacidad' => 4, 'Estado' => 'Disponible', 'Ubicacion' => 'Entrada al salón'],
                ['NumeroMesa' => 'Mesa 02', 'ZonaId' => 1, 'Capacidad' => 4, 'Estado' => 'Disponible', 'Ubicacion' => 'Junto a ventanal principal'],
                ['NumeroMesa' => 'Mesa 03', 'ZonaId' => 1, 'Capacidad' => 6, 'Estado' => 'Ocupada', 'Ubicacion' => 'Centro del salón'],
                ['NumeroMesa' => 'Mesa 04', 'ZonaId' => 2, 'Capacidad' => 2, 'Estado' => 'Disponible', 'Ubicacion' => 'Terraza vista jardín'],
                ['NumeroMesa' => 'Mesa 05', 'ZonaId' => 2, 'Capacidad' => 4, 'Estado' => 'Disponible', 'Ubicacion' => 'Esquina terraza'],
                ['NumeroMesa' => 'Mesa 06', 'ZonaId' => 3, 'Capacidad' => 8, 'Estado' => 'Reservada', 'Ubicacion' => 'Reservado Mezzanine VIP'],
                ['NumeroMesa' => 'Mesa 07', 'ZonaId' => 4, 'Capacidad' => 2, 'Estado' => 'Disponible', 'Ubicacion' => 'Periquera Barra #1'],
                ['NumeroMesa' => 'Mesa 08', 'ZonaId' => 4, 'Capacidad' => 2, 'Estado' => 'Ocupada', 'Ubicacion' => 'Periquera Barra #2'],
            ]);
        }

        // 7. Métodos de Entrega
        if (DB::table('metodosentrega')->count() == 0) {
            DB::table('metodosentrega')->insert([
                ['MetodoEntregaId' => 1, 'Nombre' => 'En Restaurante / Mesa', 'Descripcion' => 'Consumo local en instalaciones'],
                ['MetodoEntregaId' => 2, 'Nombre' => 'Para Llevar', 'Descripcion' => 'Recogida en mostrador'],
                ['MetodoEntregaId' => 3, 'Nombre' => 'Servicio a Domicilio', 'Descripcion' => 'Envío por repartidor'],
            ]);
        }

        // 8. Métodos de Pago
        if (DB::table('metodospago')->count() == 0) {
            DB::table('metodospago')->insert([
                ['Clave' => 'EFECTIVO', 'Etiqueta' => 'Pago en Efectivo', 'Activo' => 1],
                ['Clave' => 'TARJETA', 'Etiqueta' => 'Tarjeta de Crédito / Débito', 'Activo' => 1],
                ['Clave' => 'PUNTOS', 'Etiqueta' => 'Puntos de Lealtad', 'Activo' => 1],
                ['Clave' => 'TRANSFERENCIA', 'Etiqueta' => 'Transferencia Bancaria SPEI', 'Activo' => 1],
            ]);
        }

        // 9. Configuración del Sistema
        if (DB::table('configuracionsistema')->count() == 0) {
            DB::table('configuracionsistema')->insert([
                ['Clave' => 'NOMBRE_ESTABLECIMIENTO', 'Valor' => 'Café Parisien - L\'Élégance'],
                ['Clave' => 'DIRECCION_MATRIZ', 'Valor' => 'Av. Campos Elíseos 123, Polanco, CDMX'],
                ['Clave' => 'TELEFONO_CONTACTO', 'Valor' => '+52 (55) 8765-4321'],
                ['Clave' => 'PORCENTAJE_IVA', 'Valor' => '16%'],
                ['Clave' => 'PUNTOS_POR_PESO', 'Valor' => '1 punto por cada $10.00 de compra'],
                ['Clave' => 'MENSAJE_BIENVENIDA', 'Valor' => 'Bienvenido a Café Parisien, le esperamos con lo mejor de la alta repostería.'],
            ]);
        }

        // 10. Tipos de Modificador
        if (DB::table('tiposmodificador')->count() == 0) {
            DB::table('tiposmodificador')->insert([
                ['TipoId' => 1, 'Nombre' => 'Tipo de Leche'],
                ['TipoId' => 2, 'Nombre' => 'Saborizante / Jarabe Extra'],
                ['TipoId' => 3, 'Nombre' => 'Nivel de Dulzor / Azúcar'],
            ]);
        }

        // 11. Opciones de Modificador
        if (DB::table('opcionesmodificador')->count() == 0) {
            DB::table('opcionesmodificador')->insert([
                ['OpcionId' => 1, 'TipoId' => 1, 'Nombre' => 'Leche Entera', 'PrecioExtra' => 0.00],
                ['OpcionId' => 2, 'TipoId' => 1, 'Nombre' => 'Leche Deslactosada', 'PrecioExtra' => 0.00],
                ['OpcionId' => 3, 'TipoId' => 1, 'Nombre' => 'Leche de Almendras', 'PrecioExtra' => 10.00],
                ['OpcionId' => 4, 'TipoId' => 1, 'Nombre' => 'Leche de Avena', 'PrecioExtra' => 12.00],
                ['OpcionId' => 5, 'TipoId' => 2, 'Nombre' => 'Vainilla Francesa', 'PrecioExtra' => 15.00],
                ['OpcionId' => 6, 'TipoId' => 2, 'Nombre' => 'Caramelo Salado', 'PrecioExtra' => 15.00],
                ['OpcionId' => 7, 'TipoId' => 2, 'Nombre' => 'Avellana Gourmet', 'PrecioExtra' => 15.00],
                ['OpcionId' => 8, 'TipoId' => 3, 'Nombre' => 'Dulzor Normal (100%)', 'PrecioExtra' => 0.00],
                ['OpcionId' => 9, 'TipoId' => 3, 'Nombre' => 'Medio Dulzor (50%)', 'PrecioExtra' => 0.00],
                ['OpcionId' => 10, 'TipoId' => 3, 'Nombre' => 'Sin Azúcar (0%)', 'PrecioExtra' => 0.00],
            ]);
        }

        // 12. Producto Modificadores (asociar bebidas a modificadores)
        if (DB::table('productomodificadores')->count() == 0) {
            $bebidasIds = DB::table('productos')->pluck('ProductoId');
            foreach ($bebidasIds as $pId) {
                DB::table('productomodificadores')->insert([
                    ['ProductoId' => $pId, 'TipoId' => 1],
                    ['ProductoId' => $pId, 'TipoId' => 2],
                    ['ProductoId' => $pId, 'TipoId' => 3],
                ]);
            }
        }
    }
}
