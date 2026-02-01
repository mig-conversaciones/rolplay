# Software Requirements Specification (SRS)
## RolPlay EDU - Plataforma de SimulaciÃ³n Gamificada para Competencias Transversales

**VersiÃ³n:** 1.0
**Fecha:** 26 de Enero de 2026
**Autor:** Migdonio Dediego Jaramillo
**InstituciÃ³n:** SENA - Centro Agropecuario de Buga

---

## Tabla de Contenidos

1. [IntroducciÃ³n](#1-introducciÃ³n)
2. [DescripciÃ³n General](#2-descripciÃ³n-general)
3. [Requisitos Funcionales](#3-requisitos-funcionales)
4. [Requisitos No Funcionales](#4-requisitos-no-funcionales)
5. [Arquitectura del Sistema](#5-arquitectura-del-sistema)
6. [Modelo de Datos](#6-modelo-de-datos)
7. [Interfaces de Usuario](#7-interfaces-de-usuario)
8. [IntegraciÃ³n con IA (Gemini)](#8-integracion-con-ia-gemini)
9. [Plan de Sprints](#9-plan-de-sprints)
10. [Criterios de AceptaciÃ³n](#10-criterios-de-aceptaciÃ³n)
11. [ApÃ©ndices](#11-apÃ©ndices)

---

## 1. IntroducciÃ³n

### 1.1 PropÃ³sito
Este documento especifica los requisitos para el desarrollo de **RolPlay EDU**, una plataforma gamificada que simula escenarios laborales para fortalecer competencias transversales en aprendices del SENA.

### 1.2 Alcance
El sistema se enfoca en una unica version online MVC (PHP + MySQL) con generacion de escenarios mediante Gemini.

### 1.3 Definiciones y AcrÃ³nimos

- **FPI**: FormaciÃ³n Profesional Integral
- **SENA**: Servicio Nacional de Aprendizaje
- **MVC**: Model-View-Controller
- **IA**: Inteligencia Artificial
- **UX/UI**: User Experience / User Interface
- **API**: Application Programming Interface
- **SRS**: Software Requirements Specification

### 1.4 Referencias

- Anteproyecto Ajustado 2025 - Migdonio Dediego
- Modelo PedagÃ³gico FPI del SENA (2012)
- DocumentaciÃ³n Gemini: https://ai.google.dev/gemini-api/docs

### 1.5 VisiÃ³n General
El documento se estructura en secciones que cubren requisitos funcionales, arquitectura, modelo de datos, y un plan de sprints iterativo para el desarrollo Ã¡gil.

---

## 2. DescripciÃ³n General

### 2.1 Perspectiva del Producto

RolPlay EDU es un sistema educativo gamificado que permite a los aprendices:
- Enfrentar escenarios laborales simulados
- Tomar decisiones crÃ­ticas en contextos seguros
- Recibir retroalimentaciÃ³n inmediata sobre sus competencias
- Desarrollar habilidades blandas medibles

### 2.2 Funcionalidades Principales

- Casos preestablecidos organizados por Ã¡rea (comercio, salud, tecnologÃ­a, etc.)
- Sistema de puntuaciÃ³n local (localStorage)
- NavegaciÃ³n sin conexiÃ³n
- ExportaciÃ³n de resultados

#### VersiÃ³n Online MVC
- GeneraciÃ³n automÃ¡tica de casos mediante IA
- AnÃ¡lisis de programas de formaciÃ³n SENA
- IdentificaciÃ³n de perfiles de egresado
- Rutas de entrenamiento personalizadas
- Dashboard de instructor
- Reportes analÃ­ticos avanzados
- GestiÃ³n de usuarios y roles

### 2.3 Usuarios del Sistema

| Rol | DescripciÃ³n |
|-----|-------------|
| **Aprendiz** | Usuario principal que realiza las simulaciones |
| **Instructor** | Carga programas de formaciÃ³n, monitorea progreso, genera reportes |
| **Administrador** | Gestiona usuarios, contenidos y configuraciÃ³n del sistema |
| **Sistema IA** | Genera casos automÃ¡ticamente basados en programas de formaciÃ³n |

### 2.4 Restricciones

- La versiÃ³n online requiere PHP 8.1+ y MySQL 8.0+
- IntegraciÃ³n con Gemini para servicios de IA
- Debe ser responsive (mÃ³vil, tablet, desktop)
- Cumplir con estÃ¡ndares de accesibilidad WCAG 2.1 nivel AA

### 2.5 Suposiciones y Dependencias

- Los programas de formaciÃ³n SENA estÃ¡n disponibles en formato documento estructurado
- Existe acceso a API de Gemini con cuota adecuada
- Los instructores tienen capacitaciÃ³n bÃ¡sica en TIC
- Disponibilidad de hosting con soporte PHP/MySQL

---

## 3. Requisitos Funcionales

### 3.1 MÃ³dulo de AutenticaciÃ³n y Usuarios

#### RF-001: Registro de Usuarios
**Prioridad:** Alta
**DescripciÃ³n:** El sistema debe permitir el registro de usuarios con diferentes roles.

**Criterios de AceptaciÃ³n:**
- Formulario de registro con campos: nombre, email, contraseÃ±a, rol
- ValidaciÃ³n de email Ãºnico
- EncriptaciÃ³n de contraseÃ±a (bcrypt)
- Email de confirmaciÃ³n (versiÃ³n online)

#### RF-002: Inicio de SesiÃ³n
**Prioridad:** Alta
**DescripciÃ³n:** Los usuarios deben poder autenticarse en el sistema.

**Criterios de AceptaciÃ³n:**
- Login con email/usuario y contraseÃ±a
- RecuperaciÃ³n de contraseÃ±a (online)
- Control de intentos fallidos
- RedirecciÃ³n segÃºn rol

#### RF-003: GestiÃ³n de Perfiles
**Prioridad:** Media
**DescripciÃ³n:** Los usuarios pueden gestionar su informaciÃ³n personal.

**Criterios de AceptaciÃ³n:**
- VisualizaciÃ³n de perfil
- EdiciÃ³n de datos personales
- Cambio de contraseÃ±a
- Historial de simulaciones completadas
- EstadÃ­sticas personales

### 3.2 MÃ³dulo de Simulaciones (Core)

#### RF-004: SelecciÃ³n de Escenario
**Prioridad:** Alta
**DescripciÃ³n:** El aprendiz puede seleccionar escenarios disponibles para practicar.

**Criterios de AceptaciÃ³n:**
- Grid de escenarios con tÃ­tulo, descripciÃ³n e imagen
- Indicador de completado/pendiente
- Filtros por Ã¡rea formativa
- Nivel de dificultad visible
- Escenarios bloqueados hasta completar prerrequisitos (opcional)

1. Cambio de Requisitos
2. Feedback Conflictivo
3. Proveedor Inconfiable
4. Error en ProducciÃ³n
5. Idea Rechazada
6. CompaÃ±ero con Dificultades
7. Prioridades del Proyecto
8. PresentaciÃ³n Inesperada

#### RF-005: EjecuciÃ³n de SimulaciÃ³n
**Prioridad:** Alta
**DescripciÃ³n:** El sistema presenta el escenario y gestiona las decisiones del aprendiz.

**Criterios de AceptaciÃ³n:**
- PresentaciÃ³n narrativa del contexto
- Opciones de decisiÃ³n claramente visibles
- Barra de progreso de la simulaciÃ³n
- Posibilidad de terminar anticipadamente
- RetroalimentaciÃ³n visual inmediata tras cada decisiÃ³n (color verde/rojo/amarillo)
- NavegaciÃ³n hacia siguiente paso segÃºn decisiÃ³n tomada

#### RF-006: Sistema de PuntuaciÃ³n
**Prioridad:** Alta
**DescripciÃ³n:** Cada decisiÃ³n impacta en las competencias del usuario.

**Competencias Medidas:**
- ComunicaciÃ³n
- Liderazgo
- Trabajo en Equipo
- Toma de Decisiones

**Criterios de AceptaciÃ³n:**
- PuntuaciÃ³n numÃ©rica por competencia (-15 a +15 por decisiÃ³n)
- AcumulaciÃ³n de puntos a lo largo de la simulaciÃ³n
- VisualizaciÃ³n en tiempo real de puntuaciÃ³n actual
- CÃ¡lculo de puntuaciÃ³n final al terminar

#### RF-007: RetroalimentaciÃ³n Final
**Prioridad:** Alta
**DescripciÃ³n:** Al finalizar, el usuario recibe anÃ¡lisis detallado de su desempeÃ±o.

**Criterios de AceptaciÃ³n:**
- Resumen de decisiones tomadas
- Impacto en cada competencia
- Mensaje motivacional personalizado
- Sugerencias de mejora
- OpciÃ³n de reintentar o continuar a otro escenario

#### RF-008: Guardado de Progreso
**Prioridad:** Alta
**DescripciÃ³n:** El sistema guarda el progreso del usuario.

**Criterios de AceptaciÃ³n:**
- **Online:** Base de datos MySQL
- Persistencia de: escenarios completados, puntuaciones, historial de decisiones
- RecuperaciÃ³n automÃ¡tica al volver a ingresar

### 3.3 MÃ³dulo de Instructor (Solo VersiÃ³n Online)

#### RF-009: Carga de Programas de FormaciÃ³n
**Prioridad:** Alta
**DescripciÃ³n:** El instructor puede cargar programas de formaciÃ³n del SENA en documento.

**Criterios de AceptaciÃ³n:**
- Upload de archivos documento (mÃ¡x 10MB)
- ValidaciÃ³n de formato
- Vista previa del documento
- Almacenamiento en servidor
- Listado de programas cargados

#### RF-010: AnÃ¡lisis AutomÃ¡tico con IA
**Prioridad:** Alta
**DescripciÃ³n:** El sistema utiliza Gemini para analizar el programa y extraer informaciÃ³n clave.

**Criterios de AceptaciÃ³n:**
- ExtracciÃ³n de texto del documento
- EnvÃ­o a API de Gemini con prompt estructurado
- IdentificaciÃ³n de:
  - Perfil del egresado
  - Competencias especÃ­ficas
  - Resultados de aprendizaje
  - Contextos laborales relevantes
- Almacenamiento de anÃ¡lisis en BD

#### RF-011: GeneraciÃ³n de Casos Personalizados
**Prioridad:** Alta
**DescripciÃ³n:** Basado en el anÃ¡lisis del programa, la IA genera escenarios especÃ­ficos.

**Criterios de AceptaciÃ³n:**
- Prompt a Gemini que incluye:
  - Perfil del egresado
  - Competencias a desarrollar
  - Contexto laboral especÃ­fico
- GeneraciÃ³n de escenario con estructura:
  - Narrativa inicial
  - Pasos de decisiÃ³n (mÃ­nimo 3, mÃ¡ximo 5)
  - Opciones por paso (3-4 opciones)
  - Impacto en competencias por opciÃ³n
  - RetroalimentaciÃ³n final
- ValidaciÃ³n de estructura JSON
- Almacenamiento en BD

#### RF-012: AsignaciÃ³n de Rutas de Entrenamiento
**Prioridad:** Media
**DescripciÃ³n:** El instructor puede asignar secuencias de escenarios a grupos de aprendices.

**Criterios de AceptaciÃ³n:**
- CreaciÃ³n de rutas con mÃºltiples escenarios
- AsignaciÃ³n a fichas/grupos especÃ­ficos
- DefiniciÃ³n de orden y prerrequisitos
- Fechas de inicio/fin (opcional)

#### RF-013: Dashboard de Instructor
**Prioridad:** Media
**DescripciÃ³n:** Panel de control para monitorear progreso de aprendices.

**Criterios de AceptaciÃ³n:**
- Vista general de fichas asignadas
- Progreso por aprendiz
- EstadÃ­sticas agregadas por competencia
- Aprendices con bajo desempeÃ±o (alertas)
- Filtros y bÃºsqueda

#### RF-014: Reportes y AnÃ¡lisis
**Prioridad:** Media
**DescripciÃ³n:** GeneraciÃ³n de reportes sobre desempeÃ±o.

**Criterios de AceptaciÃ³n:**
- Reporte individual por aprendiz
- Reporte grupal por ficha
- Comparativa de competencias
- ExportaciÃ³n a documento/Excel
- GrÃ¡ficos visuales (barras, radar)

### 3.4 MÃ³dulo de AdministraciÃ³n (Solo VersiÃ³n Online)

#### RF-015: GestiÃ³n de Usuarios
**Prioridad:** Media
**DescripciÃ³n:** El administrador puede gestionar todos los usuarios del sistema.

**Criterios de AceptaciÃ³n:**
- CRUD completo de usuarios
- Cambio de roles
- ActivaciÃ³n/desactivaciÃ³n de cuentas
- BÃºsqueda y filtros
- Reseteo de contraseÃ±as

#### RF-016: GestiÃ³n de Contenidos
**Prioridad:** Media
**DescripciÃ³n:** Control sobre escenarios y configuraciones.

**Criterios de AceptaciÃ³n:**
- CRUD de escenarios manuales
- EdiciÃ³n de escenarios generados por IA
- ActivaciÃ³n/desactivaciÃ³n de escenarios
- CategorizaciÃ³n por Ã¡rea formativa
- ConfiguraciÃ³n de niveles de dificultad

#### RF-017: ConfiguraciÃ³n del Sistema
**Prioridad:** Baja
**DescripciÃ³n:** ParÃ¡metros generales del sistema.

**Criterios de AceptaciÃ³n:**
- ConfiguraciÃ³n de API Gemini (key, endpoints)
- LÃ­mites de intentos por escenario
- Puntuaciones mÃ­nimas/mÃ¡ximas
- Textos institucionales (bienvenida, tÃ©rminos)
- Logos y branding

### 3.5 MÃ³dulo de GamificaciÃ³n

#### RF-018: Sistema de Logros
**Prioridad:** Baja
**DescripciÃ³n:** Los usuarios pueden desbloquear logros segÃºn su desempeÃ±o.

**Criterios de AceptaciÃ³n:**
- DefiniciÃ³n de logros (ej: "Completar 5 escenarios", "MÃ¡xima puntuaciÃ³n en Liderazgo")
- NotificaciÃ³n al desbloquear
- VisualizaciÃ³n en perfil
- Insignias visuales

#### RF-019: Ranking y Competencia
**Prioridad:** Baja
**DescripciÃ³n:** Tabla de lÃ­deres para motivar la competencia sana.

**Criterios de AceptaciÃ³n:**
- Ranking global por puntuaciÃ³n total
- Ranking por competencia especÃ­fica
- Ranking por ficha/grupo
- ActualizaciÃ³n en tiempo real

---

## 4. Requisitos No Funcionales

### 4.1 Rendimiento

**RNF-001:** El sistema debe cargar la pÃ¡gina principal en menos de 2 segundos con conexiÃ³n estÃ¡ndar.

**RNF-002:** La generaciÃ³n de casos con IA no debe tardar mÃ¡s de 15 segundos.

**RNF-003:** El sistema debe soportar hasta 100 usuarios concurrentes sin degradaciÃ³n.

### 4.2 Usabilidad

**RNF-004:** La interfaz debe ser intuitiva, con navegaciÃ³n clara y consistente.

**RNF-005:** El sistema debe ser responsive, adaptÃ¡ndose a mÃ³viles (320px+), tablets y desktop.

**RNF-006:** Los textos deben estar en espaÃ±ol, con lenguaje claro y profesional.

**RNF-007:** Cumplimiento con WCAG 2.1 nivel AA para accesibilidad.

### 4.3 Seguridad

**RNF-008:** Todas las contraseÃ±as deben almacenarse con hash bcrypt (cost 12).

**RNF-009:** Las sesiones deben expirar despuÃ©s de 24 horas de inactividad.

**RNF-010:** ProtecciÃ³n contra inyecciÃ³n SQL mediante prepared statements.

**RNF-011:** ValidaciÃ³n de entrada en cliente y servidor.

**RNF-012:** HTTPS obligatorio en producciÃ³n.

**RNF-013:** ProtecciÃ³n CSRF con tokens.

### 4.4 Fiabilidad

**RNF-014:** El sistema debe tener disponibilidad del 99% mensual (online).

**RNF-015:** Backup automÃ¡tico diario de base de datos.

**RNF-016:** Manejo de errores con mensajes amigables al usuario.

**RNF-017:** Logs de errores y acciones crÃ­ticas.

### 4.5 Mantenibilidad

**RNF-018:** CÃ³digo comentado y documentado (PHPDoc).

**RNF-019:** Arquitectura MVC con separaciÃ³n clara de responsabilidades.

**RNF-020:** Uso de Git para control de versiones.

**RNF-021:** Variables de configuraciÃ³n en archivo .env.

### 4.6 Portabilidad


**RNF-023:** La versiÃ³n online debe ser compatible con hosting shared estÃ¡ndar (cPanel).

**RNF-024:** Base de datos MySQL compatible con versiÃ³n 5.7+.

### 4.7 Escalabilidad

**RNF-025:** DiseÃ±o modular que permita agregar nuevas Ã¡reas formativas sin refactorizaciÃ³n.

**RNF-026:** API REST para posible integraciÃ³n con otras plataformas SENA.

---

## 5. Arquitectura del Sistema


```
â”‚
â”œâ”€â”€ index.html                 # Punto de entrada
â”œâ”€â”€ assets/
â”‚   â”œâ”€â”€ css/
â”‚   â”‚   â””â”€â”€ styles.css        # Estilos personalizados
â”‚   â”œâ”€â”€ js/
â”‚   â”‚   â”œâ”€â”€ app.js            # LÃ³gica principal
â”‚   â”‚   â”œâ”€â”€ scenarios.js      # DefiniciÃ³n de escenarios
â”‚   â”‚   â”œâ”€â”€ player.js         # GestiÃ³n del jugador
â”‚   â”‚   â””â”€â”€ ui.js             # GestiÃ³n de interfaz
â”‚   â””â”€â”€ images/
â”‚       â”œâ”€â”€ logo.png
â”‚       â””â”€â”€ scenarios/        # ImÃ¡genes de escenarios
â”œâ”€â”€ data/
â”‚   â””â”€â”€ scenarios.json        # Casos preestablecidos
â””â”€â”€ README.md
```

**TecnologÃ­as:**
- HTML5
- CSS3 (Tailwind CDN)
- JavaScript ES6+ (Vanilla)
- LocalStorage para persistencia

### 5.2 Arquitectura VersiÃ³n Online MVC

```
rolplay-edu-online/
â”‚
â”œâ”€â”€ public/                    # Carpeta pÃºblica (document root)
â”‚   â”œâ”€â”€ index.php             # Bootstrap
â”‚   â”œâ”€â”€ .htaccess             # Rewrite rules
â”‚   â”œâ”€â”€ assets/
â”‚   â”‚   â”œâ”€â”€ css/
â”‚   â”‚   â”œâ”€â”€ js/
â”‚   â”‚   â””â”€â”€ images/
â”‚   â””â”€â”€ uploads/              # PDFs subidos
â”‚
â”œâ”€â”€ app/
â”‚   â”œâ”€â”€ controllers/
â”‚   â”‚   â”œâ”€â”€ AuthController.php
â”‚   â”‚   â”œâ”€â”€ ScenarioController.php
â”‚   â”‚   â”œâ”€â”€ InstructorController.php
â”‚   â”‚   â”œâ”€â”€ PlayerController.php
â”‚   â”‚   â””â”€â”€ AdminController.php
â”‚   â”‚
â”‚   â”œâ”€â”€ models/
â”‚   â”‚   â”œâ”€â”€ User.php
â”‚   â”‚   â”œâ”€â”€ Scenario.php
â”‚   â”‚   â”œâ”€â”€ Program.php
â”‚   â”‚   â”œâ”€â”€ Session.php
â”‚   â”‚   â”œâ”€â”€ Decision.php
â”‚   â”‚   â””â”€â”€ Achievement.php
â”‚   â”‚
â”‚   â”œâ”€â”€ views/
â”‚   â”‚   â”œâ”€â”€ layouts/
â”‚   â”‚   â”‚   â”œâ”€â”€ header.php
â”‚   â”‚   â”‚   â”œâ”€â”€ footer.php
â”‚   â”‚   â”‚   â””â”€â”€ nav.php
â”‚   â”‚   â”œâ”€â”€ auth/
â”‚   â”‚   â”œâ”€â”€ scenarios/
â”‚   â”‚   â”œâ”€â”€ instructor/
â”‚   â”‚   â””â”€â”€ admin/
â”‚   â”‚
â”‚   â”œâ”€â”€ services/
â”‚   â”‚   â”œâ”€â”€ GeminiAIService.php    # IntegraciÃ³n Gemini
â”‚   â”‚   â”œâ”€â”€ TextParser.php          # ExtracciÃ³n documento
â”‚   â”‚   â”œâ”€â”€ ScenarioGenerator.php  # GeneraciÃ³n IA
â”‚   â”‚   â””â”€â”€ ReportService.php
â”‚   â”‚
â”‚   â””â”€â”€ core/
â”‚       â”œâ”€â”€ Database.php
â”‚       â”œâ”€â”€ Router.php
â”‚       â”œâ”€â”€ Controller.php
â”‚       â”œâ”€â”€ Model.php
â”‚       â””â”€â”€ Validator.php
â”‚
â”œâ”€â”€ config/
â”‚   â”œâ”€â”€ database.php
â”‚   â”œâ”€â”€ gemini.php
â”‚   â””â”€â”€ app.php
â”‚
â”œâ”€â”€ storage/
â”‚   â”œâ”€â”€ logs/
â”‚   â””â”€â”€ cache/
â”‚
â”œâ”€â”€ vendor/                    # Composer dependencies
â”‚
â”œâ”€â”€ .env                       # Variables de entorno
â”œâ”€â”€ .gitignore
â”œâ”€â”€ composer.json
â””â”€â”€ README.md
```

**TecnologÃ­as:**
- **Backend:** PHP 8.1+ (MVC custom)
- **Base de Datos:** MySQL 8.0+
- **Frontend:** HTML5, CSS3 (Tailwind), JavaScript (Alpine.js/Vue.js lite)
- **IA:** Gemini API
- **LibrerÃ­as:**
  - PHPMailer (emails)
  - FPDF/motor de reportes (reportes documento)
  - JWT (autenticaciÃ³n)
  - Smalot/PdfParser (lectura PDFs)

### 5.3 Diagrama de Componentes (VersiÃ³n Online)

```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                     CAPA DE PRESENTACIÃ“N                     â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”   â”‚
â”‚  â”‚ Aprendiz â”‚  â”‚Instructorâ”‚  â”‚  Admin   â”‚  â”‚ API REST â”‚   â”‚
â”‚  â”‚   UI     â”‚  â”‚    UI    â”‚  â”‚    UI    â”‚  â”‚  Client  â”‚   â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜   â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                         â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                    CAPA DE CONTROLADORES                     â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”   â”‚
â”‚  â”‚   Auth   â”‚  â”‚ Scenario â”‚  â”‚Instructorâ”‚  â”‚  Player  â”‚   â”‚
â”‚  â”‚Controllerâ”‚  â”‚Controllerâ”‚  â”‚Controllerâ”‚  â”‚Controllerâ”‚   â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜   â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                         â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                    CAPA DE SERVICIOS                         â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”   â”‚
â”‚  â”‚ GeminiAI  â”‚  â”‚   documento    â”‚  â”‚ Scenario â”‚  â”‚  Report  â”‚   â”‚
â”‚  â”‚ Service  â”‚  â”‚  Parser  â”‚  â”‚Generator â”‚  â”‚ Service  â”‚   â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜   â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                         â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                       CAPA DE MODELOS                        â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”   â”‚
â”‚  â”‚   User   â”‚  â”‚ Scenario â”‚  â”‚ Program  â”‚  â”‚ Session  â”‚   â”‚
â”‚  â”‚  Model   â”‚  â”‚  Model   â”‚  â”‚  Model   â”‚  â”‚  Model   â”‚   â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜   â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                         â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                   CAPA DE PERSISTENCIA                       â”‚
â”‚                   â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”                       â”‚
â”‚                   â”‚  MySQL Database  â”‚                       â”‚
â”‚                   â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜                       â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                         â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                    SERVICIOS EXTERNOS                        â”‚
â”‚                   â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”                       â”‚
â”‚                   â”‚  Gemini API    â”‚                       â”‚
â”‚                   â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜                       â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

---

## 6. Modelo de Datos

### 6.1 Diagrama Entidad-RelaciÃ³n

```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”         â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚     USERS       â”‚         â”‚    PROGRAMS     â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤         â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚ id (PK)         â”‚         â”‚ id (PK)         â”‚
â”‚ name            â”‚         â”‚ instructor_id   â”‚â”€â”€â”
â”‚ email (UNIQUE)  â”‚         â”‚ title           â”‚  â”‚
â”‚ password        â”‚         â”‚ competencias_text        â”‚  â”‚
â”‚ role            â”‚         â”‚ analysis_json   â”‚  â”‚
â”‚ created_at      â”‚         â”‚ created_at      â”‚  â”‚
â”‚ updated_at      â”‚         â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜                              â”‚
        â”‚                                         â”‚
        â”‚                                         â”‚
        â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
        â”‚
        â”‚
        â”‚         â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
        â””â”€â”€â”€â”€â”€â”€â”€â”€â”‚   SCENARIOS     â”‚
                 â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
                 â”‚ id (PK)         â”‚
                 â”‚ program_id (FK) â”‚â”€â”€â”
                 â”‚ title           â”‚  â”‚
                 â”‚ description     â”‚  â”‚
                 â”‚ area            â”‚  â”‚
                 â”‚ difficulty      â”‚  â”‚
                 â”‚ steps_json      â”‚  â”‚
                 â”‚ is_ai_generated â”‚  â”‚
                 â”‚ created_at      â”‚  â”‚
                 â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â”‚
                         â”‚            â”‚
                         â”‚            â”‚
        â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”       â”‚
        â”‚                     â”‚       â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â–¼â”€â”€â”€â”€â”€â”€â”      â”Œâ”€â”€â”€â”€â”€â”€â”€â–¼â”€â”€â”€â”€â”€â”€â”â”‚
â”‚   SESSIONS   â”‚      â”‚    ROUTES    â”‚â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤      â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤â”‚
â”‚ id (PK)      â”‚      â”‚ id (PK)      â”‚â”‚
â”‚ user_id (FK) â”‚â”€â”€â”   â”‚ name         â”‚â”‚
â”‚ scenario_id  â”‚  â”‚   â”‚ scenarios    â”‚â”‚ (JSON array)
â”‚ started_at   â”‚  â”‚   â”‚ instructor_idâ”‚â”‚â”€â”€â”˜
â”‚ completed_at â”‚  â”‚   â”‚ created_at   â”‚
â”‚ scores_json  â”‚  â”‚   â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
â”‚ final_score  â”‚  â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â”‚
        â”‚         â”‚
        â”‚         â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â–¼â”€â”€â”€â”€â”€â”€â”€â”€â”€â–¼â”€â”€â”
â”‚    DECISIONS      â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚ id (PK)           â”‚
â”‚ session_id (FK)   â”‚
â”‚ step_number       â”‚
â”‚ option_chosen     â”‚
â”‚ scores_impact     â”‚ (JSON)
â”‚ timestamp         â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚  ACHIEVEMENTS   â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚ id (PK)         â”‚
â”‚ name            â”‚
â”‚ description     â”‚
â”‚ criteria_json   â”‚
â”‚ badge_icon      â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
        â”‚
        â”‚
â”Œâ”€â”€â”€â”€â”€â”€â”€â–¼â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ USER_ACHIEVEMENTSâ”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚ id (PK)          â”‚
â”‚ user_id (FK)     â”‚
â”‚ achievement_id   â”‚
â”‚ unlocked_at      â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

### 6.2 DescripciÃ³n de Tablas

#### Tabla: users
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('aprendiz', 'instructor', 'admin') DEFAULT 'aprendiz',
    profile_image VARCHAR(255) NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla: programs
```sql
CREATE TABLE programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    competencias_text VARCHAR(255) NOT NULL,
    analysis_json TEXT NULL,  -- Resultado del anÃ¡lisis de IA
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_instructor (instructor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla: scenarios
```sql
CREATE TABLE scenarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NULL,  -- NULL si es escenario base
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    area VARCHAR(100) NOT NULL,  -- 'comercio', 'salud', 'tecnologia', etc.
    difficulty ENUM('basico', 'intermedio', 'avanzado') DEFAULT 'basico',
    steps_json TEXT NOT NULL,  -- Estructura completa del escenario
    is_ai_generated BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_area (area),
    INDEX idx_program (program_id),
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Estructura steps_json:**
```json
[
  {
    "id": 0,
    "text": "DescripciÃ³n de la situaciÃ³n",
    "options": [
      {
        "text": "OpciÃ³n A",
        "result": 1,
        "feedback": "good|neutral|bad",
        "scores": {
          "ComunicaciÃ³n": 15,
          "Liderazgo": 10
        }
      }
    ]
  }
]
```

#### Tabla: sessions
```sql
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    scenario_id INT NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    scores_json TEXT NOT NULL,  -- {"ComunicaciÃ³n": 25, "Liderazgo": 15, ...}
    final_score INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (scenario_id) REFERENCES scenarios(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_scenario (scenario_id),
    INDEX idx_completed (completed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla: decisions
```sql
CREATE TABLE decisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    step_number INT NOT NULL,
    option_chosen INT NOT NULL,
    scores_impact TEXT NOT NULL,  -- JSON con impacto de la decisiÃ³n
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    INDEX idx_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla: routes
```sql
CREATE TABLE routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    instructor_id INT NOT NULL,
    scenarios_json TEXT NOT NULL,  -- Array de scenario_ids en orden
    assigned_groups TEXT NULL,  -- JSON array de fichas/grupos
    start_date DATE NULL,
    end_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_instructor (instructor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla: achievements
```sql
CREATE TABLE achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    criteria_json TEXT NOT NULL,  -- Condiciones para desbloquear
    badge_icon VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla: user_achievements
```sql
CREATE TABLE user_achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_achievement (user_id, achievement_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 7. Interfaces de Usuario

### 7.1 Wireframes Principales

#### 7.1.1 Pantalla de Inicio (Landing)
```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚  [Logo SENA]     RolPlay EDU      [Iniciar SesiÃ³n]    â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                        â”‚
â”‚        â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”          â”‚
â”‚        â”‚                                  â”‚          â”‚
â”‚        â”‚   Desarrolla tus Habilidades     â”‚          â”‚
â”‚        â”‚   Blandas con Casos Reales       â”‚          â”‚
â”‚        â”‚                                  â”‚          â”‚
â”‚        â”‚   [Comenzar Ahora]               â”‚          â”‚
â”‚        â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜          â”‚
â”‚                                                        â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”          â”‚
â”‚  â”‚ Elige un â”‚  â”‚ InteractÃºaâ”‚  â”‚ Recibe   â”‚          â”‚
â”‚  â”‚   Reto   â”‚  â”‚ y Decide  â”‚  â”‚ Feedback â”‚          â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜          â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

#### 7.1.2 SelecciÃ³n de Escenarios
```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚  [Perfil]  Hola, [Nombre]       [EstadÃ­sticas] [Salir]â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                        â”‚
â”‚  Tus EstadÃ­sticas:                                     â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â”               â”‚
â”‚  â”‚  ðŸ’¬  â”‚ â”‚  ðŸ‘¥  â”‚ â”‚  ðŸŽ¯  â”‚ â”‚  ðŸ†  â”‚               â”‚
â”‚  â”‚  25  â”‚ â”‚  30  â”‚ â”‚  15  â”‚ â”‚  20  â”‚               â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”˜               â”‚
â”‚                                                        â”‚
â”‚  Selecciona un Escenario:       [Filtrar por Ã¡rea â–¼] â”‚
â”‚                                                        â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”â”‚
â”‚  â”‚  [Imagen]    â”‚  â”‚  [Imagen]    â”‚  â”‚  [Imagen]    â”‚â”‚
â”‚  â”‚              â”‚  â”‚              â”‚  â”‚              â”‚â”‚
â”‚  â”‚ Cambio de    â”‚  â”‚ Feedback     â”‚  â”‚ Proveedor    â”‚â”‚
â”‚  â”‚ Requisitos   â”‚  â”‚ Conflictivo  â”‚  â”‚ Inconfiable  â”‚â”‚
â”‚  â”‚              â”‚  â”‚              â”‚  â”‚              â”‚â”‚
â”‚  â”‚ [â–¶ Jugar]    â”‚  â”‚ [âœ“ Completo] â”‚  â”‚ [â–¶ Jugar]    â”‚â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

#### 7.1.3 Pantalla de SimulaciÃ³n
```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚  Cambio de Requisitos              [Terminar] [Menu] â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚  Progreso: â–ˆâ–ˆâ–ˆâ–ˆâ–ˆâ–ˆâ–ˆâ–ˆâ–‘â–‘â–‘â–‘â–‘â–‘ 60%                         â”‚
â”‚                                                        â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”â”‚
â”‚  â”‚                                                  â”‚â”‚
â”‚  â”‚  A mitad de un sprint, el cliente pide un       â”‚â”‚
â”‚  â”‚  cambio grande. El equipo se queja de que su    â”‚â”‚
â”‚  â”‚  trabajo anterior serÃ¡ desechado.               â”‚â”‚
â”‚  â”‚  Â¿CÃ³mo respondes como lÃ­der?                    â”‚â”‚
â”‚  â”‚                                                  â”‚â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜â”‚
â”‚                                                        â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”â”‚
â”‚  â”‚ A) Convocar reuniÃ³n para explicar y            â”‚â”‚
â”‚  â”‚    replanificar juntos                          â”‚â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜â”‚
â”‚                                                        â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”â”‚
â”‚  â”‚ B) Decir que 'el cliente siempre tiene razÃ³n'  â”‚â”‚
â”‚  â”‚    y asignar tareas inmediatamente              â”‚â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜â”‚
â”‚                                                        â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”â”‚
â”‚  â”‚ C) Rechazar la peticiÃ³n para proteger          â”‚â”‚
â”‚  â”‚    la moral del equipo                          â”‚â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜â”‚
â”‚                                                        â”‚
â”‚  PuntuaciÃ³n actual: ðŸ’¬ 15  ðŸ‘¥ 10  ðŸŽ¯ 5  ðŸ† 0        â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

#### 7.1.4 Dashboard Instructor
```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚  [Panel Instructor]         [Cargar Programa] [Salir] â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                        â”‚
â”‚  Mis Fichas:                                           â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”‚
â”‚  â”‚ Ficha 2557214 - AnÃ¡lisis y Desarrollo         â”‚  â”‚
â”‚  â”‚ 25 aprendices | Progreso promedio: 67%        â”‚  â”‚
â”‚  â”‚ [Ver Detalles] [Asignar Ruta]                 â”‚  â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â”‚
â”‚                                                        â”‚
â”‚  Programas Cargados:                                   â”‚
â”‚  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”‚
â”‚  â”‚ ðŸ“„ TecnÃ³logo en ADSO - V2                      â”‚  â”‚
â”‚  â”‚ Analizado âœ“ | 5 escenarios generados           â”‚  â”‚
â”‚  â”‚ [Ver AnÃ¡lisis] [Generar MÃ¡s Casos]            â”‚  â”‚
â”‚  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â”‚
â”‚                                                        â”‚
â”‚  Alertas:                                              â”‚
â”‚  âš ï¸ 3 aprendices con bajo rendimiento en Liderazgo   â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

### 7.2 GuÃ­a de Estilos

#### Paleta de Colores

**Colores Institucionales SENA (Oficial 2025)**

| Color | CÃ³digo HEX | Uso / AplicaciÃ³n |
|-------|------------|------------------|
| **Verde Institucional** | `#39A900` | Color principal del logosÃ­mbolo; usar en logo sobre fondo blanco/negro, piezas institucionales y documentos formales. **Color primario de la aplicaciÃ³n.** |
| **Verde Oscuro** | `#007832` | Color secundario para fondos, bloques de color, acentos y elementos grÃ¡ficos. Usar en botones secundarios, bordes y Ã©nfasis. |
| **Azul Oscuro** | `#00304D` | Para jerarquÃ­as visuales, barras de navegaciÃ³n, contenedores y fondos en interfaces tÃ©cnicas o sobrias. |
| **Violeta** | `#71277A` | Ã‰nfasis en titulares, recursos grÃ¡ficos diferenciadores. Usar con moderaciÃ³n, siempre acompaÃ±ado del verde. |
| **Amarillo** | `#FDC300` | Resaltar titulares, llamados a la acciÃ³n (CTA) y elementos ornamentales. Nunca reemplaza al verde principal. |

**Colores Funcionales (Sistema)**

| Color | CÃ³digo HEX | Uso |
|-------|------------|-----|
| **Texto Principal** | `#1f2937` | Textos de cuerpo, pÃ¡rrafos |
| **Texto Secundario** | `#6b7280` | Textos de apoyo, descripciones |
| **Fondo Claro** | `#f9fafb` | Fondo principal de la aplicaciÃ³n |
| **Fondo Gris** | `#e5e7eb` | Fondos de secciones alternas |
| **Ã‰xito** | `#39A900` | RetroalimentaciÃ³n positiva (usar verde institucional) |
| **Advertencia** | `#FDC300` | Alertas, avisos (usar amarillo institucional) |
| **Error** | `#dc2626` | Errores, validaciones fallidas |
| **Info** | `#00304D` | InformaciÃ³n neutral (usar azul oscuro) |

#### TipografÃ­a
- **Familia:** Roboto, sans-serif
- **TÃ­tulos:** 700 (Bold)
- **Cuerpo:** 400 (Regular)
- **Botones:** 500 (Medium)

#### Componentes
- **Botones primarios:** Verde SENA, texto blanco, bordes redondeados (8px)
- **Tarjetas:** Sombra suave, bordes redondeados (12px)
- **Iconos:** Font Awesome 6.4.0

---

## 8. IntegraciÃ³n con IA (Gemini)

### 8.1 ConfiguraciÃ³n de Gemini

**Archivo:** `config/gemini.php`
```php
<?php
return [
    'api_key' => getenv('GEMINI_API_KEY'),
    'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
    'model' => 'gemini-2.5-flash-preview-09-2025',
    'max_tokens' => 2000,
    'temperature' => 0.7,
];
```

### 8.2 Servicio GeminiAIService

**Archivo:** `app/services/GeminiAIService.php`

**MÃ©todos principales:**

#### 8.2.1 analyzeProgram($programText)
Analiza el texto del programa de formaciÃ³n y extrae informaciÃ³n estructurada.

**Prompt utilizado:**
```
Analiza el siguiente programa de formaciÃ³n del SENA y extrae:
1. Nombre del programa
2. Nivel de formaciÃ³n
3. Perfil del egresado (resumen en 2-3 lÃ­neas)
4. Competencias especÃ­ficas (lista)
5. Resultados de aprendizaje principales (mÃ¡ximo 5)
6. Contextos laborales donde se desempeÃ±arÃ¡ (lista de sectores/empresas)

Responde SOLO en formato JSON con esta estructura:
{
  "nombre": "",
  "nivel": "",
  "perfil_egresado": "",
  "competencias": [],
  "resultados_aprendizaje": [],
  "contextos_laborales": []
}

PROGRAMA:
[programText]
```

**Retorna:** JSON estructurado

#### 8.2.2 generateScenario($programAnalysis, $focus)
Genera un escenario completo basado en el anÃ¡lisis del programa.

**ParÃ¡metros:**
- `$programAnalysis`: Resultado del mÃ©todo analyzeProgram()
- `$focus`: Competencia a enfatizar (ej: "ComunicaciÃ³n", "Liderazgo")

**Prompt utilizado:**
```
Eres un diseÃ±ador instruccional experto en gamificaciÃ³n educativa. Crea un escenario laboral simulado para un aprendiz del SENA con este perfil:

PERFIL: [perfil_egresado]
CONTEXTOS LABORALES: [contextos_laborales]
COMPETENCIA A DESARROLLAR: [focus]

El escenario debe:
1. Ser realista y relevante al contexto laboral del aprendiz
2. Presentar una situaciÃ³n problemÃ¡tica que requiera toma de decisiones
3. Tener entre 3 y 5 pasos de decisiÃ³n
4. Cada paso debe tener 3-4 opciones de respuesta
5. Cada opciÃ³n debe impactar en las competencias: ComunicaciÃ³n, Liderazgo, Trabajo en Equipo, Toma de Decisiones
6. Incluir retroalimentaciÃ³n especÃ­fica al final

Responde SOLO en formato JSON con esta estructura:
{
  "title": "TÃ­tulo del escenario",
  "description": "DescripciÃ³n breve (1-2 lÃ­neas)",
  "area": "Ã¡rea formativa",
  "difficulty": "basico|intermedio|avanzado",
  "steps": [
    {
      "id": 0,
      "text": "DescripciÃ³n de la situaciÃ³n inicial",
      "options": [
        {
          "text": "OpciÃ³n A",
          "result": 1,
          "feedback": "good|neutral|bad",
          "scores": {
            "ComunicaciÃ³n": 15,
            "Liderazgo": 10,
            "Trabajo en Equipo": 5,
            "Toma de Decisiones": 0
          }
        }
      ]
    },
    {
      "id": 1,
      "text": "SituaciÃ³n siguiente...",
      "options": [...]
    },
    {
      "id": 3,
      "text": "Desenlace final",
      "feedbackText": "AnÃ¡lisis del desempeÃ±o",
      "options": []
    }
  ]
}
```

**Retorna:** JSON del escenario completo

#### 8.2.3 generateLearningPath($programAnalysis, $studentLevel)
Genera una ruta de aprendizaje secuencial.

**Retorna:** Array ordenado de temas/escenarios sugeridos

### 8.3 Flujo de Trabajo con IA

```
1. Instructor carga documento del programa
   â†“
2. TextParser extrae texto
   â†“
3. GeminiAIService::analyzeProgram()
   â†’ Llama a Gemini con prompt de anÃ¡lisis
   â†’ Recibe JSON estructurado
   â†’ Almacena en programs.analysis_json
   â†“
4. Instructor solicita generar escenarios
   â†“
5. GeminiAIService::generateScenario()
   â†’ Llama a Gemini con prompt de generaciÃ³n
   â†’ Recibe JSON del escenario
   â†’ Valida estructura
   â†’ Almacena en tabla scenarios
   â†“
6. Escenarios disponibles para aprendices
```

### 8.4 GestiÃ³n de Errores y LÃ­mites

- **Timeout:** 30 segundos por llamada
- **Reintentos:** Hasta 3 intentos con backoff exponencial
- **Cache:** AnÃ¡lisis de programas en cache 7 dÃ­as
- **LÃ­mite de cuota:** Alertar a admin si queda < 10% de cuota mensual
- **Fallback:** Si Gemini falla, mostrar escenarios preestablecidos

---

## 9. Plan de Sprints

### 9.1 MetodologÃ­a

- **DuraciÃ³n del Sprint:** 2 semanas
- **Total de Sprints:** 8 (16 semanas = 4 meses)
- **Reuniones:**
  - Sprint Planning (dÃ­a 1)
  - Daily Standup (diario, 15 min)
  - Sprint Review (Ãºltimo dÃ­a)
  - Sprint Retrospective (Ãºltimo dÃ­a)

### 9.2 Sprints Definidos

---

#### **Sprint 0: Setup y DiseÃ±o (Semanas 1-2)**

**Objetivos:**
- Configurar entorno de desarrollo
- DiseÃ±ar base de datos
- Crear wireframes detallados
- Establecer arquitectura

**Tareas:**
- [ ] Crear repositorio Git
- [ ] Configurar servidor local (XAMPP/MAMP)
- [ ] DiseÃ±ar esquema BD completo
- [ ] Wireframes en Figma/Adobe XD
- [ ] Definir estructura de archivos MVC
- [ ] Configurar Tailwind CSS
- [ ] Documento de arquitectura

**Entregables:**
- Repositorio configurado
- BD diseÃ±ada (sin implementar)
- Wireframes aprobados
- Documento de arquitectura

---


**Objetivos:**
- Implementar escenarios base
- Sistema de puntuaciÃ³n

**Tareas:**
- [ ] HTML estructura principal (index.html)
- [ ] CSS con Tailwind (landing, selecciÃ³n, simulaciÃ³n)
- [ ] JavaScript: navegaciÃ³n entre vistas
- [ ] Implementar 8 escenarios base en scenarios.json
- [ ] LÃ³gica de simulaciÃ³n (app.js)
- [ ] Sistema de puntuaciÃ³n por competencias
- [ ] LocalStorage para progreso
- [ ] RetroalimentaciÃ³n final
- [ ] Testing manual

**Entregables:**
- 8 escenarios jugables
- README con instrucciones

---

#### **Sprint 2: Infraestructura Online (Semanas 5-6)**

**Objetivos:**
- Configurar MVC backend
- Implementar BD MySQL
- Sistema de autenticaciÃ³n

**Tareas:**
- [ ] Crear estructura de carpetas MVC
- [ ] Implementar clase Database (PDO)
- [ ] Implementar Router
- [ ] Crear tablas en MySQL
- [ ] Seeders para datos iniciales
- [ ] Implementar modelo User
- [ ] AuthController (registro, login, logout)
- [ ] Vistas de auth (login.php, register.php)
- [ ] Sistema JWT para sesiones
- [ ] Middleware de autenticaciÃ³n
- [ ] Testing con Postman/Thunder Client

**Entregables:**
- Backend MVC funcional
- BD implementada
- Sistema de auth operativo

---

#### **Sprint 3: MÃ³dulo de Aprendiz (Semanas 7-8)**

**Objetivos:**
- Interfaz de aprendiz completa

**Tareas:**
- [ ] Modelo Scenario (CRUD)
- [ ] Migrar 8 escenarios base a BD
- [ ] ScenarioController::index() - lista escenarios
- [ ] ScenarioController::show($id) - vista simulaciÃ³n
- [ ] Vista: selecciÃ³n de escenarios
- [ ] Vista: pantalla de simulaciÃ³n
- [ ] AJAX para guardar decisiones
- [ ] Modelo Session y Decision
- [ ] PlayerController para gestionar sesiones
- [ ] Guardar progreso en BD
- [ ] Vista de perfil de aprendiz
- [ ] EstadÃ­sticas personales
- [ ] Testing funcional

**Entregables:**
- MÃ³dulo de aprendiz completo
- Escenarios jugables en versiÃ³n online
- Progreso persistente en BD

---

#### **Sprint 4: IntegraciÃ³n Gemini - AnÃ¡lisis (Semanas 9-10)**

**Objetivos:**
- Integrar API de Gemini
- Implementar anÃ¡lisis de programas

**Tareas:**
- [ ] Registrar cuenta en Gemini
- [ ] Configurar API key en .env
- [ ] Implementar GeminiAIService bÃ¡sico
- [ ] MÃ©todo analyzeProgram()
- [ ] Modelo Program (CRUD)
- [ ] InstructorController::uploadProgram()
- [ ] Vista: formulario upload documento
- [ ] Implementar TextParser (Smalot/PdfParser)
- [ ] ExtracciÃ³n de texto de documento
- [ ] Llamada a Gemini para anÃ¡lisis
- [ ] Validar y almacenar JSON de anÃ¡lisis
- [ ] Vista: mostrar anÃ¡lisis del programa
- [ ] Manejo de errores y timeouts
- [ ] Testing con programas reales SENA

**Entregables:**
- Servicio de anÃ¡lisis de programas funcional
- Instructores pueden cargar y analizar PDFs

---

#### **Sprint 5: IntegraciÃ³n Gemini - GeneraciÃ³n (Semanas 11-12)**

**Objetivos:**
- GeneraciÃ³n automÃ¡tica de escenarios con IA

**Tareas:**
- [ ] MÃ©todo generateScenario() en GeminiAIService
- [ ] DiseÃ±ar prompt Ã³ptimo para generaciÃ³n
- [ ] InstructorController::generateScenarios()
- [ ] Vista: interfaz para generar casos
- [ ] ParÃ¡metros: competencia a enfatizar, dificultad
- [ ] ValidaciÃ³n de estructura JSON recibida
- [ ] Almacenar escenarios generados en BD (is_ai_generated=TRUE)
- [ ] Preview de escenario antes de guardar
- [ ] EdiciÃ³n manual de escenarios generados
- [ ] Testing con diferentes programas
- [ ] Refinamiento de prompts
- [ ] Cache de anÃ¡lisis

**Entregables:**
- GeneraciÃ³n automÃ¡tica de escenarios funcional
- MÃ­nimo 3 escenarios generados y probados

---

#### **Sprint 6: Dashboard Instructor (Semanas 13-14)**

**Objetivos:**
- Panel de control completo para instructores
- Reportes bÃ¡sicos

**Tareas:**
- [ ] Vista: dashboard principal instructor
- [ ] Listado de fichas/grupos asignados
- [ ] Modelo Route (rutas de aprendizaje)
- [ ] InstructorController::createRoute()
- [ ] AsignaciÃ³n de escenarios a rutas
- [ ] Vista: asignar rutas a grupos
- [ ] Progreso de aprendices por ficha
- [ ] EstadÃ­sticas agregadas
- [ ] Servicio ReportService bÃ¡sico
- [ ] GeneraciÃ³n de documento con motor de reportes/FPDF
- [ ] Reporte individual por aprendiz
- [ ] Reporte grupal por ficha
- [ ] GrÃ¡ficos con Chart.js
- [ ] Testing con datos reales

**Entregables:**
- Dashboard instructor funcional
- Sistema de rutas implementado
- Reportes bÃ¡sicos en documento

---

#### **Sprint 7: GamificaciÃ³n y Pulido (Semanas 15-16)**

**Objetivos:**
- Sistema de logros
- Mejoras UX/UI
- Optimizaciones

**Tareas:**
- [ ] Modelo Achievement
- [ ] Definir 10 logros base
- [ ] LÃ³gica para desbloquear logros
- [ ] Notificaciones de logro desbloqueado
- [ ] Vista: galerÃ­a de logros en perfil
- [ ] DiseÃ±ar iconos de insignias
- [ ] Ranking de aprendices (opcional)
- [ ] Mejoras visuales en todas las interfaces
- [ ] Animaciones y transiciones
- [ ] OptimizaciÃ³n de consultas BD
- [ ] Implementar cache para escenarios
- [ ] CompresiÃ³n de assets
- [ ] Testing de rendimiento
- [ ] CorrecciÃ³n de bugs reportados

**Entregables:**
- Sistema de gamificaciÃ³n completo
- UI pulida y responsive
- Optimizaciones aplicadas

---

#### **Sprint 8: Testing, DocumentaciÃ³n y Despliegue (Semanas 17-18)**

**Objetivos:**
- Testing completo del sistema
- DocumentaciÃ³n final
- Despliegue en producciÃ³n

**Tareas:**
- [ ] Testing funcional exhaustivo
- [ ] Testing de seguridad (SQL injection, XSS, CSRF)
- [ ] Testing de usabilidad con usuarios reales
- [ ] CorrecciÃ³n de bugs crÃ­ticos
- [ ] Manual de usuario (documento)
- [ ] Manual tÃ©cnico (instalaciÃ³n, configuraciÃ³n)
- [ ] Video tutorial para instructores
- [ ] DocumentaciÃ³n de API (si aplica)
- [ ] Configurar servidor de producciÃ³n
- [ ] MigraciÃ³n de BD a producciÃ³n
- [ ] Configurar HTTPS
- [ ] Backup automÃ¡tico
- [ ] Monitoreo y logs
- [ ] CapacitaciÃ³n a instructores piloto
- [ ] Lanzamiento oficial

**Entregables:**
- **Sistema completo en producciÃ³n**
- DocumentaciÃ³n completa
- Manuales y tutoriales
- Plan de mantenimiento

---

### 9.3 PriorizaciÃ³n (MoSCoW)

#### Must Have (Imprescindible)
- AutenticaciÃ³n y gestiÃ³n de usuarios
- Sistema de puntuaciÃ³n por competencias
- IntegraciÃ³n Gemini (anÃ¡lisis y generaciÃ³n)
- Dashboard instructor bÃ¡sico
- Reportes individuales

#### Should Have (Importante)
- Rutas de aprendizaje
- Reportes grupales
- Sistema de logros
- EdiciÃ³n manual de escenarios generados

#### Could Have (Deseable)
- Ranking de aprendices
- Notificaciones push
- ExportaciÃ³n de datos a Excel
- API REST para integraciones

#### Won't Have (No en esta versiÃ³n)
- App mÃ³vil nativa
- GamificaciÃ³n avanzada (avatares, tienda virtual)
- IntegraciÃ³n con LMS SENA (Blackboard)

---

## 10. Criterios de AceptaciÃ³n

### 10.1 Criterios Generales

**AC-001:** El sistema debe ser accesible desde navegadores Chrome, Firefox, Edge y Safari en sus Ãºltimas versiones.


**AC-003:** La versiÃ³n online debe requerir autenticaciÃ³n para acceder a funcionalidades.

**AC-004:** El tiempo de respuesta de cualquier pÃ¡gina no debe exceder 3 segundos.

**AC-005:** El sistema debe ser responsive en dispositivos mÃ³viles (320px+), tablets (768px+) y desktop (1024px+).

### 10.2 Criterios por MÃ³dulo

#### AutenticaciÃ³n
- El registro debe validar formato de email
- La contraseÃ±a debe tener mÃ­nimo 8 caracteres
- El login debe mostrar error claro si credenciales incorrectas
- La sesiÃ³n debe persistir al cerrar/abrir navegador

#### Simulaciones
- Cada escenario debe tener mÃ­nimo 3 pasos de decisiÃ³n
- Las opciones deben tener retroalimentaciÃ³n visual inmediata
- La puntuaciÃ³n debe actualizarse en tiempo real
- El progreso debe guardarse automÃ¡ticamente

#### IntegraciÃ³n IA
- El anÃ¡lisis de un programa debe completarse en < 30 segundos
- La generaciÃ³n de un escenario debe completarse en < 20 segundos
- El JSON generado debe ser vÃ¡lido y completo
- Debe haber mensaje claro si la IA falla

#### Dashboard Instructor
- Debe mostrar todos los aprendices asignados
- Las estadÃ­sticas deben actualizarse al refrescar
- Los reportes documento deben generarse en < 5 segundos
- Los grÃ¡ficos deben ser legibles y precisos

### 10.3 Criterios de Seguridad

- Todas las contraseÃ±as en BD deben estar hasheadas
- No debe ser posible inyecciÃ³n SQL en ningÃºn formulario
- Los tokens JWT deben expirar en 24 horas
- Los archivos documento subidos deben validarse (tipo, tamaÃ±o)
- Solo instructores pueden acceder a mÃ³dulo de instructor

---

## 11. ApÃ©ndices

### 11.1 Glosario

- **Aprendiz:** Estudiante del SENA
- **Competencia transversal:** Habilidad blanda (soft skill)
- **Escenario:** SituaciÃ³n laboral simulada
- **Ficha:** Grupo de aprendices en un programa de formaciÃ³n
- **FPI:** FormaciÃ³n Profesional Integral (modelo pedagÃ³gico SENA)
- **Programa de formaciÃ³n:** Plan de estudios de un tecnÃ³logo/tÃ©cnico SENA
- **Ruta de aprendizaje:** Secuencia ordenada de escenarios

### 11.2 Escenarios Base (Resumen)

| # | TÃ­tulo | Ãrea | Competencias Principales |
|---|--------|------|-------------------------|
| 1 | Cambio de Requisitos | TecnologÃ­a | Liderazgo, ComunicaciÃ³n |
| 2 | Feedback Conflictivo | Transversal | ComunicaciÃ³n, Trabajo en Equipo |
| 3 | Proveedor Inconfiable | Comercio | Toma de Decisiones, ComunicaciÃ³n |
| 4 | Error en ProducciÃ³n | TecnologÃ­a | Liderazgo, ComunicaciÃ³n |
| 5 | Idea Rechazada | Transversal | ComunicaciÃ³n, Toma de Decisiones |
| 6 | CompaÃ±ero con Dificultades | Transversal | Trabajo en Equipo, ComunicaciÃ³n |
| 7 | Prioridades del Proyecto | Transversal | Toma de Decisiones, ComunicaciÃ³n |
| 8 | PresentaciÃ³n Inesperada | Transversal | Liderazgo, ComunicaciÃ³n |

### 11.3 Prompts para Gemini (Templates)

#### Prompt de AnÃ¡lisis
```
Eres un experto en anÃ¡lisis de programas educativos del SENA (Servicio Nacional de Aprendizaje de Colombia).

Analiza el siguiente programa de formaciÃ³n y extrae la informaciÃ³n clave en formato JSON.

INSTRUCCIONES:
1. Identifica el nombre completo del programa
2. Determina el nivel (TÃ©cnico, TecnÃ³logo, EspecializaciÃ³n)
3. Resume el perfil del egresado en mÃ¡ximo 3 lÃ­neas
4. Lista las competencias especÃ­ficas mencionadas
5. Identifica los 5 resultados de aprendizaje mÃ¡s importantes
6. Determina los contextos laborales (sectores/empresas)

FORMATO DE SALIDA (JSON):
{
  "nombre": "string",
  "nivel": "string",
  "perfil_egresado": "string",
  "competencias": ["string"],
  "resultados_aprendizaje": ["string"],
  "contextos_laborales": ["string"]
}

TEXTO DEL PROGRAMA:
{{programText}}

Responde ÃšNICAMENTE con el JSON, sin texto adicional.
```

#### Prompt de GeneraciÃ³n de Escenarios
```
Eres un diseÃ±ador instruccional experto en gamificaciÃ³n para educaciÃ³n tÃ©cnica.

CONTEXTO:
- InstituciÃ³n: SENA (Colombia)
- Perfil del aprendiz: {{perfil_egresado}}
- Contextos laborales: {{contextos_laborales}}
- Competencia a desarrollar principalmente: {{competencia_foco}}

TAREA:
Crea un escenario laboral simulado realista y relevante.

REQUISITOS:
1. El escenario debe presentar una situaciÃ³n problemÃ¡tica comÃºn en {{contextos_laborales}}
2. Debe tener entre 3 y 5 pasos de decisiÃ³n
3. Cada paso tiene 3 opciones de respuesta
4. Cada opciÃ³n impacta en: ComunicaciÃ³n, Liderazgo, Trabajo en Equipo, Toma de Decisiones
5. Los impactos van de -15 (muy negativo) a +15 (muy positivo)
6. El Ãºltimo paso no tiene opciones, solo retroalimentaciÃ³n final
7. Usa un tono profesional pero cercano

FORMATO DE SALIDA (JSON):
{
  "title": "string (mÃ¡x 60 caracteres)",
  "description": "string (mÃ¡x 150 caracteres)",
  "area": "string (comercio|salud|tecnologia|agropecuario|industrial)",
  "difficulty": "basico|intermedio|avanzado",
  "steps": [
    {
      "id": 0,
      "text": "string (descripciÃ³n de la situaciÃ³n)",
      "options": [
        {
          "text": "string (opciÃ³n de decisiÃ³n)",
          "result": number (id del siguiente paso),
          "feedback": "good|neutral|bad",
          "scores": {
            "ComunicaciÃ³n": number,
            "Liderazgo": number,
            "Trabajo en Equipo": number,
            "Toma de Decisiones": number
          }
        }
      ]
    }
  ]
}

El Ãºltimo step debe tener "options": [] y "feedbackText": "anÃ¡lisis del desempeÃ±o"

Responde ÃšNICAMENTE con el JSON, sin texto adicional ni markdown.
```

### 11.4 Stack TecnolÃ³gico Completo

#### Frontend
- **HTML5**
- **CSS3** con Tailwind CSS 3.x
- **Chart.js** para grÃ¡ficos
- **Font Awesome 6.4** para iconos

#### Backend
- **PHP 8.1+**
- **MySQL 8.0+**
- **Composer** para dependencias
- **JWT** (Firebase PHP-JWT) para autenticaciÃ³n
- **TextParser** (Smalot) para extracciÃ³n de texto
- **FPDF/motor de reportes** para generaciÃ³n de reportes

#### Servicios Externos
- **Gemini API** para IA generativa
- **SMTP** (PHPMailer) para emails

#### DevOps
- **Git** + GitHub para control de versiones
- **Apache/Nginx** servidor web
- **cPanel** para hosting compartido (despliegue inicial)

#### Herramientas de Desarrollo
- **VS Code** con extensiones PHP, Tailwind
- **Postman/Thunder Client** para testing API
- **Figma** para diseÃ±o UI
- **phpMyAdmin** para gestiÃ³n BD

### 11.5 Variables de Entorno (.env)

```env
# Database
DB_HOST=localhost
DB_NAME=rolplay_edu
DB_USER=root
DB_PASSWORD=

# Gemini API
GEMINI_API_KEY=your_api_key_here
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
GEMINI_MODEL=gemini-2.5-flash-preview-09-2025

# App
APP_NAME=RolPlay EDU
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/rolplay-edu

# Security
JWT_SECRET=your_secret_key_here
JWT_EXPIRATION=86400

# Email
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASSWORD=your_password
SMTP_FROM=noreply@rolplayedu.com
```

### 11.6 Estructura JSON de Escenario (Ejemplo Completo)

```json
{
  "title": "Conflicto en el Equipo de Desarrollo",
  "description": "Dos miembros clave del equipo tienen un desacuerdo tÃ©cnico que estÃ¡ afectando el proyecto.",
  "area": "tecnologia",
  "difficulty": "intermedio",
  "steps": [
    {
      "id": 0,
      "text": "Durante la daily standup, Carlos y Ana empiezan a discutir sobre la arquitectura del nuevo mÃ³dulo. Carlos quiere usar microservicios, Ana defiende un monolito. El tono sube y el resto del equipo estÃ¡ incÃ³modo. Como lÃ­der tÃ©cnico, Â¿quÃ© haces?",
      "options": [
        {
          "text": "Intervenir inmediatamente y pedir que lo discutan en privado despuÃ©s de la reuniÃ³n",
          "result": 1,
          "feedback": "good",
          "scores": {
            "ComunicaciÃ³n": 10,
            "Liderazgo": 15,
            "Trabajo en Equipo": 5,
            "Toma de Decisiones": 10
          }
        },
        {
          "text": "Dejar que terminen de discutir para que todo el equipo escuche ambas posturas",
          "result": 2,
          "feedback": "neutral",
          "scores": {
            "ComunicaciÃ³n": -5,
            "Liderazgo": -10,
            "Trabajo en Equipo": -5,
            "Toma de Decisiones": 0
          }
        },
        {
          "text": "Imponer tu decisiÃ³n tÃ©cnica sin escuchar mÃ¡s argumentos",
          "result": 2,
          "feedback": "bad",
          "scores": {
            "ComunicaciÃ³n": -15,
            "Liderazgo": -10,
            "Trabajo en Equipo": -10,
            "Toma de Decisiones": 5
          }
        }
      ]
    },
    {
      "id": 1,
      "text": "DespuÃ©s de la reuniÃ³n, te reÃºnes con Carlos y Ana en privado. Ambos siguen convencidos de que su enfoque es el correcto. Â¿CÃ³mo manejas la situaciÃ³n?",
      "options": [
        {
          "text": "Proponer que cada uno prepare una presentaciÃ³n tÃ©cnica con pros/contras y decidir en equipo",
          "result": 3,
          "feedback": "good",
          "scores": {
            "ComunicaciÃ³n": 15,
            "Liderazgo": 10,
            "Trabajo en Equipo": 15,
            "Toma de Decisiones": 10
          }
        },
        {
          "text": "Pedirles que lleguen a un compromiso entre ambos enfoques",
          "result": 3,
          "feedback": "neutral",
          "scores": {
            "ComunicaciÃ³n": 5,
            "Liderazgo": 5,
            "Trabajo en Equipo": 10,
            "Toma de Decisiones": 0
          }
        }
      ]
    },
    {
      "id": 2,
      "text": "Tu intervenciÃ³n empeorÃ³ las cosas. Carlos y Ana estÃ¡n resentidos y el equipo se ha dividido en bandos. La productividad ha caÃ­do.",
      "options": [
        {
          "text": "Organizar una retrospectiva para abordar el conflicto abiertamente",
          "result": 1,
          "feedback": "good",
          "scores": {
            "ComunicaciÃ³n": 10,
            "Liderazgo": 10,
            "Trabajo en Equipo": 5,
            "Toma de Decisiones": 5
          }
        },
        {
          "text": "Escalar el problema a tu superior",
          "result": 3,
          "feedback": "neutral",
          "scores": {
            "ComunicaciÃ³n": 5,
            "Liderazgo": -5,
            "Trabajo en Equipo": 0,
            "Toma de Decisiones": 0
          }
        }
      ]
    },
    {
      "id": 3,
      "text": "El conflicto se resolviÃ³ de manera constructiva. El equipo eligiÃ³ la mejor arquitectura basÃ¡ndose en criterios tÃ©cnicos objetivos.",
      "feedbackText": "Manejaste el conflicto con inteligencia emocional y liderazgo. Convertiste una situaciÃ³n tensa en una oportunidad de aprendizaje en equipo. Tu capacidad para facilitar el diÃ¡logo y tomar decisiones basadas en evidencia es clave para un lÃ­der tÃ©cnico.",
      "options": []
    }
  ]
}
```

---

## Fin del Documento SRS

**PrÃ³ximos Pasos:**
1. Revisar y aprobar este SRS
2. Configurar entorno de desarrollo (Sprint 0)

**Contacto:**
Migdonio Dediego Jaramillo
Instructor de Software - SENA Centro Agropecuario de Buga
Email: [tu_email]

---

**VersiÃ³n:** 1.0
**Ãšltima actualizaciÃ³n:** 26 de Enero de 2026





