# Fase 1: Core de Ventas, Modificadores y Control de Caja (Corte Z)

Esta fase se enfoca en resolver las fugas operativas más costosas del negocio (la falta de cobro por extras y el control del efectivo en caja) utilizando las tablas ya existentes en la base de datos de Café Parisien.

---

## 1. Mapeo de Casos de Uso del POS y Carrito

Se implementarán los siguientes casos de uso del catálogo general de especificaciones:

| ID de Caso | Descripción Funcional | Frontend (UX/UI) | Backend (Lógica/BD) |
| :--- | :--- | :--- | :--- |
| **M-12-C-15** | Selección de modificadores y extras al agregar producto. | Ventana modal al hacer clic en "Agregar al Carrito" en el menú (`index.blade.php`). | Cargar dinámicamente desde `opcionesmodificador` vinculadas al producto. |
| **M-12-C-45** | Suma de precios adicionales al subtotal del carrito. | Actualización asíncrona del subtotal al marcar casillas (checkboxes) de extras. | Validación al hacer `checkout` en `ClientController.php` sumando modificadores al total de la orden. |
| **M-2-C-51** | Declaración obligatoria de saldo inicial al abrir caja. | Formulario inicial de apertura para cajeros con bloqueo de cobros si no se ha abierto. | Registro en tabla de control de caja con marca temporal y `UserID`. |
| **M-2-C-57** | Cierre de caja definitivo Z al final de la jornada. | Interfaz de cierre que imprime el ticket diario y deshabilita operaciones. | Bloqueo de facturación de la fecha y envío de correos automatizados con el PDF del cierre. |

---

## 2. Lista de Tareas y Asignación de Archivos

### 📥 Tarea 1.1: Frontend de Modificadores en el Catálogo
* **Archivos a modificar**: 
  - `resources/views/client/index.blade.php`: Agregar una ventana emergente (modal) dinámica que se dispare cuando el usuario haga clic en un café o producto que tenga modificadores disponibles en la base de datos.
  - `public/js/cart.js` (o lógica de carrito en `index.blade.php`): Serializar y empaquetar los modificadores seleccionados en el objeto JSON del carrito (`localStorage`).
* **Estado**: `[ ] Pendiente`

### 📤 Tarea 1.2: Procesamiento del Checkout con Extras
* **Archivos a modificar**:
  - `app/Http/Controllers/ClientController.php`: Modificar el método `checkout()` para recuperar las opciones de modificador seleccionadas, verificar existencias, registrar la relación en `detallesmodificadores` y recalcular el costo real sumando cada extra al total de la factura.
* **Estado**: `[ ] Pendiente`

### 💰 Tarea 1.3: Cierres de Caja (Corte X y Corte Z)
* **Archivos a modificar**:
  - `app/Http/Controllers/AdminController.php`: Agregar métodos `abrirCaja()`, `registrarRetiro()` y `cerrarCajaZ()`.
  - Crear la interfaz de administración para arqueo de caja con declaración a ciegas del cajero.
* **Estado**: `[ ] Pendiente`
