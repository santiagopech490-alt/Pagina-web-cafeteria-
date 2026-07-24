# Fase 2: Cocina (KDS) y Gestión de Reservaciones

Esta fase optimiza la operación dentro de la cafetería reduciendo los tiempos de preparación y organizando el plano físico de mesas y reservas de clientes.

---

## 1. Mapeo de Casos de Uso del KDS y Reservaciones

Se implementarán los siguientes casos de uso del catálogo general de especificaciones:

| ID de Caso | Descripción Funcional | Frontend (UX/UI) | Backend (Lógica/BD) |
| :--- | :--- | :--- | :--- |
| **M-6-C-101** | Consola visual de pedidos (KDS) en tiempo real para barra. | Vista de tarjetas interactivas responsivas con sonido de alerta al entrar nueva comanda. | Query optimizado a `pedidos` con estados 'PAGADO' o 'PREPARANDO'. |
| **M-6-C-104** | Semaforización visual de tiempos de preparación. | La tarjeta cambia a color amarillo a los 8 minutos, y a rojo a los 12 minutos. | Cálculo de diferencia de minutos entre `Created_At` del pedido y la hora del sistema. |
| **M-6-C-107** | Despacho de comanda por parte del barista. | Botón de "Listo" en la tarjeta que la remueve de pantalla de preparación. | Cambio de estado de pedido en backend a 'LISTO' y escritura en `historialestadospedido`. |
| **M-9-C-45** | Validación anti-traslape de horarios en reservaciones. | Mensaje de advertencia interactivo si se selecciona una mesa ya apartada. | Validación en backend que comprueba que la fecha/hora y la mesa no colisionen con registros en `reservaciones`. |

---

## 2. Lista de Tareas y Asignación de Archivos

### 🖥️ Tarea 2.1: Pantalla del KDS del Barista/Cocinero
* **Archivos a modificar**:
  - `routes/web.php`: Añadir la ruta `/kds` exclusiva para empleados con privilegios correspondientes.
  - `app/Http/Controllers/AdminController.php`: Agregar método `kdsView()` que retorne los pedidos activos.
  - `resources/views/admin/kds.blade.php` **[NUEVA VISTA]**: Interfaz premium de tarjetas responsivas táctiles que consultan nuevos pedidos mediante polling corto AJAX.
* **Estado**: `[ ] Pendiente`

### 🗓️ Tarea 2.2: Lógica de Colisión en Reservaciones
* **Archivos a modificar**:
  - `app/Http/Controllers/ClientController.php`: Modificar el método de registro de reservación para inyectar una validación de tiempo. Si la mesa ya está apartada en una ventana de 2 horas previas o posteriores al horario solicitado, denegar y sugerir otra mesa u horario.
* **Estado**: `[ ] Pendiente`
