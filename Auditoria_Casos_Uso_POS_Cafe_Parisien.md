# Auditoria y Justificacion de Casos de Uso: Cafe Parisien

Este informe de auditoria tecnica evalua de forma detallada cuales de los 15 modulos y 4,500 casos de uso detallados en las especificaciones de requisitos de la carpeta [Requisitos_Sistema](file:///C:/XD/Examen-de-programacion/CafeteriaLaravel/Requisitos_Sistema) **son necesarios de implementar** en tu proyecto actual de **Cafe Parisien**, justificando la decision basada en la estructura actual de tu base de datos y modelo de negocio.

---

## Modulo 1: Gestion de Inventario y Almacen
* **Tablas asociadas en la BD**: `productos, categorias`
* **Estado actual del proyecto**: Parcialmente implementado. Actualmente el stock se descuenta por producto final en el checkout, no por insumo basico.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 30 (Descuento proporcional por receta o BOM). Es critico para controlar la merma de cafe en grano y leche.
* **[NO] Casos descartados (Baja prioridad)**: Casos del 150 al 300 (Gestion de multiples sucursales y bodegas). Al ser un local unico, la transferencia entre almacenes y sobrestock masivo es sobre-ingenieria.

---

## Modulo 2: Punto de Venta (POS) y Gestion de Caja
* **Tablas asociadas en la BD**: `pedidos, detallespedido, metodospago, facturas`
* **Estado actual del proyecto**: Parcialmente implementado. El cobro basico existe en el frontend, pero la auditoria de caja (Corte X y Z) esta ausente.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 50 al 90 (Apertura de caja, arqueo ciego, retiros parciales de efectivo y Corte Z obligatorio). Previene robos de efectivo.
* **[NO] Casos descartados (Baja prioridad)**: Casos del 250 al 300 (Pagos automaticos con criptomonedas o integracion con cajeros de cobro automatico). No justifican el costo de desarrollo para una cafeteria bistro boutique.

---

## Modulo 3: Fidelizacion y CRM (Club Parisien)
* **Tablas asociadas en la BD**: `puntoscliente, transaccionespuntos, usuarios`
* **Estado actual del proyecto**: Estructurado en base de datos. Existen las tablas, pero no hay logica implementada en el checkout para acumular o canjear.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 25 (Acumulacion automatica de puntos del 10% de la compra, y validacion de saldo al hacer checkout).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 150 al 200 (Campanas de marketing automatizadas con geocercas push y niveles VIP Oro/Plata dinamicos). Es mejor manejarlo de forma manual o con plantillas simples al inicio.

---

## Modulo 4: Integracion con Plataformas de Delivery (Uber/Rappi)
* **Tablas asociadas en la BD**: `metodosentrega, pedidos`
* **Estado actual del proyecto**: No implementado. Solo se soporta 'Para llevar' o 'Mesa' de forma local.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 15 (Mapeo de pedidos externos de delivery e ingreso manual al POS por el cajero).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 100 al 300 (Integracion directa por Webhooks/APIs de Uber/Rappi con despacho automatizado). Las APIs oficiales de Uber/Rappi cobran miles de dolares por integrarse directamente, es inviable para un negocio boutique.

---

## Modulo 5: Gestion de Personal, Turnos y Asistencia
* **Tablas asociadas en la BD**: `empleados, turnos, asignacionturnos`
* **Estado actual del proyecto**: Estructurado en base de datos. Solo editable mediante el administrador en base de datos cruda.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 20 (Registro de asistencia mediante PIN del empleado en la terminal de caja).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 200 al 300 (Calculo automatizado de primas vacacionales y bolsa de propinas ponderada automatizada en nomina). Se opera de forma manual en Excel mas facilmente.

---

## Modulo 6: Modulo KDS (Kitchen Display System)
* **Tablas asociadas en la BD**: `pedidos, detallespedido, estadospedido`
* **Estado actual del proyecto**: No implementado. El barista depende del ticket impreso o de que el administrador edite la base de datos.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 100 al 140 (Pantalla visual de comanda de cocina responsiva tactil con semaforizacion de retrasos).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 200 al 300 (Integracion de KDS con smartwatches de meseros o KDS de despacho para repartidores).

---

## Modulo 7: Reportes Financieros, Contabilidad y Auditoria
* **Tablas asociadas en la BD**: `logsauditoria, facturas, detallesfactura`
* **Estado actual del proyecto**: La tabla de logs de auditoria captura las inserciones, pero no hay interfaz de reportes de rentabilidad.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 50 al 80 (Calculo automatizado de rentabilidad del dia restando costos fijos cargados en el sistema).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 200 al 300 (Generacion e integracion de polizas contables automatizadas para ERP externos como SAP).

---

## Modulo 8: Facturacion Fiscal Automatizada
* **Tablas asociadas en la BD**: `facturas, detallesfactura`
* **Estado actual del proyecto**: Tablas estructuradas. No hay conexion con proveedor de timbrado fiscal.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 60 al 90 (Generacion de portal web simple de autofacturacion ingresando el codigo del ticket).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 150 al 300 (Timbrado fiscal en lote de facturas globales o cancelacion asincrona automatizada).

---

## Modulo 9: Gestion de Mesas y Reservaciones
* **Tablas asociadas en la BD**: `mesas, reservaciones`
* **Estado actual del proyecto**: Estructurado en base de datos. Falta logica de validacion de colisiones horarias en reservas.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 40 (Validacion en backend que impida colisiones de horario en la misma mesa fisica).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 150 al 300 (Mapa en 3D interactivo en la web del comensal). Una lista responsiva 2D es mas que suficiente.

---

## Modulo 10: Modulo de Compras y Relacion con Proveedores
* **Tablas asociadas en la BD**: `proveedores, ordenescompra, detallesordencompra`
* **Estado actual del proyecto**: Estructurado en base de datos. Requiere interaccion a traves del CRUD plano.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 20 (Carga de insumos recibidos de ordenes de compra directo al stock).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 100 al 300 (Licitacion comparativa automatizada de precios y contratos).

---

## Modulo 11: Configuracion del Sistema y Seguridad
* **Tablas asociadas en la BD**: `roles, usuarios, logsauditoria`
* **Estado actual del proyecto**: Estructurado en base de datos. Middleware basico de administracion activo.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 50 (Control de acceso por roles de usuario: Administrador, Cajero, Barista, Mesero).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 200 al 300 (Rotacion obligatoria de contraseÃ±as de seguridad bancaria cada 30 dias).

---

## Modulo 12: Modulo de Gestion de Recetas y Menu Dinamico
* **Tablas asociadas en la BD**: `productos, categorias, tiposmodificador, opcionesmodificador`
* **Estado actual del proyecto**: Estructurado en base de datos. Mapeado pero no implementado en el flujo de carrito.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 100 (Gestion de modificadores y extras con precio dinamico sumado en checkout).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 200 al 300 (Calibracion automatica del precio de recetas basado en APIs de inflacion).

---

## Modulo 13: Modulo de Encuestas y Calificaciones (Resenas)
* **Tablas asociadas en la BD**: `resenasproductos`
* **Estado actual del proyecto**: Tabla de resenas mapeada. Falta logica de envio de encuesta de satisfaccion automatizada.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 20 (Guardar calificacion de 1 a 5 estrellas del producto en el portal del comensal).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 150 al 300 (Analisis de sentimiento del texto de la resena usando Inteligencia Artificial).

---

## Modulo 14: Modulo de Marketing y Campanas de Cupones
* **Tablas asociadas en la BD**: `cupones`
* **Estado actual del proyecto**: Funciona de forma basica aplicando descuento de porcentaje en el checkout.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 40 (Validacion de fecha de vigencia y cupon de un solo uso por cliente).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 200 al 300 (Generacion dinamica masiva de cupones por cumpleanos usando webhooks de CRM).

---

## Modulo 15: Modulo de Mantenimiento de Activos y Equipos
* **Tablas asociadas en la BD**: `configuracionsistema`
* **Estado actual del proyecto**: No implementado.
* **[SI] Casos sugeridos a implementar (Necesarios)**: Casos del 1 al 15 (Registro basico de fechas de mantenimiento preventivo de maquinas de espresso).
* **[NO] Casos descartados (Baja prioridad)**: Casos del 100 al 300 (Sensores IoT conectados para reportar fallas electricas de refrigeracion de postres).

---
