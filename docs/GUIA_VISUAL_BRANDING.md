# 🎨 Guía Visual y Branding - RolPlay EDU

**Versión:** 1.0
**Fecha:** 26 de Enero de 2026
**Autor:** Migdonio Dediego Jaramillo

---

## 📋 Tabla de Contenidos

1. [Identidad Visual SENA](#identidad-visual-sena)
2. [Paleta de Colores](#paleta-de-colores)
3. [Aplicación de Colores en RolPlay EDU](#aplicación-de-colores-en-rolplay-edu)
4. [Tipografía](#tipografía)
5. [Logotipo](#logotipo)
6. [Iconografía](#iconografía)
7. [Componentes UI](#componentes-ui)
8. [Ejemplos de Uso](#ejemplos-de-uso)

---

## 1. Identidad Visual SENA

RolPlay EDU se desarrolla bajo los lineamientos de identidad visual del **SENA - Servicio Nacional de Aprendizaje**, por lo tanto debe respetar y aplicar correctamente la paleta de colores institucional actualizada (2025).

### Principios de Diseño

1. **Institucionalidad:** Mantener la identidad visual del SENA en todo momento
2. **Accesibilidad:** Garantizar contraste adecuado (WCAG 2.1 AA)
3. **Consistencia:** Usar los mismos colores en toda la plataforma
4. **Jerarquía:** Usar colores para establecer niveles de importancia

---

## 2. Paleta de Colores

### 2.1 Colores Institucionales SENA (Oficial 2025)

| Color | Código HEX | RGB | Uso Principal |
|-------|------------|-----|---------------|
| **Verde Institucional** | `#39A900` | rgb(57, 169, 0) | Color principal del logosímbolo SENA; usar en logo sobre fondo blanco/negro, piezas institucionales y documentos formales |
| **Verde Oscuro** | `#007832` | rgb(0, 120, 50) | Color secundario para fondos, bloques de color, acentos y elementos gráficos. **NUNCA** para el logosímbolo |
| **Azul Oscuro** | `#00304D` | rgb(0, 48, 77) | Jerarquías visuales, barras de navegación, contenedores y fondos en interfaces técnicas o sobrias |
| **Violeta** | `#71277A` | rgb(113, 39, 122) | Énfasis en titulares, recursos gráficos diferenciadores. Usar siempre acompañado del verde |
| **Amarillo** | `#FDC300` | rgb(253, 195, 0) | Resaltar titulares, llamados a la acción (CTA) y elementos ornamentales. **NUNCA** reemplaza al verde |

### 2.2 Colores Funcionales del Sistema

| Color | Código HEX | RGB | Aplicación |
|-------|------------|-----|------------|
| **Texto Principal** | `#1f2937` | rgb(31, 41, 55) | Textos de cuerpo, párrafos, contenido principal |
| **Texto Secundario** | `#6b7280` | rgb(107, 114, 128) | Descripciones, textos de apoyo, metadatos |
| **Texto Claro** | `#9ca3af` | rgb(156, 163, 175) | Placeholders, textos deshabilitados |
| **Fondo Claro** | `#f9fafb` | rgb(249, 250, 251) | Fondo principal de la aplicación |
| **Fondo Gris** | `#e5e7eb` | rgb(229, 231, 235) | Fondos alternos, separadores sutiles |
| **Fondo Oscuro** | `#1f2937` | rgb(31, 41, 55) | Modales, overlays, secciones oscuras |

### 2.3 Colores de Estado

| Estado | Color | HEX | Uso |
|--------|-------|-----|-----|
| **Éxito** | Verde Institucional | `#39A900` | Retroalimentación positiva, decisiones correctas |
| **Advertencia** | Amarillo | `#FDC300` | Alertas, avisos, información importante |
| **Error** | Rojo | `#dc2626` | Errores, validaciones fallidas, acciones destructivas |
| **Info** | Azul Oscuro | `#00304D` | Información neutral, datos complementarios |

---

## 3. Aplicación de Colores en RolPlay EDU

### 3.1 Jerarquía de Uso

#### Nivel 1: Elementos Principales (Verde Institucional #39A900)
- ✅ Botones de acción primaria ("Comenzar Simulación", "Iniciar Sesión")
- ✅ Enlaces principales
- ✅ Logosímbolo SENA
- ✅ Barra de progreso activa
- ✅ Iconos principales de competencias
- ✅ Retroalimentación positiva

#### Nivel 2: Elementos Secundarios (Verde Oscuro #007832)
- ✅ Botones secundarios ("Cancelar", "Volver")
- ✅ Fondos de secciones destacadas
- ✅ Bordes de elementos activos
- ✅ Hover states de botones primarios
- ✅ Gradientes (con Verde Institucional)

#### Nivel 3: Elementos de Apoyo (Azul Oscuro #00304D)
- ✅ Header / Navegación principal
- ✅ Footer
- ✅ Contenedores de dashboard
- ✅ Títulos de secciones técnicas
- ✅ Información neutral

#### Nivel 4: Elementos de Énfasis (Violeta #71277A)
- ✅ Titulares importantes ("¡Felicitaciones!")
- ✅ Insignias de logros
- ✅ Elementos decorativos especiales
- ⚠️ Siempre acompañado de verde institucional

#### Nivel 5: Llamados a la Acción (Amarillo #FDC300)
- ✅ Botones de "Demo", "Probar Ahora"
- ✅ Badges de "Nuevo", "Destacado"
- ✅ Elementos ornamentales que requieran atención
- ⚠️ Nunca sustituye al verde como color principal

### 3.2 Variables CSS Recomendadas

```css
:root {
  /* Colores Institucionales SENA */
  --sena-verde-institucional: #39A900;
  --sena-verde-oscuro: #007832;
  --sena-azul-oscuro: #00304D;
  --sena-violeta: #71277A;
  --sena-amarillo: #FDC300;

  /* Aplicación en Sistema */
  --color-primary: var(--sena-verde-institucional);
  --color-primary-hover: var(--sena-verde-oscuro);
  --color-secondary: var(--sena-verde-oscuro);
  --color-accent: var(--sena-violeta);
  --color-cta: var(--sena-amarillo);
  --color-nav: var(--sena-azul-oscuro);

  /* Texto */
  --text-primary: #1f2937;
  --text-secondary: #6b7280;
  --text-muted: #9ca3af;
  --text-white: #ffffff;

  /* Fondos */
  --bg-primary: #f9fafb;
  --bg-secondary: #e5e7eb;
  --bg-dark: #1f2937;
  --bg-white: #ffffff;

  /* Estados */
  --success: var(--sena-verde-institucional);
  --warning: var(--sena-amarillo);
  --error: #dc2626;
  --info: var(--sena-azul-oscuro);

  /* Sombras */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);

  /* Bordes */
  --border-radius: 8px;
  --border-radius-lg: 12px;
  --border-color: #e5e7eb;
}
```

### 3.3 Reglas de NO Uso

❌ **NUNCA:**
- Usar Verde Oscuro (#007832) en el logosímbolo SENA
- Reemplazar el Verde Institucional con Amarillo en elementos primarios
- Usar Violeta sin acompañamiento del verde institucional
- Modificar los códigos HEX de los colores institucionales
- Crear gradientes que no incluyan colores institucionales

✅ **SIEMPRE:**
- Usar Verde Institucional (#39A900) en el logo SENA
- Mantener suficiente contraste para accesibilidad
- Respetar la jerarquía de colores establecida
- Usar variables CSS en lugar de códigos hardcoded

---

## 4. Tipografía

### 4.1 Familia Tipográfica

**Primaria:** Roboto (Google Fonts)
- Diseñada para legibilidad en pantallas
- Soporte completo para español
- Disponible en múltiples pesos

```html
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
```

### 4.2 Jerarquía Tipográfica

| Elemento | Familia | Peso | Tamaño | Uso |
|----------|---------|------|--------|-----|
| **H1** | Roboto | 700 (Bold) | 2.5rem (40px) | Títulos principales de página |
| **H2** | Roboto | 700 (Bold) | 2rem (32px) | Títulos de sección |
| **H3** | Roboto | 700 (Bold) | 1.5rem (24px) | Subtítulos de sección |
| **H4** | Roboto | 500 (Medium) | 1.25rem (20px) | Subtítulos menores |
| **Body** | Roboto | 400 (Regular) | 1rem (16px) | Texto de cuerpo |
| **Small** | Roboto | 400 (Regular) | 0.875rem (14px) | Textos pequeños, metadatos |
| **Button** | Roboto | 500 (Medium) | 1rem (16px) | Texto de botones |

### 4.3 Estilos CSS

```css
body {
  font-family: 'Roboto', sans-serif;
  font-size: 16px;
  line-height: 1.5;
  color: var(--text-primary);
}

h1 {
  font-size: 2.5rem;
  font-weight: 700;
  line-height: 1.2;
  color: var(--text-primary);
}

h2 {
  font-size: 2rem;
  font-weight: 700;
  line-height: 1.3;
  color: var(--text-primary);
}

h3 {
  font-size: 1.5rem;
  font-weight: 700;
  line-height: 1.4;
  color: var(--text-primary);
}

button {
  font-weight: 500;
  font-size: 1rem;
}

.text-muted {
  color: var(--text-secondary);
}
```

---

## 5. Logotipo

### 5.1 Logos Disponibles

RolPlay EDU cuenta con varios archivos de logo en `/img`:

| Archivo | Uso Recomendado |
|---------|-----------------|
| **LogoRP2.png** | ⭐ Logo principal para web, presentaciones, documentos |
| **LogoRP3.png** | Logo compacto para favicon, iconos pequeños |
| **LogoRP.png** | Alta resolución para impresión, banners |
| **logo_rp1.webp** | Versión optimizada para web (carga rápida) |

### 5.2 Área de Protección

Mantener un espacio mínimo alrededor del logo equivalente al 25% de su altura.

```
┌────────────────────────────────┐
│          [espacio]             │
│   ┌──────────────────┐         │
│   │   [LOGO ROLPLAY] │         │
│   └──────────────────┘         │
│          [espacio]             │
└────────────────────────────────┘
```

### 5.3 Usos Correctos e Incorrectos

✅ **Correcto:**
- Logo sobre fondo blanco o gris claro
- Logo sobre imagen con suficiente contraste
- Escala proporcional (mantener aspect ratio)
- Tamaño mínimo: 120px de ancho

❌ **Incorrecto:**
- Deformar o estirar el logo
- Cambiar los colores del logo
- Usar sobre fondos que no tengan contraste
- Tamaño menor a 120px de ancho

---

## 6. Iconografía

### 6.1 Librería de Iconos

**Font Awesome 6.4.0** - Librería oficial del proyecto

```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

### 6.2 Iconos por Competencia

| Competencia | Icono | Código HTML | Color |
|-------------|-------|-------------|-------|
| **Comunicación** | 💬 | `<i class="fas fa-comment-dots"></i>` | Azul `#00304D` |
| **Liderazgo** | 👥 | `<i class="fas fa-users"></i>` | Verde `#39A900` |
| **Trabajo en Equipo** | 🤝 | `<i class="fas fa-user-friends"></i>` | Violeta `#71277A` |
| **Toma de Decisiones** | 🎯 | `<i class="fas fa-bullseye"></i>` | Amarillo `#FDC300` |

### 6.3 Iconos de Sistema

| Función | Icono | Código |
|---------|-------|--------|
| **Inicio** | 🏠 | `<i class="fas fa-home"></i>` |
| **Usuario** | 👤 | `<i class="fas fa-user"></i>` |
| **Configuración** | ⚙️ | `<i class="fas fa-cog"></i>` |
| **Salir** | 🚪 | `<i class="fas fa-sign-out-alt"></i>` |
| **Éxito** | ✅ | `<i class="fas fa-check-circle"></i>` |
| **Error** | ❌ | `<i class="fas fa-times-circle"></i>` |
| **Advertencia** | ⚠️ | `<i class="fas fa-exclamation-triangle"></i>` |
| **Info** | ℹ️ | `<i class="fas fa-info-circle"></i>` |

---

## 7. Componentes UI

### 7.1 Botones

#### Botón Primario
```html
<button class="btn-primary">
  Comenzar Simulación
</button>
```

```css
.btn-primary {
  background-color: var(--sena-verde-institucional);
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: var(--sena-verde-oscuro);
}
```

#### Botón Secundario
```css
.btn-secondary {
  background-color: transparent;
  color: var(--sena-verde-institucional);
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 500;
  border: 2px solid var(--sena-verde-institucional);
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-secondary:hover {
  background-color: var(--sena-verde-institucional);
  color: white;
}
```

#### Botón de Advertencia (CTA)
```css
.btn-cta {
  background-color: var(--sena-amarillo);
  color: var(--text-primary);
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.btn-cta:hover {
  transform: scale(1.05);
}
```

### 7.2 Tarjetas (Cards)

```css
.card {
  background-color: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.card-header {
  border-bottom: 2px solid var(--sena-verde-institucional);
  padding-bottom: 12px;
  margin-bottom: 16px;
}
```

### 7.3 Badges (Insignias)

```css
.badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.875rem;
  font-weight: 500;
}

.badge-success {
  background-color: #d1fae5;
  color: var(--sena-verde-institucional);
}

.badge-warning {
  background-color: #fef3c7;
  color: #92400e;
}

.badge-new {
  background-color: var(--sena-amarillo);
  color: var(--text-primary);
}
```

### 7.4 Barra de Progreso

```css
.progress-container {
  width: 100%;
  height: 8px;
  background-color: var(--bg-secondary);
  border-radius: 9999px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background-color: var(--sena-verde-institucional);
  transition: width 0.5s ease-in-out;
  border-radius: 9999px;
}
```

### 7.5 Alertas

```html
<!-- Éxito -->
<div class="alert alert-success">
  <i class="fas fa-check-circle"></i>
  ¡Excelente decisión!
</div>

<!-- Advertencia -->
<div class="alert alert-warning">
  <i class="fas fa-exclamation-triangle"></i>
  Esta decisión tiene consecuencias.
</div>

<!-- Error -->
<div class="alert alert-error">
  <i class="fas fa-times-circle"></i>
  Esa no fue la mejor opción.
</div>
```

```css
.alert {
  padding: 16px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.alert-success {
  background-color: #d1fae5;
  color: var(--sena-verde-institucional);
  border-left: 4px solid var(--sena-verde-institucional);
}

.alert-warning {
  background-color: #fef3c7;
  color: #92400e;
  border-left: 4px solid var(--sena-amarillo);
}

.alert-error {
  background-color: #fee2e2;
  color: var(--error);
  border-left: 4px solid var(--error);
}
```

---

## 8. Ejemplos de Uso

### 8.1 Header Principal

```html
<header class="header">
  <div class="container">
    <div class="header-content">
      <div class="logo-section">
        <img src="img/LogoSENA.png" alt="Logo SENA" height="50">
        <div class="divider"></div>
        <img src="img/LogoRP2.png" alt="RolPlay EDU" height="40">
      </div>
      <nav class="navigation">
        <a href="#proyecto">Sobre el Proyecto</a>
        <a href="#como-funciona">Cómo Funciona</a>
        <button class="btn-primary">Iniciar Sesión</button>
      </nav>
    </div>
  </div>
</header>
```

```css
.header {
  background-color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 16px 0;
}

.navigation a {
  color: var(--text-secondary);
  text-decoration: none;
  margin: 0 16px;
  transition: color 0.3s ease;
}

.navigation a:hover {
  color: var(--sena-verde-institucional);
}
```

### 8.2 Tarjeta de Escenario

```html
<div class="scenario-card">
  <div class="scenario-header">
    <h3>Cambio de Requisitos</h3>
    <span class="badge badge-new">Nuevo</span>
  </div>
  <p class="scenario-description">
    Un cliente solicita un cambio que afecta la planificación...
  </p>
  <div class="scenario-stats">
    <span><i class="fas fa-comment-dots"></i> Comunicación</span>
    <span><i class="fas fa-users"></i> Liderazgo</span>
  </div>
  <button class="btn-primary">
    <i class="fas fa-play"></i> Jugar
  </button>
</div>
```

### 8.3 Retroalimentación de Decisión

```html
<div class="feedback-modal feedback-good">
  <div class="feedback-header">
    <i class="fas fa-check-circle"></i>
    <h2>¡Excelente Decisión!</h2>
  </div>
  <p>
    Convocar una reunión para explicar la situación demuestra
    liderazgo y comunicación asertiva.
  </p>
  <div class="score-impact">
    <div class="score-item">
      <i class="fas fa-comment-dots"></i>
      <span>Comunicación</span>
      <strong class="positive">+15</strong>
    </div>
    <div class="score-item">
      <i class="fas fa-users"></i>
      <span>Liderazgo</span>
      <strong class="positive">+10</strong>
    </div>
  </div>
</div>
```

---

## 📊 Resumen de Aplicación

| Elemento | Color Principal | Color Hover/Activo |
|----------|----------------|-------------------|
| **Botón Primario** | Verde Institucional #39A900 | Verde Oscuro #007832 |
| **Botón Secundario** | Transparente con borde verde | Verde Institucional #39A900 |
| **Botón CTA** | Amarillo #FDC300 | Amarillo más oscuro |
| **Enlaces** | Azul Oscuro #00304D | Verde Institucional #39A900 |
| **Header/Nav** | Blanco sobre Azul Oscuro | Verde Institucional |
| **Footer** | Azul Oscuro #00304D | - |
| **Barra de Progreso** | Verde Institucional #39A900 | - |
| **Logros/Badges** | Violeta #71277A | - |
| **Alertas Éxito** | Verde Institucional #39A900 | - |
| **Alertas Advertencia** | Amarillo #FDC300 | - |

---

## ✅ Checklist de Implementación

Antes de implementar cualquier componente visual, verificar:

- [ ] ¿Los colores usados están en la paleta oficial SENA 2025?
- [ ] ¿El Verde Institucional (#39A900) es el color primario?
- [ ] ¿Se está usando Verde Oscuro SOLO en elementos gráficos, NO en logo?
- [ ] ¿El contraste de texto cumple WCAG 2.1 AA (4.5:1 mínimo)?
- [ ] ¿La tipografía es Roboto con los pesos correctos?
- [ ] ¿Los bordes tienen radio de 8px o 12px?
- [ ] ¿Las sombras son sutiles y consistentes?
- [ ] ¿Los iconos son de Font Awesome 6.4?
- [ ] ¿El espaciado es múltiplo de 4px (4, 8, 12, 16, 24...)?
- [ ] ¿Los estados hover tienen transición suave?

---

<div align="center">

**🎨 Guía Visual Completa para Desarrollo Consistente 🎨**

Versión 1.0 | Última actualización: 26 de Enero de 2026

[← Volver a Documentación](README.md)

</div>
