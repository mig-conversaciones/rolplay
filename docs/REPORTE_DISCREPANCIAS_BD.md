# Reporte de Discrepancias BD vs Código

**Fecha:** 29 de Enero de 2026
**Base de datos:** `rolplay_edu`
**Estado:** ✅ CORREGIDO

---

## Resumen Ejecutivo

Se identificaron **discrepancias críticas** entre la estructura de la base de datos MySQL y los modelos PHP que causaban errores fatales al hacer login (acceso al dashboard de admin).

**Error original:**
```
Fatal error: Uncaught PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'is_active' in 'where clause'
in Achievement.php:525
```

---

## Discrepancias Encontradas

### 1. Tabla `achievements` - CRÍTICO ⚠️

**Estructura en BD ANTES de la corrección:**
| Columna | Tipo | Existe |
|---------|------|--------|
| id | int(11) | ✅ |
| name | varchar(100) | ✅ |
| description | text | ✅ |
| criteria_json | text | ✅ |
| badge_icon | varchar(255) | ✅ |
| category | varchar(50) | ✅ |
| points | int(11) | ✅ |
| rarity | enum | ✅ |
| created_at | timestamp | ✅ |
| **icon** | varchar(50) | ❌ FALTABA |
| **requirement_type** | varchar(50) | ❌ FALTABA |
| **requirement_value** | int(11) | ❌ FALTABA |
| **is_active** | tinyint(1) | ❌ FALTABA |

**Columnas usadas por [Achievement.php](../app/models/Achievement.php) que NO existían:**
- `icon` (líneas 25, 51, 75, 99, 155, 437, 461)
- `requirement_type` (líneas 28, 101, 201, 247, 438, 463)
- `requirement_value` (líneas 29, 102, 248, 439, 464)
- `is_active` (líneas 33, 108, 203, 440, 454, 505, 522)

---

### 2. Tabla `user_stats` - MODERADO ⚠️

**Estructura en BD ANTES de la corrección:**
| Columna | Tipo | Existe |
|---------|------|--------|
| id | int(11) | ✅ |
| user_id | int(11) | ✅ |
| total_sessions | int(11) | ✅ |
| completed_sessions | int(11) | ✅ |
| total_points | int(11) | ✅ |
| average_score | decimal(5,2) | ✅ |
| best_competence | varchar(50) | ✅ |
| scenarios_completed_ids | text | ✅ |
| last_activity | timestamp | ✅ |
| **achievements_unlocked** | int(11) | ❌ FALTABA |

**Uso en código:**
- [Achievement.php:174-178](../app/models/Achievement.php#L174-L178) - método `addPointsToUser()`

---

### 3. Tabla `scenarios` - REDUNDANCIA ℹ️

La tabla tiene DOS columnas para el mismo propósito:
- `is_active` (tinyint)
- `active` (tinyint)

Esto no causa errores pero es inconsistente. El modelo [Scenario.php](../app/models/Scenario.php) usa `is_active`.

---

## Archivos Afectados

| Archivo | Líneas Problemáticas | Estado |
|---------|---------------------|--------|
| [Achievement.php](../app/models/Achievement.php) | 25-33, 99-108, 201-203, 437-465, 505, 522 | ✅ Corregido |
| [AdminController.php](../app/controllers/AdminController.php) | 53 (llama a `getStats()`) | ✅ Corregido |

---

## Correcciones Aplicadas

### Migración ejecutada: [fix_achievements_and_user_stats.sql](../database/migrations/fix_achievements_and_user_stats.sql)

```sql
-- 1. Añadir columnas faltantes en achievements
ALTER TABLE achievements ADD COLUMN icon VARCHAR(50);
ALTER TABLE achievements ADD COLUMN requirement_type VARCHAR(50);
ALTER TABLE achievements ADD COLUMN requirement_value INT;
ALTER TABLE achievements ADD COLUMN is_active TINYINT(1) DEFAULT 1;

-- 2. Añadir columna faltante en user_stats
ALTER TABLE user_stats ADD COLUMN achievements_unlocked INT DEFAULT 0;
```

### Seeder ejecutado: [seed_achievements.sql](../database/seeders/seed_achievements.sql)

Se poblaron **39 logros** con los campos correctos:
- Progreso: 5 logros
- Excelencia: 10 logros
- Social: 3 logros
- Especial: 8 logros
- General: 13 logros

---

## Verificación Post-Corrección

```
=== VERIFICACIÓN FINAL ===

1. Logros activos: 39
2. Categorías: 5
3. Puntos posibles: 2,445
4. Consulta Achievement::getStats(): ✅ FUNCIONA
5. Login dashboard admin: ✅ FUNCIONA
```

---

## Recomendaciones

1. **Sincronizar schema.sql** - El archivo [schema.sql](../database/schema.sql) debe actualizarse para reflejar la estructura correcta

2. **Eliminar columnas obsoletas** - Considerar eliminar:
   - `achievements.criteria_json`
   - `achievements.badge_icon` (reemplazado por `icon`)
   - `achievements.rarity`
   - `scenarios.active` (duplicado de `is_active`)

3. **Documentar migraciones** - Mantener un registro de todas las migraciones aplicadas en [EJECUTAR_EN_ORDEN.md](../database/EJECUTAR_EN_ORDEN.md)

---

## Historial de Cambios

| Fecha | Cambio | Archivo |
|-------|--------|---------|
| 2026-01-29 | Creación de migración | fix_achievements_and_user_stats.sql |
| 2026-01-29 | Ejecución de seeder | seed_achievements.sql |
| 2026-01-29 | Verificación completa | - |

---

**Autor:** Claude Code
**Versión:** 1.0
