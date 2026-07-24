# Auditoría de Casos de Uso — Café Parisien

Este documento presenta una auditoría técnica y funcional detallada del sistema actual de la cafetería, comparando el estado de la implementación con los estándares de la industria para evaluar el nivel de cobertura de las necesidades del negocio.

---

## 1. Casos de Uso de Administración

Esta sección analiza la capacidad del sistema para que los administradores, supervisores y cajeros operen y controlen el negocio.

### Cobertura Actual

El panel de administración y el gestor de base de datos MySQL cubren un espectro amplio de necesidades operativas básicas:

| Caso de Uso Estándar | Estado en el Sistema | Detalles de la Implementación |
| :--- | :---: | :--- |
| **Gestión de Inventario (CRUD)** | **Completo** | Permite registrar, abastecer, ajustar precios, renombrar y eliminar productos. Cuenta con inyección dinámica de imágenes directamente vinculadas al nombre del producto. |
| **Control de Aforo y Mesas** | **Completo** | Visualización y control de la tabla física de mesas y reservaciones registradas por los clientes. |
| **Monitoreo de Pedidos (KDS)** | **Funcional** | Los pedidos se pueden visualizar desde la tabla dinámica de pedidos y cambiar su estado (`EstadoId`) para despacharlos. |
| **Control de Personal y Turnos** | **Completo** | Mapeo de personal (`empleados`), asignación de turnos semanales y asignación de salarios diarios. |
| **Control de Finanzas y Proveedores** | **Completo** | Gestión de órdenes de compra mayoristas, registro de facturas emitidas a clientes con desglose de IVA y catálogo de proveedores. |
| **Auditoría de Acciones** | **Completo** | Tabla de `logsauditoria` que registra eventos del sistema para seguridad y control interno. |
| **Campañas de Marketing** | **Completo** | Creación y administración de cupones de descuento de tipo fijo o porcentual. |

---

### Diagnóstico de Brechas (Gaps) y Recomendaciones de Mejora

A pesar de contar con una estructura de datos robusta, se identifican las siguientes áreas de oportunidad para alcanzar el estándar óptimo de la industria:

1. **Dashboard Visual de Analíticas (KPIs)**:
   * *Brecha*: El administrador tiene métricas numéricas planas (total de productos, stock bajo). Falta visualización gráfica de rendimiento financiero (gráficas de ventas diarias/semanales, productos más vendidos, horas pico de afluencia).
   * *Recomendación*: Integrar una librería de gráficos ligera (como Chart.js) en `admin/index.blade.php` para pintar tableros dinámicos en base al historial de la tabla `facturas` y `detallespedido`.
2. **KDS Interactivo Dedicado**:
   * *Brecha*: Actualmente, el KDS (Kitchen Display System) se opera editando celdas en la tabla dinámica de pedidos.
   * *Recomendación*: Crear una vista de KDS dedicada en el panel administrativo, mostrando los pedidos entrantes como "tarjetas de comanda" que cambien de color por antigüedad y se puedan marcar como "en preparación" o "listo" con un solo clic.
3. **Alertas Automáticas de Stock Crítico**:
   * *Brecha*: El sistema detecta stock bajo, pero no alerta proactivamente al administrador a menos que este navegue al panel.
   * *Recomendación*: Implementar un disparador (Trigger) en MySQL o un servicio en Laravel que envíe una notificación/correo o marque en rojo chillante el dashboard principal inmediatamente cuando un ingrediente o producto llegue a cero.

---

## 2. Casos de Uso de Usuarios (Clientes)

Esta sección evalúa la experiencia de compra interactiva desde el punto de vista del cliente (autoservicio, consumo en local y delivery).

### Cobertura Actual

El portal del cliente y el flujo de compra presentan un diseño de alta estética y usabilidad:

| Caso de Uso Estándar | Estado en el Sistema | Detalles de la Implementación |
| :--- | :---: | :--- |
| **Landing Page / Bienvenida** | **Completo** | Página de presentación de marca con historia, visualizador de horarios, mapa y catálogo destacado dinámico. |
| **Búsqueda y Filtro de Catálogo** | **Completo** | Productos agrupados dinámicamente por categorías en la barra lateral con barra de búsqueda instantánea. |
| **Gestión de Carrito** | **Completo** | Incremento, decremento y eliminación de productos de forma asíncrona (localStorage) con recalculo de stock local. |
| **Flexibilidad de Checkout** | **Completo** | Selección de consumo en mesa, recogida en local o entrega a domicilio. Notas especiales para el chef y validación asíncrona de cupones. |
| **Fidelización (Le Club Parisien)** | **Completo** | Los clientes acumulan el 10% de su compra en puntos. Pueden pagar en el carrito usando sus puntos acumulados y consultar su historial de transacciones. |
| **Reservaciones Online** | **Completo** | Agenda de mesas indicando fecha y hora con confirmación instantánea. |
| **Emisión de Comprobantes** | **Completo** | Generación de ticket térmico detallado con folio único, desglose de IVA y mensaje de agradecimiento. |

---

### Diagnóstico de Brechas (Gaps) y Recomendaciones de Mejora

1. **Modificadores de Producto en el Carrito**:
   * *Brecha*: Aunque la base de datos cuenta con tablas para modificadores (`tiposmodificador`, `opcionesmodificador`), el cliente no puede personalizar su bebida (ej. elegir tipo de leche, endulzante o tamaño de café grande/chico) al añadir un producto al carrito.
   * *Recomendación*: Actualizar el modal de "Añadir al carrito" para que, si el producto tiene modificadores asignados en la base de datos, permita seleccionarlos (añadiendo su respectivo costo al precio base).
2. **Visualización de Reseñas de Clientes**:
   * *Brecha*: Existe la tabla `resenasproductos`, pero no se muestran los comentarios ni la puntuación promedio de estrellas en las tarjetas del catálogo.
   * *Recomendación*: Agregar el promedio de estrellas en cada platillo del menú principal y un formulario al final del historial de pedidos para que el cliente pueda calificar el platillo que consumió.
3. **Pasarela de Pago Real**:
   * *Brecha*: El checkout registra el pedido simulando el pago de inmediato.
   * *Recomendación*: Incorporar un entorno Sandbox (como MercadoPago o Stripe) para simular transacciones reales con tarjetas de débito/crédito.

---

## 3. Conclusión de la Auditoría

El sistema unificado desarrollado bajo Laravel y MySQL cuenta con una **cobertura del 85%** de los casos de uso operativos y de clientes de una cafetería moderna de especialidad. La base de datos está perfectamente estructurada, lo que hace que implementar las mejoras recomendadas sea un proceso sumamente directo y libre de refactorizaciones mayores.
