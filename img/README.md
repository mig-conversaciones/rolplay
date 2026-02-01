# 🖼️ Recursos Multimedia - RolPlay EDU

Esta carpeta contiene todos los recursos visuales y multimedia del proyecto, listos para usar en la web de presentación.

---

## 📁 Contenido

### 🎨 Logos del Proyecto

| Archivo | Descripción | Uso Recomendado |
|---------|-------------|-----------------|
| **LogoRP2.png** | Logo principal con tipografía | Web, presentaciones, documentos |
| **LogoRP3.png** | Logo compacto | Favicon, iconos, redes sociales |
| **LogoRP.png** | Logo original alta resolución | Impresión, banners |
| **logo_RolPlay3.png** | Variante alternativa | Materiales secundarios |
| **logo_rp1.webp** | Logo optimizado web | Páginas web (carga rápida) |

**Recomendación:** Usar **LogoRP2.png** como logo principal del proyecto.

---

### 🖼️ Imágenes de Presentación

| Archivo | Contenido | Dimensiones |
|---------|-----------|-------------|
| **Imagen_1.png** | Ilustración del problema | 472 KB |
| **Imagen_2.png** | Ilustración de la solución | 3 MB (alta calidad) |

**Uso:** Diapositivas del pitch, landing page, documentación visual.

**Contexto:**
- **Imagen_1:** Aprendiz con dificultades para aplicar conocimientos en contexto real
- **Imagen_2:** Aprendices usando RolPlay EDU en entorno gamificado

---

### 📸 Capturas de Pantalla

| Archivo | Descripción |
|---------|-------------|
| **bit.ly_prototiporolplay.png** | QR + captura del prototipo funcional |
| **7ZxlxXJ1Tpei0Dvg9p2QZg.webp** | Mockup de la interfaz |

**Uso:** Demos, presentaciones, redes sociales.

---

### 🎵 Recursos de Audio

#### 🎙️ Audios Explicativos

| Archivo | Duración | Descripción |
|---------|----------|-------------|
| **RolPlay_HabilidadesClavedelFuturo.mp3** | ~2 min | Explicación principal del proyecto |
| **RolPlay EDU_ Cuando la IA y los Juegos Entrenan las Habilidades Clave del Futuro.mp3** | ~2 min | Versión alternativa con título completo |

**Uso:**
- Landing page (reproducción automática o bajo demanda)
- Presentaciones narradas
- Material de inducción para instructores

**Contenido:** Explicación de qué es RolPlay EDU, cómo funciona y su impacto esperado.

---

### 🎬 Video

| Archivo | Formato | Descripción |
|---------|---------|-------------|
| **Video_Script_RolPlay_EDU_Promo.mp4** | MP4 | Video promocional del proyecto |

**Uso:**
- Landing page (hero section)
- Redes sociales (YouTube, LinkedIn)
- Presentaciones institucionales

**Duración estimada:** ~1-2 minutos

---

## 🎨 Paleta de Colores del Proyecto

**Colores Institucionales SENA (Actualizado 2025)**

```css
/* Colores Institucionales SENA */
--color-verde-institucional: #39A900;  /* Principal - Logo y elementos primarios */
--color-verde-oscuro: #007832;         /* Secundario - Fondos, acentos */
--color-azul-oscuro: #00304D;          /* Apoyo - Barras, contenedores */
--color-violeta: #71277A;              /* Énfasis - Titulares, diferenciadores */
--color-amarillo: #FDC300;             /* Destacados - CTAs, ornamental */

/* Aplicación en Sistema */
--color-primary: #39A900;              /* Botones principales, enlaces */
--color-secondary: #007832;            /* Botones secundarios, bordes */
--color-accent: #71277A;               /* Elementos destacados */
--color-cta: #FDC300;                  /* Llamados a la acción */

/* Neutros y Funcionales */
--color-text-primary: #1f2937;         /* Texto principal */
--color-text-secondary: #6b7280;       /* Texto secundario */
--color-background: #f9fafb;           /* Fondo claro */
--color-background-alt: #e5e7eb;       /* Fondo alternativo */

/* Estados del Sistema */
--color-success: #39A900;              /* Verde institucional */
--color-warning: #FDC300;              /* Amarillo institucional */
--color-error: #dc2626;                /* Rojo para errores */
--color-info: #00304D;                 /* Azul oscuro */
```

**Notas de Uso:**
- **Verde Institucional (#39A900):** Usar SIEMPRE en el logosímbolo SENA
- **Verde Oscuro (#007832):** NUNCA usar en el logosímbolo, solo en elementos gráficos
- **Amarillo (#FDC300):** NUNCA reemplaza al verde principal
- **Violeta (#71277A):** Usar siempre acompañado del verde institucional

---

## 📐 Especificaciones Técnicas

### Logos
- **Formato:** PNG con transparencia
- **Resolución mínima:** 300 DPI para impresión
- **Tamaños recomendados web:**
  - Header: 200px altura
  - Favicon: 64x64px
  - Social media: 1200x630px

### Imágenes
- **Formatos:** PNG (alta calidad), WebP (optimizado web)
- **Optimización:** Comprimir antes de usar en web
- **Alt text:** Siempre incluir descripciones accesibles

### Audio
- **Formato:** MP3 (compatibilidad universal)
- **Bitrate:** 128-192 kbps
- **Uso:** Atributo `controls` en HTML5 `<audio>`

### Video
- **Formato:** MP4 (H.264)
- **Resolución recomendada:** 1080p
- **Compresión:** Optimizar para web (<5MB si es posible)

---

## 🌐 Uso en Web de Presentación

### Estructura HTML Recomendada

```html
<!-- Logo en header -->
<header>
  <img src="img/LogoRP2.png" alt="RolPlay EDU - Logo" height="50">
</header>

<!-- Hero section con audio -->
<section class="hero">
  <h1>RolPlay EDU</h1>
  <p>¿Prefieres escucharlo? Dale play y descubre de qué se trata.</p>
  <audio controls>
    <source src="img/RolPlay_HabilidadesClavedelFuturo.mp3" type="audio/mpeg">
    Tu navegador no soporta el elemento de audio.
  </audio>
</section>

<!-- Sección problema/solución -->
<section>
  <img src="img/Imagen_1.png" alt="El problema: Aprendices con dificultades en habilidades blandas">
  <img src="img/Imagen_2.png" alt="La solución: RolPlay EDU gamificado">
</section>

<!-- Video promocional -->
<section>
  <video controls width="100%">
    <source src="img/Video_Script_RolPlay_EDU_Promo.mp4" type="video/mp4">
    Tu navegador no soporta el elemento de video.
  </video>
</section>
```

---

## ✅ Checklist de Optimización

Antes de publicar la web, verificar:

- [ ] Comprimir imágenes PNG con TinyPNG o similar
- [ ] Generar versiones WebP de todas las imágenes
- [ ] Crear favicon.ico desde LogoRP3.png
- [ ] Agregar lazy loading a imágenes: `loading="lazy"`
- [ ] Incluir alt text descriptivo en todas las imágenes
- [ ] Optimizar video (resolución 720p si es muy pesado)
- [ ] Comprimir audio si supera 3MB
- [ ] Verificar que todos los recursos carguen correctamente

---

## 📦 Recursos Adicionales Necesarios

Para la web completa, considerar agregar:

- [ ] Screenshots del prototipo en acción (4-6 capturas)
- [ ] Foto del equipo / instructor
- [ ] Iconos para características (Font Awesome o custom)
- [ ] Imágenes de escenarios específicos
- [ ] Testimonios (foto + quote)
- [ ] Certificaciones o logos institucionales SENA

---

## 📞 Solicitudes de Recursos

Si necesitas nuevos recursos multimedia:

1. Definir especificaciones (formato, dimensiones, uso)
2. Crear brief visual
3. Guardar en esta carpeta con nombre descriptivo
4. Actualizar este README

---

## 📊 Inventario de Archivos

```
img/
├── README.md                          # Este archivo
│
├── Logos/
│   ├── LogoRP2.png                   # ⭐ Logo principal
│   ├── LogoRP3.png                   # Logo compacto
│   ├── LogoRP.png                    # Alta resolución
│   ├── logo_RolPlay3.png             # Variante
│   └── logo_rp1.webp                 # Optimizado web
│
├── Presentacion/
│   ├── Imagen_1.png                  # El problema
│   ├── Imagen_2.png                  # La solución
│   ├── bit.ly_prototiporolplay.png   # QR + captura
│   └── 7ZxlxXJ1Tpei0Dvg9p2QZg.webp  # Mockup interfaz
│
├── Audio/
│   ├── RolPlay_HabilidadesClavedelFuturo.mp3        # ⭐ Principal
│   └── RolPlay EDU_...mp3            # Alternativa
│
└── Video/
    └── Video_Script_RolPlay_EDU_Promo.mp4
```

**Total de archivos:** 12
**Peso total:** ~7.5 MB

---

<div align="center">

**🎨 Recursos listos para crear una experiencia visual impactante 🎨**

[← Volver al README principal](../README.md)

</div>
