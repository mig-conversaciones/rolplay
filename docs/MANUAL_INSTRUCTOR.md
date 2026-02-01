# Manual de Instructor - RolPlay EDU

Version: 2026-01-30

## 1. Objetivo
Describe las tareas del rol **Instructor**: crear programas, analizar PDF con IA, generar escenarios, gestionar rutas y consultar reportes.

## 2. Acceso
Inicia sesion con rol **instructor** y entra al dashboard en **/instructor**.

## 3. Programas (IA)
Rutas:
- **/instructor/programs** (listado)
- **/instructor/programs/create** (crear)

### 3.1 Crear programa desde PDF
1. Subir PDF oficial del programa.
2. Presionar **Analizar PDF con IA** (Puter.js).
3. El sistema llena automaticamente:
   - Titulo del programa
   - Codigo
   - Competencias
   - Perfil de egreso
4. Guardar programa.

Notas:
- Si no hay sesion en Puter, se solicitara login.
- Existe un boton opcional **Iniciar sesion con Puter**.

### 3.2 Ver analisis del programa
En el detalle del programa se muestran:
- Sector sugerido.
- Habilidades blandas recomendadas.

### 3.3 Generar escenarios con IA
En **Acciones IA**:
- Selecciona la **competencia foco** (lista de habilidades blandas).
- Presiona **Generar escenario IA**.
- El escenario se guarda automaticamente y queda disponible en la lista.

## 4. Rutas de aprendizaje
Rutas:
- **/instructor/routes** (listado)
- **/instructor/routes/create** (crear)

Funciones:
- Crear rutas y seleccionar escenarios.
- Asignar grupos/fichas.
- Definir fechas de inicio y fin.

## 5. Reportes
Rutas:
- **/instructor/reports/individual/{id}**
- **/instructor/reports/group**

Usa estos reportes para seguimiento individual o grupal.

## 6. Recomendaciones
- Mantener escenarios alineados al perfil y competencias del programa.
- Verificar que el analisis IA esta completo antes de generar escenarios.
