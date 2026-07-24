# Fase 3: Costeo, Recetas (BOM) y Proveedores

Esta fase aporta un control financiero avanzado al automatizar la relación entre el stock de materias primas y los platillos vendidos, asegurando el cálculo preciso del Food Cost.

---

## 1. Mapeo de Casos de Uso de Costeo e Inventario

Se implementarán los siguientes casos de uso del catálogo general de especificaciones:

| ID de Caso | Descripción Funcional | Frontend (UX/UI) | Backend (Lógica/BD) |
| :--- | :--- | :--- | :--- |
| **M-1-C-1** | Descuento proporcional de ingredientes básicos por receta. | Oculto al comensal. El administrador ve en el panel de stock el remanente en kg/litros. | El backend recorre la receta del producto y descuenta proporcionalmente el peso de cada ingrediente del inventario al procesar checkout. |
| **M-1-C-11** | Compra automática / Órdenes al punto de reorden. | Dashboard del administrador con alertas en rojo para insumos bajos. | Alerta por disparador en backend que notifica cuando un insumo básico baja del stock mínimo configurado. |
| **M-1-C-23** | Conversión automática de unidades de medida. | Formulario de creación de recetas simplificado (gramos por shot de café espresso). | Lógica de conversión (ej: 1 kg de café en grano rinde 55 espressos de 18g). |
| **M-10-C-45** | Recepción de mercancía de proveedores y suma a stock. | Interfaz de recepción de órdenes de compra con casillas para marcar lo recibido físicamente. | Sumar cantidades validadas a la tabla de insumos y actualizar el estado de la orden de compra a 'RECIBIDA'. |

---

## 2. Lista de Tareas y Asignación de Archivos

### 🌾 Tarea 3.1: Modelado de Relaciones de Recetas (BOM)
* **Archivos a crear/modificar**:
  - `database/migrations/...` **[NUEVA MIGRACIÓN]**: Crear tabla `recetas` que asocie `ProductoId` con un `InsumoId` y declare la `CantidadRequerida` (Ej: 0.018 kg de café por cada Espresso).
  - Modificar el backend del checkout para restar insumos en lugar de stock directo del producto si el producto tiene una receta asociada.
* **Estado**: `[ ] Pendiente`

### 📦 Tarea 3.2: Recepción de Compras de Proveedores
* **Archivos a modificar**:
  - `app/Http/Controllers/AdminController.php`: Diseñar un flujo interactivo para gestionar órdenes de compra desde el listado.
  - Implementar la sumatoria automática a la tabla de insumos al cambiar el estado de la orden a 'Recibida'.
* **Estado**: `[ ] Pendiente`
