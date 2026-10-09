# Pendientes de desarrollo

**Proyecto:** Raíz & Grano  
**Revisión:** 8 de octubre de 2026

## Decisiones del negocio

- Definir canales, horarios de atención, zonas y tarifas de entrega, pedido mínimo, políticas de cancelación y medios de pago por canal.
- Confirmar moneda, impuestos y comprobantes con asesoría contable; no asumir tasas ni reglas tributarias en el código.
- Definir si el stock representa productos terminados o insumos; si son insumos, diseñar ingredientes, recetas, unidades y proveedores.

## Ventas y caja

- Persistir turnos reales de caja con apertura y cierre asociados al usuario responsable; calcular el arqueo usando únicamente cobros válidos, devoluciones y movimientos de efectivo.
- Proteger la creación y el cobro frente a solicitudes simultáneas: bloquear la orden, evitar pagos duplicados, asegurar idempotencia y evitar sobreventa en web y POS mediante validación de stock y bloqueo transaccional.
- Guardar en cada ítem una instantánea histórica del nombre, SKU y precio; identificar los productos por ID y evitar crear productos implícitamente desde nombres enviados por el cliente.
- Completar la actualización en vivo de la cola de preparación y las notificaciones al cliente sobre pedidos y reservas.
- Implementar personalizaciones por producto cuando formen parte de la oferta y conservarlas en los ítems del pedido para que el cobro, la preparación y el reembolso sigan siendo consistentes.
- Añadir auditoría y conciliación por día y turno para distinguir ventas pagadas, pendientes, canceladas y reembolsadas sin mezclar cierres operativos con transacciones no validadas.

## Pagos y comprobantes

- Integrar una pasarela real cuando corresponda, con confirmación verificable, webhooks firmados, idempotencia, reintentos, expiración y conciliación; documentar qué pagos seguirán siendo confirmación manual.
- No recibir números completos de tarjeta ni CVC en la aplicación; delegar el cobro a un proveedor tokenizado y almacenar comprobantes de pago en almacenamiento privado.
- Definir e implementar emisión de tickets o comprobantes tributarios con el proveedor y las reglas contables aplicables.

## Cuentas y permisos

- Añadir registro o invitación controlada, recuperación/cambio de contraseña, verificación de correo según riesgo, revocación de sesiones y MFA para personal privilegiado.
- Separar permisos de propietario, gerente, cajero y preparación; aplicar autorización por recurso y propiedad, y evitar que se desactive o degrade al último administrador.
- Restringir la consulta y el pago de pedidos a su cliente o a un enlace/token seguro; actualmente el número de pedido permite acceder a la confirmación sin autenticación.
- Completar edición del perfil, consentimiento y reglas de retención/borrado de datos personales.

## Reportes y confiabilidad

- Crear reportes por periodo y zona horaria de ventas pagadas netas, reembolsos, impuestos, descuentos, canal, producto y método de pago; corregir indicadores para que no cuenten órdenes completadas sin verificar su pago.
- Añadir métricas operativas y técnicas, exportaciones por rol, auditoría de cambios sensibles, logs estructurados sin datos sensibles y alertas accionables.
- Configurar correo transaccional y colas con reintentos para pedidos, cambios de estado, reservas y recuperación de cuenta.
- Preparar producción con HTTPS, secretos fuera del repositorio, depuración desactivada, dependencias revisadas, monitoreo y copias de seguridad cifradas cuya restauración se pruebe.
- Completar pruebas de aceptación, concurrencia, idempotencia, autorización y privacidad; ensayar despliegue, contingencia y recuperación antes de habilitar ventas reales.

## Datos y distribución

- Decidir la estrategia de migraciones según los ambientes existentes; normalizar el nombre de la migración de clientes que contiene un espacio sin romper bases ya migradas.
- Revisar y restringir la ejecución de `CafeDataExportSeeder` fuera de desarrollo; conservar datos de demostración explícitos, idempotentes y limitados a local/testing.
- Confirmar titularidad del código y preparar licencia/aviso, contrato de uso comercial y atribución antes de distribuir el sistema a terceros.

## Observación global de calidad y estabilidad

- La arquitectura del proyecto presenta varios errores de organización funcional. Hay controladores que mezclan administración, operaciones, pagos, reservas, inventario y presentación sin un criterio claro de responsabilidad, lo que complica la trazabilidad, el mantenimiento y la validación de cada flujo.
- Existen múltiples problemas de UI/UX en las vistas: desbordes de contenido, inconsistencias de estructura, estilo no uniforme, componentes que dependen de reglas de layout no estables y diseños que no responden de forma consistente a distintos tamaños de pantalla.
- Hay piezas de código que no aportan valor real y además se han incorporado de manera desordenada, creando lógica redundante, rutas duplicadas o procesos que no se usan correctamente. Esto se refleja en funciones de administración y operación que no están alineadas con una política real de negocio.
- El sistema no está preparado para operar con la solidez necesaria. La falta de migraciones completas, validaciones de flujo, control de dependencias y sincronización de datos hace que varias operaciones se ejecuten de forma parcial o incorrecta.
- El módulo de reservas no es funcional como flujo completo. Se detecta un problema de integridad entre la disponibilidad por fecha/turno, la lógica de mesa y zona, y el criterio de administración del estado. La reserva en sí puede generarse, pero no garantiza un manejo operativo fiable ni una administración consistente del estado en panel.
- El cambio de estado de reservas desde administración no es confiable: se observa un flujo de transición limitado, pero no una gestión cerrada de negocio, ni un seguimiento real del historial, ni un control de validación completo del responsable y del motivo del cambio.
- Los pagos de los tres métodos no están completamente funcionales como flujo garantizado. Hay validaciones parciales, referencias manuales, ausencia de una conciliación real y falta de una capa de confirmación verificable, tokenización y control idempotente. El sistema no puede sostener un procesamiento financiero serio sin estas correcciones.
- La información incorporada al proyecto no es real ni consistente con una operación comercial completa. Hay contenido, reglas y datos que no responden a la realidad del negocio y que pueden generar decisiones erróneas en reservas, inventario, pagos, reportes y atención al cliente.
- El estado real del proyecto no supera una estimación conservadora del 38% de funcionamiento correcto y validado. El resto del sistema requiere refactorización, maduración de reglas de negocio y validación funcional real en rutas de administración, operación y seguimiento.
- Faltan métodos correctos de administración, operación y seguimiento en los controladores. Es necesario definir claramente quién puede ejecutar cada acción, qué validaciones deben ejecutarse antes de cada cambio, cómo se filtra la información, y cómo se registra el seguimiento de cada operación para que los procesos no dependan de variables de sesión, datos incompletos o ejecuciones no coordinadas.

## Evaluación por controladores y módulos

### ReservaController

- La reserva por mesa y por zona existe, pero se detecta una mezcla de conceptos de disponibilidad sin una regla de negocio única y clara. La comprobación de conflicto usa horarios y estados parciales, pero la gestión del flujo completo no se valida con una capa de control y auditoría clara.
- La ausencia de un control centralizado de reservas activas, historial de cambios y trazabilidad de administrador hace que el módulo sea frágil.
- El sistema no incorpora un seguimiento operativo completo para confirmar, completar, no asistir y cancelar con registros consistentes.

### AdminOperationsController

- El controlador de operaciones centraliza reservas, órdenes, inventario, clientes, caja y mesas. Esto genera acoplamiento excesivo y hace difícil distinguir la responsabilidad de cada flujo.
- El cambio de estado de reservas y órdenes existe, pero no está reforzado por validaciones de negocio, permisos por recurso, historial, ni flujo de transición seguro.
- La caja POS solo registra un cierre simplificado de sesión y no alcanza el nivel de arqueo, conciliación y control de efectivo requerido para operación real.
- El inventario y los movimientos no están conectados a una política de administración robusta ni a una auditoría completa.

### OrderCheckoutController y PaymentController

- El flujo de compra y pago se ha implementado parcialmente, pero no soporta un modelo financiero real ni una confirmación segura del pedido.
- El tratamiento de pagos por efectivo, tarjeta y billetera no es verificado de forma real ni robusta; falta idempotencia, almacenamiento seguro de comprobantes, conciliación y validaciones de autorización.
- El sistema no debería operar con información financiera sensible dentro de la capa de aplicación sin un diseño adecuado de tokenización y almacenamiento privado.

### Rutas y administración

- Las rutas del administrador permiten acciones importantes sin una separación clara de responsabilidades, sin control real de propiedad y sin procesos de revisión.
- La administración del sistema requiere módulos específicos para roles, permisos, auditoría y seguimiento de cada operación, no solo una lista de pantallas y botones operativos.

## Conclusión

El proyecto ha avanzado en varias piezas funcionales, pero aún no alcanza una etapa de operación confiable. Se requieren correcciones estructurales profundas, reorganización de controladores, limpieza de código no útil, definición de flujos reales de negocio, validación de migraciones y un diseño más serio de reservas, pagos y administración. La base técnica existe, pero la calidad y la madurez operativa aún deben consolidarse antes de considerarlo un sistema funcional para uso real o comercial.