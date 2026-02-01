# 🔐 Accesos al Sistema - RolPlay EDU

## 🌐 URL de Acceso

**Desarrollo Local:**
```
http://localhost/rolplay/public/
```

**Login:**
```
http://localhost/rolplay/public/login
```

---

## 👤 Usuarios y Contraseñas

### 🔑 Contraseña Universal

**TODOS los usuarios usan la misma contraseña:**
```
password123
```

---

## 🛡️ ADMINISTRADORES (2 usuarios)

Acceso completo al sistema.

| Nombre | Email | Contraseña | Rol |
|--------|-------|------------|-----|
| Carlos Rodríguez | `admin@sena.edu.co` | `password123` | admin |
| María González | `admin2@sena.edu.co` | `password123` | admin |

**Dashboard:** http://localhost/rolplay/public/admin

**Funciones:**
- ✅ Gestión completa de usuarios (crear, editar, eliminar, cambiar roles)
- ✅ Gestión de escenarios (activar/desactivar)
- ✅ Configuración del sistema
- ✅ Reportes globales
- ✅ Vista de todas las estadísticas

---

## 👨‍🏫 INSTRUCTORES (3 usuarios)

Pueden crear rutas, programas y ver reportes de aprendices.

| Nombre | Email | Contraseña | Rol |
|--------|-------|------------|-----|
| Juan Pérez | `instructor@sena.edu.co` | `password123` | instructor |
| Ana Martínez | `instructor2@sena.edu.co` | `password123` | instructor |
| Luis Sánchez | `instructor3@sena.edu.co` | `password123` | instructor |

**Dashboard:** http://localhost/rolplay/public/instructor

**Funciones:**
- ✅ Cargar programas de formación (documento)
- ✅ Analizar programas con IA (identificar 5 soft skills)
- ✅ Crear y gestionar rutas de aprendizaje
- ✅ Generar escenarios dinámicos
- ✅ Ver reportes de aprendices asignados
- ✅ Gestionar logros

---

## 🎓 APRENDICES (10 usuarios)

Pueden jugar escenarios, completar rutas y ver sus logros.

### Ficha 2468101 - Análisis y Desarrollo de Software

| Nombre | Email | Contraseña | Ficha | Sesiones | Puntos | Logros |
|--------|-------|------------|-------|----------|--------|--------|
| **Pedro García** | `aprendiz1@sena.edu.co` | `password123` | 2468101 | 3 | 560 | 3 🏆 |
| **Laura Torres** | `aprendiz2@sena.edu.co` | `password123` | 2468101 | 4 | 865 | 4 🏆 |

### Ficha 2468102 - Gestión Administrativa

| Nombre | Email | Contraseña | Ficha | Sesiones | Puntos | Logros |
|--------|-------|------------|-------|----------|--------|--------|
| **Diego Ramírez** | `aprendiz3@sena.edu.co` | `password123` | 2468102 | 5 | 1240 | 6 🏆 |
| **Camila López** | `aprendiz4@sena.edu.co` | `password123` | 2468102 | 2 | 555 | 4 🏆 |

### Ficha 2468103 - Técnico en Enfermería

| Nombre | Email | Contraseña | Ficha | Sesiones | Puntos | Logros |
|--------|-------|------------|-------|----------|--------|--------|
| **Andrés Herrera** | `aprendiz5@sena.edu.co` | `password123` | 2468103 | 3 | 895 | 7 🏆 |
| **Valentina Díaz** | `aprendiz6@sena.edu.co` | `password123` | 2468103 | 0 | 0 | 0 🏆 |

### Ficha 2468104 - Mantenimiento Electrónico

| Nombre | Email | Contraseña | Ficha | Sesiones | Puntos | Logros |
|--------|-------|------------|-------|----------|--------|--------|
| **Sebastián Morales** | `aprendiz7@sena.edu.co` | `password123` | 2468104 | 0 | 0 | 0 🏆 |
| **Isabella Castro** | `aprendiz8@sena.edu.co` | `password123` | 2468104 | 0 | 0 | 0 🏆 |

### Ficha 2468105 - Producción Agropecuaria

| Nombre | Email | Contraseña | Ficha | Sesiones | Puntos | Logros |
|--------|-------|------------|-------|----------|--------|--------|
| **Miguel Ángel Vargas** | `aprendiz9@sena.edu.co` | `password123` | 2468105 | 0 | 0 | 0 🏆 |
| **Sofía Jiménez** | `aprendiz10@sena.edu.co` | `password123` | 2468105 | 0 | 0 | 0 🏆 |

---

## 🎯 Recomendaciones de Testing

### Para Probar el Sistema Completo

**Instructor (Cargar y Analizar Programa):**
```
Usuario: instructor@sena.edu.co
Contraseña: password123
```

**Aprendiz con Historial:**
```
Usuario: aprendiz3@sena.edu.co
Contraseña: password123
(Diego Ramírez - usuario más activo con 5 sesiones)
```

**Aprendiz Nuevo (Sin Historial):**
```
Usuario: aprendiz7@sena.edu.co
Contraseña: password123
(Sebastián Morales - cuenta limpia para probar desde cero)
```

**Administrador:**
```
Usuario: admin@sena.edu.co
Contraseña: password123
```

---

## 📊 Top 3 Aprendices (Por Promedio)

| Posición | Nombre | Email | Promedio | Sesiones |
|----------|--------|-------|----------|----------|
| 🥇 | Andrés Herrera | aprendiz5@sena.edu.co | 298.33 pts | 3 |
| 🥈 | Camila López | aprendiz4@sena.edu.co | 277.50 pts | 2 |
| 🥉 | Diego Ramírez | aprendiz3@sena.edu.co | 248.00 pts | 5 |

---

## 🔗 Links Rápidos por Rol

### Como Administrador
- Dashboard: http://localhost/rolplay/public/admin
- Gestión de Usuarios: http://localhost/rolplay/public/admin/users
- Configuración: http://localhost/rolplay/public/admin/settings

### Como Instructor
- Dashboard: http://localhost/rolplay/public/instructor
- Mis Programas: http://localhost/rolplay/public/instructor/programs
- Crear Programa: http://localhost/rolplay/public/instructor/programs/create
- Mis Rutas: http://localhost/rolplay/public/instructor/routes
- Reportes: http://localhost/rolplay/public/instructor/reports/group

### Como Aprendiz
- Programas Disponibles: http://localhost/rolplay/public/learner/programs
- Mis Rutas: http://localhost/rolplay/public/routes
- Escenarios Estáticos: http://localhost/rolplay/public/scenarios
- Mi Perfil: http://localhost/rolplay/public/profile
- Mis Logros: http://localhost/rolplay/public/achievements
- Ranking: http://localhost/rolplay/public/achievements/ranking

---

## 🧪 Flujo de Prueba Completo

### 1️⃣ Como Instructor: Cargar Programa

```
1. Login: instructor@sena.edu.co / password123
2. Ir a: http://localhost/rolplay/public/instructor/programs
3. Clic en "Cargar Programa"
4. Subir cualquier documento
5. Clic en "Analizar Programa"
6. Esperar 30-60 segundos (IA trabajando)
7. Verificar que aparezcan 5 soft skills identificadas
```

### 2️⃣ Como Aprendiz: Iniciar Simulación Dinámica

```
1. Login: aprendiz7@sena.edu.co / password123
2. Ir a: http://localhost/rolplay/public/learner/programs
3. Seleccionar programa con badge "✓ Listo"
4. Clic en "Iniciar Simulación"
5. Jugar Etapa 1 → Seleccionar opción → Confirmar
6. Jugar Etapa 2 → Seleccionar opción → Confirmar
7. Jugar Etapa 3 → Seleccionar opción → Confirmar
8. Ver resultados con:
   - Puntaje total
   - Feedback de IA
   - Logros desbloqueados
   - Evaluación por soft skills
```

### 3️⃣ Verificar Logros Desbloqueados

```
1. Ir a: http://localhost/rolplay/public/achievements
2. Verificar que aparezca logro "Primer Paso" (si es primera sesión)
3. Ver galería completa de 42 logros
```

### 4️⃣ Ver Ranking

```
1. Ir a: http://localhost/rolplay/public/achievements/ranking
2. Cambiar entre tipos de ranking:
   - Consolidado Dinámico (default)
   - General (por puntos)
   - Por Soft Skill específica
```

---

## ⚙️ Configuración de Base de Datos

**Nombre de BD:** `rolplay_edu`

**Usuario MySQL:** `root`

**Sin contraseña** (instalación XAMPP por defecto)

**Puerto:** `3306`

---

## 🔧 Troubleshooting

### No puedo iniciar sesión

```
Verificar que ejecutaste los seeders:
/c/xampp/mysql/bin/mysql -u root rolplay_edu < database/seeders/seed_test_data.sql
```

### No aparecen programas para simular

```
1. Login como instructor
2. Cargar programa documento
3. Analizar programa (esperar 30-60s)
4. Verificar que tenga 5 soft skills
5. Logout y login como aprendiz
6. Ir a /learner/programs
```

### No se desbloquean logros

```
Verificar que ejecutaste:
1. database/migrations/fix_schema_discrepancies.sql
2. database/migrations/fix_foreign_keys.sql
3. database/seeders/seed_achievements.sql
```

---

## 📝 Notas Importantes

- ⚠️ **NO USAR EN PRODUCCIÓN** - Estas contraseñas son públicas
- 🔄 Los datos son regenerables ejecutando los seeders nuevamente
- 🗑️ Para limpiar: ejecutar `database/seeders/clean_database.sql`
- 💾 Para backup: `mysqldump -u root rolplay_edu > backup.sql`

---

## 📞 Documentación Adicional

- **USUARIOS_PRUEBA.md**: Información detallada de cada usuario
- **SISTEMA_DINAMICO_IA.md**: Documentación técnica completa
- **CORRECCION_ESQUEMA.md**: Guía de migración de base de datos
- **EJECUTAR_EN_ORDEN.md**: Orden de ejecución de scripts SQL

---

**Versión:** 1.0
**Fecha:** 2026-01-28
**Sistema:** RolPlay EDU - Escenarios Dinámicos con IA
