# 🚀 Orden de Ejecución de Scripts SQL

## ⚠️ IMPORTANTE: Ejecutar en este orden exacto

### Paso 0: Backup (OBLIGATORIO)

```bash
cd C:\xampp\htdocs\rolplay
/c/xampp/mysql/bin/mysqldump -u root rolplay_edu > backup_antes_de_todo_$(date +%Y%m%d_%H%M%S).sql
```

---

### Paso 1: Corregir Esquema Principal

```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu < database/migrations/fix_schema_discrepancies.sql
```

**Verifica que se ejecutó correctamente:**
```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu -e "
    SELECT COUNT(*) as total_achievements FROM achievements;
    DESCRIBE achievements;
"
```

**Resultado esperado:**
- `total_achievements`: 0 (tabla vacía pero con estructura correcta)
- Columnas: `id`, `name`, `description`, `icon`, `category`, `requirement_type`, `requirement_value`, `points`, `is_active`, `created_at`

---

### Paso 2: Corregir Foreign Keys

```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu < database/migrations/fix_foreign_keys.sql
```

**Verifica que se ejecutó correctamente:**
```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu -e "
    SELECT
        CONSTRAINT_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'rolplay_edu'
      AND TABLE_NAME = 'user_achievements'
      AND CONSTRAINT_NAME LIKE '%ibfk%';
"
```

**Resultado esperado:**
```
user_achievements_ibfk_1 | users        | id
user_achievements_ibfk_2 | achievements | id
```

---

### Paso 3: Cargar Logros (42 logros predefinidos)

```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu < database/seeders/seed_achievements.sql
```

**Verifica que se cargaron:**
```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu -e "
    SELECT COUNT(*) as total_logros FROM achievements;
    SELECT category, COUNT(*) as cantidad FROM achievements GROUP BY category;
"
```

**Resultado esperado:**
- `total_logros`: 42
- Categorías: progreso (5), excelencia (5), social (5), especial (27)

---

### Paso 4: Cargar Usuarios de Prueba

```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu < database/seeders/seed_test_data.sql
```

**Verifica que se cargaron:**
```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu -e "
    SELECT role, COUNT(*) as cantidad FROM users GROUP BY role;
    SELECT COUNT(*) as total_escenarios FROM scenarios;
    SELECT COUNT(*) as total_sesiones FROM sessions;
"
```

**Resultado esperado:**
- Usuarios: admin (2), instructor (3), aprendiz (10)
- Escenarios: 6 (estáticos de prueba)
- Sesiones: 20 (sesiones de prueba completadas)

---

## ✅ Verificación Final Completa

```bash
/c/xampp/mysql/bin/mysql -u root rolplay_edu << 'EOF'
SELECT '========================================' AS '';
SELECT 'VERIFICACIÓN FINAL DEL SISTEMA' AS '';
SELECT '========================================' AS '';

SELECT '' AS '';
SELECT '1. Tabla achievements' AS '';
SELECT CONCAT('   Total logros: ', COUNT(*)) as info FROM achievements;

SELECT '' AS '';
SELECT '2. Tabla users' AS '';
SELECT role, COUNT(*) as cantidad FROM users GROUP BY role;

SELECT '' AS '';
SELECT '3. Tabla scenarios' AS '';
SELECT CONCAT('   Total escenarios: ', COUNT(*)) as info FROM scenarios;

SELECT '' AS '';
SELECT '4. Tabla sessions' AS '';
SELECT
    CONCAT('   Total sesiones: ', COUNT(*)) as info_1,
    CONCAT('   Completadas: ', COUNT(CASE WHEN status = 'completed' THEN 1 END)) as info_2,
    CONCAT('   Dinámicas: ', COUNT(CASE WHEN is_dynamic = 1 THEN 1 END)) as info_3
FROM sessions;

SELECT '' AS '';
SELECT '5. Tabla user_achievements' AS '';
SELECT CONCAT('   Logros desbloqueados: ', COUNT(*)) as info FROM user_achievements;

SELECT '' AS '';
SELECT '6. Tabla user_stats' AS '';
SELECT CONCAT('   Usuarios con estadísticas: ', COUNT(*)) as info FROM user_stats;

SELECT '' AS '';
SELECT '7. Tabla programs' AS '';
SELECT CONCAT('   Programas cargados: ', COUNT(*)) as info FROM programs;

SELECT '' AS '';
SELECT '8. Tabla program_soft_skills' AS '';
SELECT CONCAT('   Soft skills identificadas: ', COUNT(*)) as info FROM program_soft_skills;

SELECT '' AS '';
SELECT '========================================' AS '';
SELECT 'SISTEMA LISTO PARA USAR' AS '';
SELECT '========================================' AS '';
EOF
```

**Resultado esperado:**
```
1. Tabla achievements
   Total logros: 42

2. Tabla users
   role        | cantidad
   ------------|----------
   admin       | 2
   instructor  | 3
   aprendiz    | 10

3. Tabla scenarios
   Total escenarios: 6

4. Tabla sessions
   Total sesiones: 20
   Completadas: 20
   Dinámicas: 0 (por ahora, se crearán cuando uses el sistema)

5. Tabla user_achievements
   Logros desbloqueados: 19

6. Tabla user_stats
   Usuarios con estadísticas: 6

7. Tabla programs
   Programas cargados: 0 (cargar desde el sistema)

8. Tabla program_soft_skills
   Soft skills identificadas: 0 (se generan al analizar programas)
```

---

## 🧪 Probar el Sistema

### Test 1: Login

```
URL: http://localhost/rolplay/public/login
Usuario: aprendiz1@sena.edu.co
Contraseña: password123
```

### Test 2: Ver Logros

```
URL: http://localhost/rolplay/public/achievements
```

Deberías ver:
- 42 logros en total
- 3 logros desbloqueados (si usas aprendiz1)
- Categorías organizadas

### Test 3: Ver Ranking

```
URL: http://localhost/rolplay/public/achievements/ranking
```

Deberías ver:
- Ranking consolidado de escenarios dinámicos (default)
- Filtros para cambiar tipo de ranking

### Test 4: Cargar Programa (como instructor)

```
1. Login: instructor@sena.edu.co / password123
2. URL: http://localhost/rolplay/public/instructor/programs
3. Clic en "Cargar Programa"
4. Subir cualquier documento
5. Clic en "Analizar Programa"
6. Esperar 30-60 segundos
7. Ver que se identificaron 5 soft skills
```

### Test 5: Iniciar Simulación Dinámica (como aprendiz)

```
1. Login: aprendiz7@sena.edu.co / password123
2. URL: http://localhost/rolplay/public/learner/programs
3. Seleccionar programa con badge "Listo"
4. Clic en "Iniciar Simulación"
5. Completar 3 etapas
6. Ver resultados con logros desbloqueados
```

---

## 🐛 Si algo sale mal

### Error: "achievements_old_backup already exists"

```sql
DROP TABLE IF EXISTS achievements_old_backup;
-- Luego re-ejecutar Paso 1
```

### Error: "Duplicate column name 'status'"

```sql
-- La columna ya existe, saltarse ese error
-- Continuar con siguiente paso
```

### Error: Foreign key apunta a tabla incorrecta

```bash
# Re-ejecutar Paso 2
/c/xampp/mysql/bin/mysql -u root rolplay_edu < database/migrations/fix_foreign_keys.sql
```

### Error: "Unknown column 'name' in field list"

```bash
# Verificar que Paso 1 se ejecutó correctamente
/c/xampp/mysql/bin/mysql -u root rolplay_edu -e "DESCRIBE achievements;"

# Si no tiene columna 'name', re-ejecutar Paso 1
```

---

## 📞 Archivos de Referencia

- **CORRECCION_ESQUEMA.md**: Documentación detallada de problemas
- **SISTEMA_DINAMICO_IA.md**: Documentación técnica completa
- **USUARIOS_PRUEBA.md**: Lista de usuarios y contraseñas

---

**Fecha:** 2026-01-28
**Versión:** 1.0 - Corrección de Errores Críticos
