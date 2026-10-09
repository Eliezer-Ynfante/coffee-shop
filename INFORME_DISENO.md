# Informe de diseño – Raíz & Grano

## 1. Estado general del diseño actual

El proyecto presenta una base visual clara y consistente con una identidad premium de cafetería, orientada a un estilo cálido, elegante y oscuro. La intención de marca es muy evidente: uso de fondos oscuros, acentos ámbar y tipografía ornamental en títulos, con un enfoque de ambiente gourmet y contemporáneo.

Sin embargo, el diseño todavía no está maduro como sistema completo. Hay una base sólida de estilo, pero faltan estándares formales de diseño, accesibilidad, consistencia de componentes y un esquema de diseño documentado y validado en todas las vistas.

## 2. Lo que sí existe en la base visual

### Paleta de colores
La paleta está definida en la base CSS y refleja una intención clara de marca:

- Negro profundo: #0A0704
- Café oscuro: #1A120A
- Marrón terciopelo: #2E1F10
- Ámbar cálido: #C8783A
- Dorado brillante: #D4A017
- Crema: #F0E6D0
- Texto secundario: #7A6550

Esta combinación transmite:
- ambiente premium y nocturno,
- contraste entre lujo y comodidad,
- un enfoque café artesanal y acogedor.

### Tipografía actual
El sistema usa:
- Outfit para cuerpo, navegación, formularios y elementos UI generales.
- Cormorant Garamond para titulares y elementos de impacto visual.

Esto funciona bien para una identidad de cafetería premium, pero se necesita reforzar la jerarquía tipográfica, tamaños, espaciados y legibilidad en mobile, formularios y zonas de acción.

### Estructura visual actual
Se observa una estructura basada en:
- header / navbar fijo o semi-transparente,
- hero sections con fondo oscuro y degradado,
- bloques de contenido con separación clara,
- tarjetas de productos con hover y elevación visual,
- CTA con botones de color ámbar,
- layout de páginas públicas basado en sections y cards.

El patrón general es sólido, pero no está aún normalizado en todos los módulos.

### Fuentes de iconos
Se utiliza Font Awesome 6 como sistema principal de íconos:
- fa-solid
- fa-regular
- fa-brands

Esto aporta consistencia visual, pero conviene definir mejor si el sistema debe estar estandarizado con un único estilo en todas las vistas y componentes de admin.

### Modelo de diseño predominante
El proyecto se orienta a un modelo de diseño:
- premium hospitality,
- dark luxury coffee,
- editorial minimalista,
- e-commerce gastronómico con estética moderna.

Es un enfoque correcto para la marca, pero requiere más disciplina para evitar que la experiencia visual se vuelva inconsistente entre módulos.

## 3. Lo que todavía falta en el diseño

### 3.1. Skeletons y estados de carga
Falta una estrategia visual clara para:
- carga de productos,
- carga de reservas,
- resultados de búsqueda,
- panel administrativo,
- actualización de inventario,
- envío de formularios o confirmación asíncrona.

Se requieren skeletons, placeholders y controles de estado para evitar saltos visuales y mejorar la percepción de velocidad.

### 3.2. Ley de Hick
El sistema aún no aplica de forma rigurosa la Ley de Hick en pantallas con demasiadas opciones a la vez. Hay casos donde el usuario puede verse abrumado por:
- demasiadas categorías,
- demasiados botones de acción,
- varias opciones de selección en reserva o checkout,
- múltiples tareas dentro del panel administrativo.

Se requiere simplificar decisiones y priorizar opciones primarias, secundarias y de emergencia.

### 3.3. Design Tokens incompletos
El proyecto tiene una base de tokens visuales, pero no está documentado ni aplicado de forma completa. Faltan:
- tokens para spacing,
- radius,
- shadows,
- border widths,
- typography scale,
- states (hover, focus, disabled, selected, error, success),
- tamaños mínimos para hit targets y accesibilidad táctil.

Esto provoca inconsistencias entre módulos y dificulta el crecimiento del sistema.

### 3.4. Accesibilidad incompleta
No hay estándares completos de accesibilidad definidos ni validados. Faltan elementos clave como:
- contrastes adecuados en botones y fondos,
- foco visible para teclado,
- etiquetas explícitas en formularios,
- navegación por teclado consistente,
- aria-labels en controles complejos,
- estados de error claros y legibles,
- lectura correcta para lectores de pantalla.

### 3.5. Contraste y legibilidad
Se requiere revisar:
- textos sobre fondo oscuro,
- botones con texto poco legible,
- hover states con demasiado bajo contraste,
- placeholders con baja legibilidad,
- fondos degradados que reduzcan la claridad del contenido.

No basta con usar colores bonitos; deben ser legibles y accesibles.

### 3.6. Textos claros en botones y acciones
Hay acciones que pueden tener textos ambiguos o poco descriptivos. Los botones deben indicar claramente lo que sucederá, por ejemplo:
- Confirmar reserva
- Continuar al pago
- Enviar mensaje
- Guardar cambios
- Ajustar stock
- Reembolsar pedido

Se debe evitar texto vago que obligue al usuario a interpretar la acción.

### 3.7. Reseñas falsas o testimonios no verificados
Se deben eliminar o validar todo contenido testimonial no comprobado, especialmente si se usa para dar una apariencia de confianza artificial. Deben existir:
- reseñas verificadas,
- política de uso de testimonios,
- control de autenticidad,
- medios para evitar contenido falso o manipulado.

## 4. Problemas actuales de diseño detectados

- Desbordes visuales en algunos bloques y columnas.
- Inconsistencias entre páginas públicas y administración.
- Componentes con estilos no estandarizados.
- Formatos de botón poco homogéneos.
- Formularios visualmente diferentes entre secciones.
- Faltan estados de carga y error en flujos clave.
- Diferencias de jerarquía en textos y niveles de información.
- No hay diseño de componentes reusable en todas las vistas.
- Falta un sistema unificado para administración y operación.

## 5. Recomendaciones de diseño estructural

### Esquema recomendado
1. Definir una identidad visual maestra en tokens.
2. Crear un sistema de componentes reutilizables.
3. Separar layouts públicos, administración y checkout.
4. Establecer estados visuales para carga, éxito, error y vacío.
5. Revisar accesibilidad y contraste antes de publicar.
6. Trabajar con una guía visual unificada para todas las páginas.

### Ecosistema recomendado
- Diseño basado en tokens y variables CSS.
- Component library para botones, cards, inputs, alertas, tables, modals.
- Interacciones con foco visible y keyboard support.
- Estándares de contraste WCAG AA o superior.
- Skeletons para cada estado de carga.

## 6. Conclusión del diseño

El proyecto ya tiene una base visual atractiva y una dirección clara de marca: premium, cálida y moderna. Sin embargo, todavía no se ha consolidado como sistema de diseño robusto ni usable. El principal desafío no es solo cambiar colores o fuentes, sino estructurar un diseño más claro, más accesible, más consistente y más profesional para producción.

El siguiente paso debe ser convertir la estética actual en un diseño sólido, documentado y verificable, con tokens, accesibilidad, estados de carga, coherencia visual y validación real de cada flujo crítico.
