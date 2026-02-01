# 🔧 Corrección de Discrepancias del Esquema de Base de Datos

## 📋 Resumen Ejecutivo

Al contrastar el archivo `rolplay_edu.sql` (esquema actual de la base de datos) con la lógica implementada en el código PHP, se identificaron **5 discrepancias críticas** que impiden el funcionamiento correcto del sistema de escenarios dinámicos con IA.

---

## ⚠️ Problemas Identificados

### **Problema 1: Estructura de `achievements` Incorrecta** ❌ CRÍTICO

**Estado Actual (rolplay_edu.sql):**
```sql
CREATE TABLE achievements (
    id INT,
    name VARCHAR(100),
    description TEXT,
    criteria_json TEXT,      -- ❌ No usado por el código
    badge_icon VARCHAR(255), -- ❌ Debe ser 'icon'
    points INT DEFAULT 0,
    rarity ENUM('common','rare','epic','legendary'), -- ❌ Debe ser 'category'
    created_at TIMESTAMP
);
```

**Requerido por el Código:**
```sql
CREATE TABLE achievements (
    id INT,
    name VARCHAR(100),
    description TEXT,
    icon VARCHAR(50),                    -- ✅ Para Font Awesome
    category VARCHAR(50),                -- ✅ general, progreso, maestria, especial
    requirement_type VARCHAR(50),        -- ✅ sessions_completed, avg_score, etc.
    requirement_value INT,               -- ✅ Valor mínimo para desbloquear
    points INT DEFAULT 100,
    is_active TINYINT(1) DEFAULT 1,     -- ✅ Para desactivar logros
    created_at TIMESTAMP
);
```

**Archivo que falla:**
- `app/models/Achievement.php` - Métodos `findById()`, `getAll()`, `create()`
- `app/controllers/AchievementController.php` - Método `store()`

**Error esperado:**
```
Unknown column 'icon' in 'field list'
Unknown column 'requirement_type' in 'field list'
```

---

### **Problema 2: `sessions.scenario_id` NO es NULL** ❌ CRÍTICO

**Estado Actual:**
```sql
CREATE TABLE sessions (
    scenario_id INT NOT NULL,  -- ❌ NO permite NULL
    ...
);
```

**Requerido:**
```sql
CREATE TABLE sessions (
    scenario_id INT NULL,  -- ✅ NULL para sesiones dinámicas
    ...
);
```

**Razón:**
Las sesiones dinámicas no tienen un `scenario_id` (usan `program_id` en su lugar). El código intenta insertar `NULL`:

```php
// SessionController::startDynamic()
$sessionId = $sessionModel->create([
    'scenario_id' => null,  // ❌ Falla si NOT NULL
    'program_id' => $programId,
    'is_dynamic' => true
]);
```

**Error esperado:**
```
Column 'scenario_id' cannot be null
```

---

### **Problema 3: Falta columna `sessions.status`** ❌ CRÍTICO

**Estado Actual:**
```sql
CREATE TABLE sessions (
    -- ❌ No existe columna 'status'
    completed_at TIMESTAMP NULL,
    ...
);
```

**Requerido:**
```sql
CREATE TABLE sessions (
    status ENUM('pending','in_progress','completed','abandoned') DEFAULT 'pending',
    completed_at TIMESTAMP NULL,
    ...
);
```

**Archivo que falla:**
- `app/models/GameSession.php` - Método `complete()`
- `app/controllers/SessionController.php` - Método `results()`

**Código que falla:**
```php
// SessionController::results()
if ($session['status'] !== 'completed') {  // ❌ Columna no existe
    $this->redirect('/sessions/' . $sessionId . '/play');
}
```

**Error esperado:**
```
Undefined array key "status"
```

---

### **Problema 4: Falta columna `user_stats.achievements_unlocked`** ❌ CRÍTICO

**Estado Actual:**
```sql
CREATE TABLE user_stats (
    total_sessions INT,
    completed_sessions INT,
    total_points INT,
    average_score DECIMAL(5,2),
    -- ❌ No existe 'achievements_unlocked'
    ...
);
```

**Requerido:**
```sql
CREATE TABLE user_stats (
    total_sessions INT,
    completed_sessions INT,
    total_points INT,
    achievements_unlocked INT DEFAULT 0,  -- ✅ Requerido
    average_score DECIMAL(5,2),
    ...
);
```

**Archivo que falla:**
- `app/models/Achievement.php` - Métodos `addPointsToUser()`, `meetsRequirement()`

**Código que falla:**
```php
// Achievement::addPointsToUser()
$query = "
    INSERT INTO user_stats (user_id, total_points, achievements_unlocked)
    VALUES (:user_id, :points, 1)
    ON DUPLICATE KEY UPDATE
        total_points = total_points + :points,
        achievements_unlocked = achievements_unlocked + 1  -- ❌ Columna no existe
";
```

**Error esperado:**
```
Unknown column 'achievements_unlocked' in 'field list'
```

---

### **Problema 5: Columna `sessions.context_json` Obsoleta** ⚠️ ADVERTENCIA

**Estado Actual:**
```sql
CREATE TABLE sessions (
    context_json TEXT,  -- ⚠️ No usado en el código actual
    stage1_json TEXT,   -- ✅ Usado
    stage2_json TEXT,   -- ✅ Usado
    stage3_json TEXT,   -- ✅ Usado
    ...
);
```

**Situación:**
- `context_json` no se utiliza en ningún modelo ni controlador
- El código usa `stage1_json`, `stage2_json`, `stage3_json` en su lugar
- Puede eliminarse sin afectar funcionalidad

**Recomendación:**
- Mantener por seguridad (datos antiguos)
- Eliminar en futuras versiones si se confirma que no hay datos

---

## 🔧 Solución: Archivo de Migración

Se creó el archivo: **`database/migrations/fix_schema_discrepancies.sql`**

Este script SQL:
1. ✅ Reemplaza tabla `achievements` con estructura correcta
2. ✅ Migra datos antiguos de achievements (si existen)
3. ✅ Hace `sessions.scenario_id` NULLABLE
4. ✅ Agrega columna `sessions.status`
5. ✅ Migra sesiones existentes (completed_at → status='completed')
6. ✅ Agrega columna `user_stats.achievements_unlocked`
7. ✅ Calcula achievements_unlocked para usuarios existentes
8. ✅ Agrega índices optimizados
9. ✅ Realiza verificaciones finales

---

## 📝 Cómo Ejecutar la Migración

### Opción 1: Desde MySQL CLI (Recomendado)

```bash
# 1. Crear backup
cd C:\xampp\htdocs\rolplay
/c/xampp/mysql/bin/mysqldump -u root rolplay_edu > backup_antes_migracion.sql

# 2. Ejecutar migración
/c/xampp/mysql/bin/mysql -u root rolplay_edu < database/migrations/fix_schema_discrepancies.sql

# 3. Verificar resultado
/c/xampp/mysql/bin/mysql -u root rolplay_edu -e "
    SELECT 'Verificación de achievements' AS '';
    DESCRIBE achievements;
    SELECT '' AS '';
    SELECT 'Verificación de sessions' AS '';
    SHOW COLUMNS FROM sessions WHERE Field IN ('scenario_id', 'status');
    SELECT '' AS '';
    SELECT 'Verificación de user_stats' AS '';
    SHOW COLUMNS FROM user_stats WHERE Field = 'achievements_unlocked';
"
```

### Opción 2: Desde phpMyAdmin

1. Abrir phpMyAdmin: http://localhost/phpmyadmin
2. Seleccionar base de datos `rolplay_edu`
3. Ir a pestaña "Importar"
4. Seleccionar archivo: `database/migrations/fix_schema_discrepancies.sql`
5. Clic en "Continuar"
6. Verificar que no haya errores

---

## ✅ Verificación Post-Migración

### Verificación 1: Tabla `achievements`

```sql
-- Debe mostrar las nuevas columnas
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE
FROM information_schema.COLUMNS
WHERE TABLE_NAME = 'achievements'
  AND COLUMN_NAME IN ('icon', 'category', 'requirement_type', 'requirement_value', 'is_active');
```

**Resultado esperado:**
```
icon               | varchar(50)  | YES
category           | varchar(50)  | YES
requirement_type   | varchar(50)  | NO
requirement_value  | int          | NO
is_active          | tinyint(1)   | YES
```

### Verificación 2: `sessions.scenario_id` es NULLABLE

```sql
SELECT COLUMN_NAME, IS_NULLABLE, COLUMN_TYPE
FROM information_schema.COLUMNS
WHERE TABLE_NAME = 'sessions' AND COLUMN_NAME = 'scenario_id';
```

**Resultado esperado:**
```
scenario_id | YES | int
```

### Verificación 3: `sessions.status` existe

```sql
SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_DEFAULT
FROM information_schema.COLUMNS
WHERE TABLE_NAME = 'sessions' AND COLUMN_NAME = 'status';
```

**Resultado esperado:**
```
status | enum('pending','in_progress','completed','abandoned') | pending
```

### Verificación 4: `user_stats.achievements_unlocked` existe

```sql
SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT
FROM information_schema.COLUMNS
WHERE TABLE_NAME = 'user_stats' AND COLUMN_NAME = 'achievements_unlocked';
```

**Resultado esperado:**
```
achievements_unlocked | int | 0
```

---

## 🧪 Testing Post-Migración

### Test 1: Sistema de Logros

```php
// En la consola PHP o crear script temporal
$achievementModel = new \App\Models\Achievement();

// Crear logro de prueba
$achievementId = $achievementModel->create([
    'name' => 'Test Migration',
    'description' => 'Logro de prueba post-migración',
    'icon' => 'fa-check',
    'category' => 'general',
    'requirement_type' => 'sessions_completed',
    'requirement_value' => 1,
    'points' => 50
]);

echo "Logro creado con ID: $achievementId\n";

// Verificar que se creó correctamente
$logro = $achievementModel->findById($achievementId);
print_r($logro);
```

### Test 2: Sesión Dinámica

```php
// Crear sesión dinámica con scenario_id = NULL
$sessionModel = new \App\Models\GameSession();

$sessionId = $sessionModel->create([
    'user_id' => 1,
    'scenario_id' => null,  // ✅ Debe funcionar ahora
    'program_id' => 1,
    'is_dynamic' => true,
    'status' => 'pending',  // ✅ Debe funcionar ahora
    'scores_json' => json_encode([])
]);

echo "Sesión dinámica creada con ID: $sessionId\n";
```

### Test 3: Desbloquear Logro

```php
// Desbloquear logro y verificar que se actualiza achievements_unlocked
$achievementModel->unlock(1, $achievementId);

// Verificar user_stats
$query = "SELECT * FROM user_stats WHERE user_id = 1";
$stats = $achievementModel->query($query)->fetch();
print_r($stats);

// Debe mostrar achievements_unlocked = 1
```

---

## 🚨 Troubleshooting

### Error: "Table 'achievements_old_backup' already exists"

**Causa:** El script ya se ejecutó antes

**Solución:**
```sql
-- Eliminar backup antiguo y re-ejecutar
DROP TABLE IF EXISTS achievements_old_backup;
-- Luego re-ejecutar el script completo
```

### Error: "Duplicate column name 'status'"

**Causa:** La columna `status` ya existe

**Solución:**
```sql
-- Verificar si la columna ya existe
SELECT * FROM information_schema.COLUMNS
WHERE TABLE_NAME = 'sessions' AND COLUMN_NAME = 'status';

-- Si existe, saltarse esa parte de la migración
```

### Error: "achievements_unlocked no existe" (después de migración)

**Causa:** La migración falló parcialmente

**Solución:**
```sql
-- Agregar manualmente
ALTER TABLE user_stats
ADD COLUMN achievements_unlocked INT DEFAULT 0
COMMENT 'Total de logros desbloqueados'
AFTER total_points;

-- Calcular valores
UPDATE user_stats us
SET achievements_unlocked = (
    SELECT COUNT(*) FROM user_achievements ua WHERE ua.user_id = us.user_id
);
```

---

## 📊 Impacto de No Aplicar la Migración

| Funcionalidad | Estado Sin Migración | Después de Migración |
|---------------|----------------------|----------------------|
| **Crear logros** | ❌ Error SQL | ✅ Funcional |
| **Desbloquear logros** | ❌ Error SQL | ✅ Funcional |
| **Iniciar sesión dinámica** | ❌ Error NULL | ✅ Funcional |
| **Completar sesión** | ⚠️ Funciona pero sin status | ✅ Con control de estado |
| **Mostrar resultados** | ❌ Error undefined key | ✅ Funcional |
| **Rankings** | ⚠️ Parcialmente funcional | ✅ Completamente funcional |
| **Estadísticas de usuario** | ⚠️ Sin conteo de logros | ✅ Con conteo completo |

---

## 📦 Archivos Relacionados

| Archivo | Propósito |
|---------|-----------|
| `database/rolplay_edu.sql` | Esquema actual (con problemas) |
| `database/migrations/fix_schema_discrepancies.sql` | Script de corrección |
| `database/seeders/seed_achievements.sql` | Cargar 42 logros predefinidos |
| `database/seeders/seed_test_data.sql` | Cargar usuarios y datos de prueba |
| `docs/CORRECCION_ESQUEMA.md` | Este documento |

---

## ✨ Próximos Pasos Después de la Migración

1. **Cargar Logros:**
   ```bash
   /c/xampp/mysql/bin/mysql -u root rolplay_edu < database/seeders/seed_achievements.sql
   ```

2. **Cargar Usuarios de Prueba:**
   ```bash
   /c/xampp/mysql/bin/mysql -u root rolplay_edu < database/seeders/seed_test_data.sql
   ```

3. **Probar Sistema Completo:**
   - Login: `aprendiz7@sena.edu.co` / `password123`
   - Navegar a: http://localhost/rolplay/public/learner/programs
   - Iniciar simulación dinámica
   - Completar 3 etapas
   - Verificar que aparezcan logros desbloqueados

4. **Verificar Rankings:**
   - http://localhost/rolplay/public/achievements/ranking
   - Cambiar entre diferentes tipos de ranking

---

## 📞 Soporte

Si encuentras errores durante la migración:

1. Revisar logs de MySQL:
   ```bash
   tail -f C:\xampp\mysql\data\*.err
   ```

2. Restaurar backup si es necesario:
   ```bash
   /c/xampp/mysql/bin/mysql -u root rolplay_edu < backup_antes_migracion.sql
   ```

3. Consultar troubleshooting en este documento

---

**Versión:** 1.0
**Fecha:** 2026-01-28
**Autor:** Análisis Automático de Esquema
**Prioridad:** 🔴 CRÍTICA - Ejecutar antes de usar el sistema dinámico
