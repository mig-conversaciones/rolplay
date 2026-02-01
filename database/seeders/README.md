# Scripts de Gestión de Base de Datos - RolPlay EDU

Este directorio contiene scripts SQL para gestionar la base de datos en entornos de desarrollo y pruebas.

## 📁 Archivos Disponibles

| Archivo | Descripción | Uso |
|---------|-------------|-----|
| `clean_database.sql` | Limpia completamente la BD | Desarrollo/Testing |
| `seed_test_data.sql` | Carga datos de prueba | Desarrollo/Testing |
| `seed_achievements.sql` | Carga 42 logros base | Producción/Testing |

---

## 🚀 Guía de Uso

### Escenario 1: Empezar desde Cero

Si quieres empezar con una base de datos completamente limpia y datos de prueba:

```bash
# 1. Limpiar base de datos
mysql -u root -p rolplay_edu < clean_database.sql

# 2. Cargar logros base
mysql -u root -p rolplay_edu < seed_achievements.sql

# 3. Cargar datos de prueba
mysql -u root -p rolplay_edu < seed_test_data.sql
```

### Escenario 2: Solo Agregar Logros

Si ya tienes datos y solo quieres añadir los logros:

```bash
mysql -u root -p rolplay_edu < seed_achievements.sql
```

### Escenario 3: Limpiar y Empezar de Nuevo

Si quieres resetear completamente:

```bash
mysql -u root -p rolplay_edu < clean_database.sql
```

---

## 👥 Usuarios de Prueba Creados

### Contraseña para TODOS los usuarios: `password123`

#### Administradores (2)
| Nombre | Email | Rol |
|--------|-------|-----|
| Carlos Rodríguez | admin@sena.edu.co | admin |
| María González | admin2@sena.edu.co | admin |

#### Instructores (3)
| Nombre | Email | Rol |
|--------|-------|-----|
| Juan Pérez | instructor@sena.edu.co | instructor |
| Ana Martínez | instructor2@sena.edu.co | instructor |
| Luis Sánchez | instructor3@sena.edu.co | instructor |

#### Aprendices (10)
| Nombre | Email | Ficha | Programa |
|--------|-------|-------|----------|
| Pedro García | aprendiz1@sena.edu.co | 2468101 | Análisis y Desarrollo de Software |
| Laura Torres | aprendiz2@sena.edu.co | 2468101 | Análisis y Desarrollo de Software |
| Diego Ramírez | aprendiz3@sena.edu.co | 2468102 | Gestión Administrativa |
| Camila López | aprendiz4@sena.edu.co | 2468102 | Gestión Administrativa |
| Andrés Herrera | aprendiz5@sena.edu.co | 2468103 | Técnico en Enfermería |
| Valentina Díaz | aprendiz6@sena.edu.co | 2468103 | Técnico en Enfermería |
| Sebastián Morales | aprendiz7@sena.edu.co | 2468104 | Mantenimiento Electrónico |
| Isabella Castro | aprendiz8@sena.edu.co | 2468104 | Mantenimiento Electrónico |
| Miguel Ángel Vargas | aprendiz9@sena.edu.co | 2468105 | Producción Agropecuaria |
| Sofía Jiménez | aprendiz10@sena.edu.co | 2468105 | Producción Agropecuaria |

---

## 🎭 Escenarios de Prueba Creados

El script crea **6 escenarios** en diferentes áreas y niveles:

| ID | Título | Área | Dificultad | Duración |
|----|--------|------|------------|----------|
| 1 | Gestión de Incidente de Seguridad | Tecnología | Básico | 15 min |
| 2 | Conflicto con Cliente Insatisfecho | Comercio | Intermedio | 20 min |
| 3 | Emergencia Médica en Urgencias | Salud | Avanzado | 25 min |
| 4 | Falla en Línea de Producción | Industrial | Intermedio | 20 min |
| 5 | Detección de Plaga en Cultivo | Agropecuario | Básico | 15 min |
| 6 | Trabajo en Equipo Interdisciplinario | General | Básico | 15 min |

---

## 🎮 Sesiones Completadas

El script crea **historial de sesiones** para probar reportes y estadísticas:

- **Pedro García (aprendiz1):** 3 sesiones completadas
- **Laura Torres (aprendiz2):** 4 sesiones completadas
- **Diego Ramírez (aprendiz3):** 5 sesiones completadas
- **Camila López (aprendiz4):** 2 sesiones completadas
- **Andrés Herrera (aprendiz5):** 3 sesiones completadas (alto rendimiento)
- **Valentina Díaz (aprendiz6):** 1 sesión en progreso

---

## 🏆 Logros Desbloqueados

Los usuarios tienen logros pre-desbloqueados para probar:
- Sistema de galería de logros
- Rankings
- Notificaciones
- Reportes con logros

**Total de logros en el sistema:** 42 (desde `seed_achievements.sql`)

---

## 🛣️ Rutas de Aprendizaje

Se crean **3 rutas** asignadas a diferentes fichas:

1. **Ruta de Inducción** - Fichas 2468101, 2468102
2. **Especialización en Atención al Cliente** - Ficha 2468102
3. **Liderazgo en Situaciones Críticas** - Fichas 2468103, 2468104

---

## 🔔 Notificaciones

Cada aprendiz tiene notificaciones de prueba:
- Logros desbloqueados
- Rutas asignadas
- Mensajes de instructores
- Actualizaciones del sistema

---

## ⚠️ Advertencias de Seguridad

### ❌ NO usar en producción

Estos scripts están diseñados SOLO para:
- Entornos de desarrollo local
- Testing y pruebas
- Demostraciones

### ⚠️ Contraseñas de prueba

Todos los usuarios tienen la contraseña: `password123`

**En producción:**
- Usar contraseñas seguras únicas
- Implementar política de contraseñas
- Forzar cambio de contraseña en primer login

---

## 🧪 Casos de Prueba Sugeridos

### Como Administrador
1. Login con `admin@sena.edu.co`
2. Acceder a `/admin`
3. Gestionar usuarios (crear, editar, eliminar)
4. Activar/desactivar escenarios
5. Ver estadísticas del sistema

### Como Instructor
1. Login con `instructor@sena.edu.co`
2. Acceder a `/instructor`
3. Ver dashboard con gráficos
4. Descargar reportes PDF individuales
5. Descargar reporte grupal
6. Crear rutas de aprendizaje
7. Asignar escenarios a fichas

### Como Aprendiz
1. Login con `aprendiz1@sena.edu.co` (o cualquier aprendiz)
2. Ver escenarios disponibles en `/scenarios`
3. Iniciar una simulación
4. Ver perfil y estadísticas en `/profile`
5. Ver logros en `/achievements`
6. Ver ranking en `/achievements/ranking`
7. Navegar rutas asignadas en `/routes`

---

## 🔄 Restaurar Datos de Prueba

Si modificas los datos durante las pruebas y quieres volver al estado inicial:

```bash
# Limpia y recarga todo
mysql -u root -p rolplay_edu < clean_database.sql
mysql -u root -p rolplay_edu < seed_achievements.sql
mysql -u root -p rolplay_edu < seed_test_data.sql
```

---

## 📊 Verificar Datos Insertados

Después de ejecutar los scripts, puedes verificar con:

```sql
-- Contar usuarios por rol
SELECT role, COUNT(*) as cantidad FROM users GROUP BY role;

-- Ver escenarios por área
SELECT area, COUNT(*) as cantidad FROM scenarios GROUP BY area;

-- Ver sesiones completadas
SELECT u.name, COUNT(s.id) as sesiones
FROM users u
LEFT JOIN sessions s ON u.id = s.user_id AND s.completed_at IS NOT NULL
WHERE u.role = 'aprendiz'
GROUP BY u.id, u.name;

-- Ver logros desbloqueados
SELECT u.name, COUNT(ua.id) as logros
FROM users u
LEFT JOIN user_achievements ua ON u.id = ua.user_id
WHERE u.role = 'aprendiz'
GROUP BY u.id, u.name;
```

---

## 🐛 Solución de Problemas

### Error: "Table doesn't exist"
**Causa:** No se ha ejecutado el schema.sql
**Solución:**
```bash
mysql -u root -p rolplay_edu < ../schema.sql
```

### Error: "Duplicate entry"
**Causa:** Datos ya existen en la base de datos
**Solución:**
```bash
mysql -u root -p rolplay_edu < clean_database.sql
# Luego ejecutar nuevamente los seeders
```

### Error: "Foreign key constraint fails"
**Causa:** Orden incorrecto de ejecución de scripts
**Solución:** Seguir el orden correcto:
1. clean_database.sql
2. seed_achievements.sql
3. seed_test_data.sql

---

## 📝 Notas Adicionales

- Los datos de prueba incluyen fechas recientes (enero 2026) para que los gráficos se vean realistas
- Las sesiones tienen scores variados para probar diferentes niveles de rendimiento
- Algunos aprendices tienen más sesiones que otros para probar rankings
- Hay una sesión "en progreso" para probar ese estado
- Las notificaciones incluyen estados leídas/no leídas
- Los escenarios tienen pasos completos con opciones y feedback

---

## 🔗 Referencias

- **Schema principal:** `../schema.sql`
- **Logros base:** `seed_achievements.sql`
- **Documentación:** `../../docs/FUNCIONALIDADES_IMPLEMENTADAS.md`
- **SRS:** `../../docs/SRS_RolPlay_EDU.md`

---

**Última actualización:** 27 de Enero de 2026
**Versión:** 1.0.0
