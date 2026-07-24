# Auditoría de Casos de Uso: 300 Requerimientos Faltantes

Este documento presenta una auditoría profunda de la operación de un restaurante-cafetería de clase mundial y detalla exactamente **300 casos de uso críticos que la aplicación actual de Café Parisien NO posee**, estructurados en 6 módulos operativos.

---

## Módulo 1: Inventario, Almacén, Recetas y Proveedores (1-50)

1. Descuento proporcional de ingredientes básicos por receta (BOM).
2. Definición de mermas por caducidad de insumos.
3. Registro de mermas por caída de alimentos en preparación.
4. Ajuste de stock por robo hormiga auditado.
5. Alertas de inventario mínimo por SMS a gerencia.
6. Transferencia de insumos entre diferentes sucursales.
7. Valoración de inventario bajo el método PEPS (Primeras Entradas, Primeras Salidas).
8. Valoración de inventario bajo el método UEPS (Últimas Entradas, Primeras Salidas).
9. Valoración de inventario bajo el método de Promedio Ponderado.
10. Registro de devoluciones de insumos defectuosos a proveedores.
11. Generación automática de órdenes de compra al llegar al punto de reorden.
12. Control de caducidades con semaforización de frescura.
13. Registro de temperatura de cámaras de refrigeración ligado al inventario.
14. Seguimiento de insumos congelados y tiempos de descongelación.
15. Gestión de envases retornables con proveedores.
16. Licitación comparativa de precios de insumos entre proveedores.
17. Registro de facturas de proveedores a crédito (cuentas por pagar).
18. Programación de pagos semanales a proveedores.
19. Control de mermas en barra de barista por calibración de molino.
20. Control de lotes de insumos para rastreabilidad en caso de sospecha de intoxicación.
21. Auditoría física de inventario a ciegas por personal externo.
22. Registro de sobrestock con promociones sugeridas para salida rápida.
23. Conversión automática de unidades de medida (de kilogramos a gramos en recetas).
24. Registro de mermas por cortesía o degustación de empleados.
25. Control de stock en consignación de café de especialidad.
26. Registro de mermas por error en toma de comanda.
27. Seguimiento de merma por corte incorrecto de panadería.
28. Programación de inventarios cíclicos diarios por insumos de alto costo.
29. Cálculo de merma teórica vs merma real semanal.
30. Alerta de cambio de precio de insumo mayor al 10% en el mercado.
31. Historial de cotizaciones de proveedores por insumo.
32. Asociación de proveedores suplentes por insumo crítico.
33. Clasificación ABC de insumos por costo y volumen de rotación.
34. Registro de inventario en tránsito desde centros de distribución.
35. Registro de mermas por falla de energía eléctrica en refrigeradores.
36. Control de inventario de artículos de limpieza y consumibles de baño.
37. Control de vajilla y cristalería con registro de roturas por mesero.
38. Registro de activos fijos de cocina (licuadoras, espresseras) y sus mantenimientos.
39. Cálculo del rendimiento de insumos (Ej. cuántas tazas rinde un kilo de café).
40. Control de insumos de empaque ecológico para envío a domicilio.
41. Registro de órdenes de compra con firma digital del gerente.
42. Control de rechazo de mercancía en andén por mala calidad.
43. Programación de entregas de proveedores en horarios no pico del local.
44. Reporte de desviación de costos de recetas por inflación de insumos.
45. Gestión de órdenes de compra parciales (proveedor entrega solo una parte).
46. Control de merma por platos devueltos por insatisfacción del cliente.
47. Ajuste de recetas estacionales según disponibilidad de ingredientes.
48. Control de inventario de uniformes del personal.
49. Registro de inventario de material publicitario y menús físicos.
50. Conciliación de notas de remisión de proveedores contra facturas fiscales recibidas.

---

## Módulo 2: Operaciones de Caja, Finanzas y Contabilidad (51-100)

51. Apertura de caja con declaración obligatoria de saldo inicial (fondo de caja).
52. Registro de retiros de efectivo parciales durante el turno (gastos menores).
53. Registro de depósitos de efectivo a bóveda durante el turno por exceso de efectivo.
54. Arqueo de caja a ciegas al final de la jornada (cierre sin saber la cifra esperada).
55. Reporte automático de discrepancia en caja (faltantes y sobrantes).
56. Cierre parcial de turno (Corte X) para relevo de cajero.
57. Cierre definitivo de jornada (Corte Z) con bloqueo de ventas.
58. Envío automatizado de reportes financieros del Corte Z por correo a socios.
59. Registro de métodos de pago combinados (Ej: Mitad tarjeta, mitad efectivo).
60. Integración y cobro mediante vales de despensa físicos y electrónicos.
61. Registro de retenciones de comisiones bancarias por cobro con tarjeta.
62. Control de reembolsos de gastos menores autorizados por gerencia.
63. Integración con el sistema de facturación fiscal SAT/Hacienda nacional.
64. Generación de portal web para autofacturación del cliente usando folio de ticket.
65. Registro de facturación fiscal global diaria por ventas a público general.
66. Reporte de cobros de propinas integrados en tarjeta y su retención.
67. Control de cuentas por cobrar de clientes frecuentes o corporativos.
68. Reporte dinámico de punto de equilibrio del restaurante en tiempo real.
69. Conciliación automática de estados de cuenta bancarios contra cobros del POS.
70. Reporte de rentabilidad neto restando costos fijos (luz, agua, renta).
71. Control y registro de depósitos de garantía por eventos privados.
72. Gestión de reembolsos a clientes aprobados por gerencia.
73. Registro de pérdidas monetarias por billetes falsos detectados.
74. Control de comisiones a repartidores de plataformas externas (Uber, Rappi).
75. Programación de presupuestos mensuales de compras por departamento.
76. Registro de pasivos por cupones de descuento no canjeados.
77. Reporte de valor neto de puntos de lealtad en circulación en el balance.
78. Historial de auditorías financieras del sistema por usuario administrativo.
79. Bloqueo de cobros a mesas con comandas abiertas no impresas.
80. Control de descuentos autorizados por rango de puesto (Ej: Cajero max 5%, Gerente 20%).
81. Registro de propina sugerida en terminal bancaria integrada.
82. Bloqueo automático de caja si se detectan más de 3 discrepancias consecutivas.
83. Envío de alertas de retiros de efectivo altos al celular del dueño.
84. Reporte de costo operativo de terminales bancarias por tipo de tarjeta (crédito/débito).
85. Control financiero de cancelaciones de facturas fiscales emitidas.
86. Reporte de impuestos cobrados acumulados (IVA, IEPS).
87. Control de caja chica independiente para baristas.
88. Registro contable del valor de depreciación de maquinaria del local.
89. Control de cobros con monedas extranjeras (cálculo de tipo de cambio).
90. Integración con pasarelas de pago de criptomonedas.
91. Reporte de pérdidas financieras por mermas registradas en el mes.
92. Generación automática de pólizas contables de ventas diarias.
93. Control de préstamos y adelantos de nómina a empleados.
94. Registro de multas y recargos fiscales del establecimiento.
95. Alerta de margen de ganancia neto menor al 15% en el día.
96. Conciliación de ventas de plataformas delivery contra facturas de comisión.
97. Registro de comisiones por transacciones de pago con QR (CoDi, etc.).
98. Reporte financiero de rendimiento por hora en el local.
99. Control de reembolsos por quejas por mal servicio o comida fría.
100. Registro de transferencias de fondos entre cajas del mismo local.

---

## Módulo 3: Cocina, KDS, Barra y Producción (101-150)

101. Pantalla de KDS (Kitchen Display System) para la estación de barista.
102. Pantalla de KDS para la estación de repostería y postres.
103. Pantalla de KDS para la estación de cocina caliente.
104. Alerta visual de tiempo excedido en comandas (comanda cambia a rojo a los 12 minutos).
105. Registro de comentarios especiales de alérgenos en comanda (Ej. "Sin nuez").
106. Opción de marcar comanda como "En preparación" desde pantalla táctil.
107. Opción de marcar comanda como "Plato Listo" con notificación sonora.
108. Consola de despacho (KDS de entrega) para coordinar meseros y repartidores.
109. Agrupación inteligente de comandas por tipo de platillo (Ej: Juntar 3 baguettes en parrilla).
110. Alerta sonora de comanda prioritaria (para clientes VIP o pedidos demorados).
111. Registro de tiempos promedio de preparación por cocinero.
112. Control de recetas digitales en pantalla para consulta rápida de cocineros nuevos.
113. Pantalla de visualización de ingredientes alternos por desabasto.
114. Envío de comanda a preparación solo después de recibir confirmación de pago (para fast-food).
115. Registro de temperatura de los alimentos al salir de cocina para auditorías de higiene.
116. Botón de pánico en KDS para detener pedidos temporales por saturación de cocina.
117. Notificación automática a mesero (vía smartwatch/móvil) cuando el plato de su mesa esté listo.
118. Impresión física de "tira de cocina" de repuesto en caso de fallo de red en KDS.
119. Control de mermas de producción por porciones incorrectas en cocina.
120. Registro de temperatura del agua de la máquina espresso en barra cada turno.
121. Alerta automática al KDS si un producto del menú se marca como "Agotado".
122. Marcado de platillos con "Servicio inmediato" (bebidas de entrada).
123. Configuración de tiempos de desfase para que los postres se preparen después de los fuertes.
124. Control de gramaje de café espresso por shot en barra.
125. Control de desinfección de verduras con registro de bitácora en cocina.
126. Reporte de eficiencia de producción por horas de servicio.
127. Control de producción pre-elaborada (Ej: cuántas salsas preparar en la mañana).
128. Registro de merma por platos regresados a cocina por sabor desagradable.
129. Control de desecho de aceite de freidoras con bitácora ecológica.
130. Registro de recalibración de molino de café según humedad del día.
131. Marcado de comandas de reparto a domicilio con colores distintivos.
132. Bloqueo de comandas si la mesa física no está registrada en el sistema.
133. Visualización de foto del plato final en KDS para asegurar estándar de emplatado.
134. Registro de tiempos de entrega del KDS a la mesa por mesero.
135. Control de alérgenos cruzados en cocina mediante alertas en comandas.
136. Envío de comanda de cortesía directamente del chef a la mesa (sin pasar por caja).
137. Programación de tareas de limpieza profunda de cocina asignadas en KDS.
138. Registro de consumo de gas y energía en cocina diario.
139. Control de inventario de vajilla de repuesto dentro de cocina.
140. Indicador de "Pedido para llevar" en comanda de cocina para empaque especial.
141. Alerta de platillo "Poco cocido" o "Término medio" según orden.
142. Control de tiempo de vida útil de postres en vitrina antes de merma.
143. Registro de recalibración de hornos de repostería.
144. Visualización en barra del stock actual de leche fría.
145. Alerta de saturación de barra si se acumulan más de 8 cafés en KDS.
146. Reporte de tiempos de despacho comparando fines de semana vs entre semana.
147. Gestión de comandas canceladas con aviso inmediato en KDS para detener cocción.
148. Control de ingredientes premium con pesaje obligatorio en cocina.
149. Reporte de merma de cocina por mala manipulación de cuchillos.
150. Control y registro de afilado de cuchillos de cocina semanal.

---

## Módulo 4: Recursos Humanos, Asistencia, Turnos y Propinas (151-200)

151. Reloj checador digital por huella o PIN para inicio de jornada.
152. Registro de salida a descanso/comida de empleados para control de tiempos.
153. Alerta a gerencia por retardos acumulados de empleados en el mes.
154. Registro de faltas justificadas e injustificadas con bitácora médica.
155. Programación de turnos semanales de empleados en calendario visual.
156. Notificación automática al celular del empleado sobre su turno asignado.
157. Solicitud y aprobación de intercambio de turnos entre compañeros en el sistema.
158. Cálculo de horas extra automáticas acumuladas por día.
159. Control y registro de comisiones por venta de productos sugeridos.
160. Módulo de acumulación y distribución de bolsa de propinas (Tip Pool) automática.
161. Reparto de propinas según escala de puntos (Ej: Cocinero 1.5, Mesero 2.0, Lava loza 1.0).
162. Reporte de propinas electrónicas recibidas por mesero para dispersión.
163. Registro de incapacidades laborales avaladas por salud pública.
164. Control de vacaciones acumuladas y días de descanso disfrutados.
165. Expediente digital del empleado (CURP, RFC, Contrato, Alergias).
166. Registro de amonestaciones y actas administrativas de empleados.
167. Evaluación de desempeño de empleados mensual con métricas del POS.
168. Control de préstamos al personal con descuento automático en nómina.
169. Programación de capacitaciones de higiene (Distintivo H) para personal de cocina.
170. Registro de accidentes laborales internos con bitácora de primeros auxilios.
171. Control de entrega de uniformes, mandiles y herramientas de trabajo.
172. Reporte de rotación de personal y promedio de antigüedad en la empresa.
173. Gestión de comisiones por propina en eventos privados grandes.
174. Control de roles temporales (Ej: Mesero cubre como Cajero durante el almuerzo).
175. Alerta automática si un empleado excede las horas máximas de trabajo legales.
176. Registro de renuncias y finiquitos calculados automáticamente en base a ley.
177. Control de comidas de empleados autorizadas del menú diario.
178. Reporte de puntualidad y asistencia por departamento.
179. Programación de descansos obligatorios por turnos extenuantes.
180. Registro de llamadas de atención por mala presentación de uniforme.
181. Reporte de ventas promedio logradas por hora por mesero.
182. Cálculo de bonos por puntualidad y asistencia en el periodo de nómina.
183. Registro de evaluaciones psicométricas previas a la contratación.
184. Control de comisiones a baristas por venta de café de especialidad.
185. Alerta al administrador si un empleado intenta registrar entrada fuera de su horario.
186. Registro de capacitaciones de primeros auxilios y uso de extintores.
187. Control de asignación de propinas directas en mesa.
188. Reporte de quejas de clientes asociadas a meseros específicos.
189. Registro de exámenes de salud obligatorios para manipuladores de alimentos.
190. Control de asignación de lockers de vestidor del personal.
191. Gestión de vales de transporte nocturno para personal de cierre.
192. Reporte de productividad comparativa entre turnos matutino y vespertino.
193. Registro de felicitaciones de clientes a empleados en el POS.
194. Control de permisos de salida anticipada con firma digital de gerencia.
195. Cálculo de primas vacacionales y aguinaldos del personal.
196. Registro de suspensiones disciplinarias temporales.
197. Reporte de ausentismo general por mes.
198. Control de bonos por comentarios positivos en Google Maps mencionando al mesero.
199. Registro de capacitaciones de barismo avanzado.
200. Auditoría de ingresos y salidas del personal fuera del horario comercial.

---

## Módulo 5: Fidelización, CRM, Marketing y Promociones (201-250)

201. Creación de campañas de marketing con cupones de cumpleaños automatizados.
202. Monedero electrónico recargable para clientes frecuentes.
203. Envío de tarjetas de regalo virtuales a amigos con dedicatoria personalizada.
204. Programa de referidos con recompensa de puntos a ambos usuarios.
205. Clasificación de clientes por niveles (Bronce, Plata, Oro) según volumen de compra.
206. Cupones de descuento automáticos al acumular 5 compras en el mes.
207. Envío de correos masivos con promociones semanales a la base de clientes.
208. Descuento automático para estudiantes o adultos mayores presentando credencial.
209. Promociones automáticas tipo "2x1" en bebidas seleccionadas en horarios de menor venta.
210. Acumulación doble de puntos de lealtad los días de lluvia o eventos especiales.
211. Historial de compras detallado del cliente visible para baristas al ordenar.
212. Registro de preferencias de mesa del cliente en su perfil de CRM.
213. Alertas en el POS si el cliente tiene alérgenos registrados al ordenar.
214. Generación de cupones de disculpa con postre gratis por mal servicio previo.
215. Programación de precios especiales de "Hora Feliz" en repostería al final del día.
216. Módulo para crear paquetes combo (Ej: Baguette + Bebida = Precio especial).
217. Canje de puntos de lealtad por productos específicos del menú (Menú de Puntos).
218. Envío de notificaciones push de ofertas a clientes que tengan abierta la app.
219. Descuento del 100% en la décima taza de café mediante cupones digitales.
220. Registro de encuestas de satisfacción automatizadas post-compra por correo.
221. Control de campañas de publicidad digital y cálculo de ROI por ticket de venta.
222. Descuento automático en productos que tengan excedente de stock.
223. Promociones especiales cruzadas (Ej: compra una ensalada y llévate el agua a mitad de precio).
224. Cupones válidos únicamente los fines de semana.
225. Registro de clientes VIP con alertas de bienvenida en el sistema administrativo.
226. Canje de cupones mediante códigos de barras en pantalla de móvil.
227. Promoción automática de postre gratis si la reservación es de cumpleaños.
228. Control y registro de convenios de descuento corporativos con empresas locales.
229. Módulo de retos mensuales (Ej: "Prueba 4 cafés diferentes y gana 200 puntos").
230. Envío de correos de reactivación a clientes que no han comprado en 30 días.
231. Descuentos exclusivos para clientes que completen su perfil de CRM.
232. Envío de felicitaciones de cumpleaños con código QR de postre gratis.
233. Promociones exclusivas válidas sólo en canal de entrega a domicilio.
234. Registro de clientes que han cancelado reservaciones repetidamente (Lista Negra).
235. Programa de fidelización corporativo (tarjetas de afinidad multiusuario).
236. Notificación push al cliente si pasa cerca del local (geocercas).
237. Descuentos por volumen (Ej: compra 5 baguettes para oficina y obtén 15% menos).
238. Cupones de descuento exclusivos para seguidores de redes sociales de tiempo limitado.
239. Monitoreo de valor de vida útil del cliente (LTV) en CRM.
240. Programación de degustaciones exclusivas para clientes Oro.
241. Cupones válidos únicamente si se consume en el establecimiento.
242. Control de cortesías para influencers con registro de menciones.
243. Descuento automático en café si el cliente trae su propia taza ecológica.
244. Registro de opiniones de clientes en pantallas interactivas de la cafetería.
245. Reporte de tasas de retención de clientes por mes.
246. Cupones automatizados por abandono de carrito de compras en la web.
247. Alerta de cliente inactivo en el panel de CRM para contacto directo.
248. Sorteos automáticos mensuales basados en tickets de compra mayores a $200.
249. Descuentos automatizados para personal del establecimiento durante sus días libres.
250. Reporte de efectividad de cupones (cuántos emitidos vs cuántos canjeados).

---

## Módulo 6: Experiencia del Cliente, Mesa, Reservas y QR (251-300)

251. Selección de modificadores y extras al pedir café (Ej. Leche almendra, endulzante).
252. Visualización de alérgenos y valor nutricional por platillo en menú web.
253. Pedido y pago de la mesa mediante escaneo de código QR en la mesa física.
254. Visualización en tiempo real del estado de preparación del pedido en vivo en la web.
255. Portal de facturación en línea introduciendo el folio del ticket de compra.
256. Reservación de mesa con selección sobre mapa interactivo en 2D/3D del local.
257. Envío de ticket digital al correo electrónico del cliente para evitar imprimir papel.
258. Programación de pedidos para entrega o recogida a una hora futura específica.
259. Notificación de mesa disponible al móvil cuando el cliente está en lista de espera.
260. División de cuentas de comensales por consumo personal o partes iguales en la web.
261. Adición de propina en checkout web con selección de porcentaje rápido.
262. Menú web optimizado para lectores de pantalla de personas con discapacidad visual.
263. Petición de asistencia o botón de llamar al mesero desde el menú QR de la mesa.
264. Integración de pasarelas de pago reales como Stripe o PayPal en el checkout web.
265. Cancelación automática de reservaciones tras 15 minutos de retraso con aviso por SMS.
266. Registro de notas especiales para el repartidor (Ej: "Timbrar portón negro").
267. Autocompletado de dirección de entrega web mediante API de Google Maps.
268. Visualización de fotos ampliadas y videos de preparación de platillos en menú web.
269. Sección de preguntas frecuentes y soporte al cliente web con chat interactivo.
270. Alerta automática al móvil del cliente cuando su reservación requiera confirmación.
271. Solicitud de cambio de mesa en el establecimiento desde la interfaz web del comensal.
272. Visualización en menú de platos recomendados según el historial del comensal.
273. Módulo web para calificar y dejar comentarios a cada platillo tras su consumo.
274. Reservación de mesas preferenciales con cobro de comisión de apartado.
275. Registro de solicitudes de sillas para bebés o accesos para silla de ruedas.
276. Portal web para facturación del cliente con validación fiscal de RFC automática.
277. Visualización del historial calórico consumido en la cafetería en el portal web.
278. Canje de puntos acumulados directamente desde el menú web del cliente.
279. Notificación de retraso en entrega de comida a domicilio con cupón de disculpa.
280. Compartir el carrito de compra en grupo (Ej: la oficina junta el pedido en un link).
281. Visualización de firmas de baristas expertos y notas de cata de café en menú.
282. Menú web disponible en múltiples idiomas seleccionable por el comensal.
283. Programación de recordatorios de reservación 24 horas antes por WhatsApp.
284. Visualización de los modificadores elegidos detallados en el ticket digital.
285. Registro de comentarios anónimos del cliente sobre la limpieza de los baños.
286. Compra web de café en grano con selección de molienda a domicilio.
287. Visualización de disponibilidad de mesas en tiempo real en la web del cliente.
288. Solicitud de factura fiscal al momento de hacer el checkout en la web.
289. Registro de opiniones de clientes en pantallas interactivas de la cafetería.
290. Compartir fotos de los platillos en redes sociales directamente desde el menú.
291. Notificación al cliente de "Mesa Desinfectada y Lista" para pasar.
292. Visualización de sellos de certificación de higiene del local en pie de página.
293. Compra de boletos de eventos y catas de café en el portal web del cliente.
294. Reservación de la barra de baristas para eventos privados de café de especialidad.
295. Módulo de sugerencias de maridajes recomendados por platillo (Ej: Baguette + Espresso).
296. Selección de empaque del envío a domicilio ecológico.
297. Solicitud de cubiertos biodegradables adicionales al ordenar en la web.
298. Registro de quejas directas al gerente en portal de soporte del sitio.
299. Seguimiento por GPS del repartidor de comida a domicilio en el mapa de la web.
300. Mensaje de despedida y resumen de puntos ganados enviado al cerrar la comanda.

---

## 4. Conclusión de la Auditoría

La implementación de estos **300 casos de uso faltantes** elevaría la aplicación de Café Parisien a un ecosistema de gestión empresarial (ERP) y portal de experiencia del comensal (CRM) de clase mundial. La arquitectura unificada en Laravel y MySQL desarrollada proporciona la base modular perfecta para expandir estas funcionalidades de manera orgánica.
