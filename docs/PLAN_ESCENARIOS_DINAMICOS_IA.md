# Plan de Implementación: Escenarios Dinámicos con IA (3 Etapas)

**Fecha**: 27 de Enero de 2026
**Versión**: 1.0
**Autor**: Sistema de Análisis RolPlay EDU
**API Key Gemini Configurada**: AIzaSyCxsDEVNP-C_LWLcLOOwaH8QbPuQbzDcqI

---

## 📋 Tabla de Contenidos

1. [Análisis del Requerimiento](#1-análisis-del-requerimiento)
2. [Estado Actual del Sistema](#2-estado-actual-del-sistema)
3. [Arquitectura Propuesta](#3-arquitectura-propuesta)
4. [Modificaciones al Modelo de Datos](#4-modificaciones-al-modelo-de-datos)
5. [Servicios y Componentes](#5-servicios-y-componentes)
6. [Flujo de Trabajo Completo](#6-flujo-de-trabajo-completo)
7. [Plan de Implementación (Sprints)](#7-plan-de-implementación-sprints)
8. [Modificaciones al SRS](#8-modificaciones-al-srs)
9. [Nuevos Roles y Permisos](#9-nuevos-roles-y-permisos)
10. [Preguntas Pendientes](#10-preguntas-pendientes)

---

## 1. Análisis del Requerimiento

### 1.1 Requisitos Clave

**R1. Generación Dinámica con IA**
- Los escenarios se generan en **tiempo real** usando API Gemini
- Cada escenario es **único y contextualizado** al programa de formación
- Se generan **3 momentos/etapas** por sesión

**R2. Dinámica de 3 Etapas Secuenciales**
```
Etapa 1 (Presentación)
    ↓ (Respuesta del aprendiz)
Etapa 2 (Consecuencia adaptada a la respuesta)
    ↓ (Respuesta del aprendiz)
Etapa 3 (Cierre y evaluación)
```

**R3. Evaluación de Soft Skills**
- Cada etapa evalúa **diferentes habilidades blandas**
- Los resultados **suman a la gamificación**
- Se miden **5 soft skills principales** determinadas del análisis del programa

**R4. Soft Skills Basadas en Análisis de Programa**
- Cuando el instructor carga un programa documento
- El sistema analiza con Gemini el **sector laboral**
- Identifica las **5 soft skills más relevantes** para ese sector
- Los escenarios siempre se basan en esas 5 soft skills

**R5. Criterios de Evaluación Visibles**
- Al crear/cargar el programa se muestran:
  - Las 5 soft skills identificadas
  - Criterios de evaluación por soft skill
  - Contexto del sector analizado

**R6. Acceso sin Login (Opcional)**
- Pregunta: ¿Es necesario mostrar escenarios sin estar logueado?
- **Respuesta recomendada**: NO, porque:
  - No se puede guardar progreso
  - No se puede evaluar gamificación
  - Requiere contexto de programa asignado

---

## 2. Estado Actual del Sistema

### 2.1 ✅ Ya Implementado

| Componente | Estado | Archivo |
|------------|--------|---------|
| **GeminiAIService** | ✅ Funcional | `app/services/GeminiAIService.php` |
| **ProgramAnalysisService** | ✅ Funcional | `app/services/ProgramAnalysisService.php` |
| **ScenarioGeneratorService** | ✅ Funcional | `app/services/ScenarioGeneratorService.php` |
| **Tabla `programs`** | ✅ Creada | `database/schema.sql` |
| **Tabla `scenarios`** | ✅ Creada | `database/schema.sql` |
| **Tabla `sessions`** | ✅ Creada | `database/schema.sql` |
| **Tabla `decisions`** | ✅ Creada | `database/schema.sql` |

### 2.2 ⚠️ Limitaciones Actuales

1. **Escenarios Pre-generados**: Se generan y guardan en BD, no en tiempo real
2. **Sin Sistema de Etapas**: No hay generación dinámica basada en respuestas previas
3. **Soft Skills Fijas**: Usa siempre las mismas 4 competencias predefinidas
4. **Sin Análisis de Sector**: No identifica soft skills específicas del programa
5. **Sin Visualización de Criterios**: No muestra el análisis al instructor

### 2.3 🔧 Lo Que Hay Que Cambiar

```diff
- Escenarios estáticos guardados en BD
+ Escenarios dinámicos generados en tiempo real

- 4 soft skills fijas para todos
+ 5 soft skills personalizadas por programa

- Análisis de programa genérico
+ Análisis de sector con identificación de soft skills

- Sin interfaz para ver criterios
+ Dashboard mostrando soft skills y criterios de evaluación
```

---

## 3. Arquitectura Propuesta

### 3.1 Flujo General

```
┌─────────────────────────────────────────────────────────────┐
│ 1. INSTRUCTOR CARGA PROGRAMA documento                            │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. ANÁLISIS INTELIGENTE DEL PROGRAMA (Gemini)              │
│                                                             │
│   Input: documento del programa SENA                             │
│   Proceso:                                                  │
│   - Extracción de texto del documento                            │
│   - Análisis del sector laboral                            │
│   - Identificación del perfil de egresado                  │
│   - Determinación de las 5 soft skills principales         │
│   - Definición de criterios de evaluación por soft skill   │
│                                                             │
│   Output (JSON):                                            │
│   {                                                         │
│     "nombre": "...",                                        │
│     "sector": "tecnología",                                 │
│     "soft_skills": [                                        │
│       {                                                     │
│         "nombre": "Comunicación Técnica",                   │
│         "peso": 25,                                         │
│         "criterios": ["Claridad", "Precisión", ...]        │
│       },                                                    │
│       ...5 soft skills                                      │
│     ]                                                       │
│   }                                                         │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. VISUALIZACIÓN PARA INSTRUCTOR                            │
│                                                             │
│   Dashboard del Programa:                                   │
│   ┌──────────────────────────────────────────────┐         │
│   │ 📊 Análisis Completado                        │         │
│   │                                               │         │
│   │ Sector: Tecnología                            │         │
│   │                                               │         │
│   │ Soft Skills Identificadas:                    │         │
│   │ 1. Comunicación Técnica (25%)                 │         │
│   │ 2. Trabajo en Equipo Ágil (20%)               │         │
│   │ 3. Resolución de Problemas (20%)              │         │
│   │ 4. Pensamiento Crítico (20%)                  │         │
│   │ 5. Adaptabilidad al Cambio (15%)              │         │
│   │                                               │         │
│   │ [Ver Criterios Detallados] [Generar Escenarios]│         │
│   └──────────────────────────────────────────────┘         │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. APRENDIZ INICIA SESIÓN DE ESCENARIO                      │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. GENERACIÓN DINÁMICA ETAPA 1 (Gemini)                    │
│                                                             │
│   Contexto enviado a Gemini:                                │
│   - Programa del aprendiz                                   │
│   - 5 Soft skills del sector                                │
│   - Historial previo (si existe)                            │
│                                                             │
│   Prompt:                                                   │
│   "Genera la PRIMERA ETAPA de un escenario para sector     │
│    [tecnología] evaluando principalmente [Comunicación      │
│    Técnica] y [Trabajo en Equipo]. Presenta una situación  │
│    inicial con 3 opciones de respuesta..."                 │
│                                                             │
│   Output: JSON con etapa 1                                  │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. APRENDIZ RESPONDE ETAPA 1                                │
│                                                             │
│   Decisión guardada en BD                                   │
│   Soft skills evaluadas: Comunicación (+8), Equipo (+5)    │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. GENERACIÓN DINÁMICA ETAPA 2 (Gemini)                    │
│                                                             │
│   Contexto enviado a Gemini:                                │
│   - Etapa 1 generada                                        │
│   - Opción que eligió el aprendiz                          │
│   - Evaluación de etapa 1                                   │
│                                                             │
│   Prompt:                                                   │
│   "El aprendiz eligió la opción B (analizar impacto).      │
│    Genera la SEGUNDA ETAPA como consecuencia natural,      │
│    evaluando ahora [Resolución de Problemas] y             │
│    [Pensamiento Crítico]..."                                │
│                                                             │
│   Output: JSON con etapa 2 (adaptada a la respuesta)       │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 8. APRENDIZ RESPONDE ETAPA 2                                │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 9. GENERACIÓN DINÁMICA ETAPA 3 (Gemini)                    │
│                                                             │
│   Prompt:                                                   │
│   "Genera el CIERRE del escenario evaluando                │
│    [Adaptabilidad] y proporcionando feedback integral..."  │
│                                                             │
│   Output: JSON con etapa 3 (cierre + evaluación)           │
└───────────────┬─────────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────────────────────────┐
│ 10. RESULTADOS Y GAMIFICACIÓN                               │
│                                                             │
│   - Suma de puntos de las 3 etapas                          │
│   - Evaluación por cada soft skill                          │
│   - Desbloqueo de logros                                    │
│   - Actualización de ranking                                │
└─────────────────────────────────────────────────────────────┘
```

---

## 4. Modificaciones al Modelo de Datos

### 4.1 Nueva Tabla: `program_soft_skills`

```sql
-- ============================================
-- Tabla: program_soft_skills
-- Descripción: Soft skills identificadas por programa
-- ============================================
CREATE TABLE program_soft_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    soft_skill_name VARCHAR(100) NOT NULL COMMENT 'Ej: Comunicación Técnica',
    weight DECIMAL(5,2) NOT NULL DEFAULT 20.00 COMMENT 'Peso porcentual (suma 100)',
    criteria_json TEXT NOT NULL COMMENT 'Array de criterios de evaluación',
    description TEXT NULL COMMENT 'Descripción contextualizada al sector',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
    INDEX idx_program (program_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Ejemplo de registro**:
```json
{
  "program_id": 5,
  "soft_skill_name": "Comunicación Técnica",
  "weight": 25.00,
  "criteria_json": [
    "Claridad en explicaciones técnicas",
    "Uso adecuado de terminología",
    "Adaptación del mensaje a la audiencia",
    "Documentación efectiva"
  ],
  "description": "Capacidad de comunicar conceptos técnicos a audiencias diversas en el sector tecnológico"
}
```

### 4.2 Modificación Tabla `programs`

```sql
ALTER TABLE programs
ADD COLUMN sector VARCHAR(100) NULL COMMENT 'Sector laboral identificado (tecnología, salud, comercio)',
ADD COLUMN soft_skills_generated BOOLEAN DEFAULT FALSE COMMENT 'Si ya se identificaron las soft skills',
ADD INDEX idx_sector (sector);
```

### 4.3 Modificación Tabla `sessions`

```sql
ALTER TABLE sessions
ADD COLUMN context_json TEXT NULL COMMENT 'Contexto completo de la sesión para generación dinámica',
ADD COLUMN current_stage INT DEFAULT 0 COMMENT 'Etapa actual (0, 1, 2)',
ADD COLUMN stage1_json TEXT NULL COMMENT 'Contenido generado en etapa 1',
ADD COLUMN stage2_json TEXT NULL COMMENT 'Contenido generado en etapa 2',
ADD COLUMN stage3_json TEXT NULL COMMENT 'Contenido generado en etapa 3 (cierre)';
```

### 4.4 Modificación Tabla `decisions`

```sql
ALTER TABLE decisions
ADD COLUMN stage INT NOT NULL DEFAULT 1 COMMENT 'En qué etapa se tomó (1, 2, 3)',
ADD COLUMN soft_skills_evaluated JSON NOT NULL COMMENT 'Soft skills evaluadas en esta decisión';
```

**Ejemplo**:
```json
{
  "Comunicación Técnica": 8,
  "Trabajo en Equipo": 5
}
```

---

## 5. Servicios y Componentes

### 5.1 Nuevo Servicio: `SectorAnalysisService`

**Ubicación**: `app/services/SectorAnalysisService.php`

**Responsabilidad**: Analizar el programa y determinar las 5 soft skills principales del sector.

```php
<?php
namespace App\Services;

final class SectorAnalysisService
{
    /**
     * Analiza el programa y determina el sector + 5 soft skills principales
     *
     * @param array $programAnalysis Resultado del ProgramAnalysisService
     * @return array ['sector' => string, 'soft_skills' => array]
     */
    public function identifySoftSkills(array $programAnalysis): array
    {
        $gemini = new GeminiAIService();

        $prompt = $this->buildSoftSkillsPrompt($programAnalysis);

        $response = $gemini->chat([
            ['role' => 'system', 'content' => 'Eres un experto en análisis laboral y competencias del SENA. Responde solo en JSON válido.'],
            ['role' => 'user', 'content' => $prompt]
        ], 2000, 0.5);

        $decoded = json_decode($response, true);

        return $this->validateSoftSkillsResponse($decoded);
    }

    private function buildSoftSkillsPrompt(array $programAnalysis): string
    {
        $nombre = $programAnalysis['nombre'] ?? '';
        $perfil = $programAnalysis['perfil_egresado'] ?? '';
        $contextos = implode(', ', $programAnalysis['contextos_laborales'] ?? []);

        return <<<PROMPT
Analiza este programa de formación SENA y determina:

1. El SECTOR LABORAL principal (tecnología, salud, comercio, industrial, agropecuario, servicios, etc.)

2. Las 5 SOFT SKILLS (habilidades blandas) MÁS IMPORTANTES para ese sector específico.

3. Para cada soft skill:
   - Nombre contextualizado al sector
   - Peso relativo (suma debe ser 100)
   - 4-5 criterios específicos de evaluación
   - Descripción breve

PROGRAMA:
- Nombre: {$nombre}
- Perfil de egresado: {$perfil}
- Contextos laborales: {$contextos}

Responde SOLO con este JSON:
{
  "sector": "nombre del sector",
  "soft_skills": [
    {
      "nombre": "Nombre de la soft skill",
      "peso": 25,
      "criterios": ["Criterio 1", "Criterio 2", "Criterio 3", "Criterio 4"],
      "descripcion": "Descripción contextualizada"
    },
    ... (5 soft skills total)
  ]
}

IMPORTANTE:
- Los nombres deben ser específicos del sector (ej: "Comunicación Técnica" en vez de solo "Comunicación")
- Los pesos deben sumar exactamente 100
- Los criterios deben ser medibles y observables
PROMPT;
    }
}
```

### 5.2 Nuevo Servicio: `DynamicScenarioService`

**Ubicación**: `app/services/DynamicScenarioService.php`

**Responsabilidad**: Generar escenarios dinámicamente en 3 etapas basándose en las respuestas del aprendiz.

```php
<?php
namespace App\Services;

final class DynamicScenarioService
{
    /**
     * Genera la etapa 1 del escenario
     */
    public function generateStage1(int $programId, array $softSkills): array
    {
        $gemini = new GeminiAIService();
        $prompt = $this->buildStage1Prompt($programId, $softSkills);

        $response = $gemini->chat([
            ['role' => 'system', 'content' => 'Eres un diseñador instruccional experto. Responde solo en JSON.'],
            ['role' => 'user', 'content' => $prompt]
        ], 1500, 0.8);

        return json_decode($response, true);
    }

    /**
     * Genera la etapa 2 basándose en la respuesta de etapa 1
     */
    public function generateStage2(
        array $stage1Content,
        int $chosenOption,
        array $softSkills
    ): array
    {
        $gemini = new GeminiAIService();
        $prompt = $this->buildStage2Prompt($stage1Content, $chosenOption, $softSkills);

        $response = $gemini->chat([
            ['role' => 'system', 'content' => 'Continúa el escenario de manera coherente. Responde solo en JSON.'],
            ['role' => 'user', 'content' => $prompt]
        ], 1500, 0.8);

        return json_decode($response, true);
    }

    /**
     * Genera la etapa 3 (cierre) basándose en toda la sesión
     */
    public function generateStage3(
        array $stage1Content,
        array $stage2Content,
        int $stage1Choice,
        int $stage2Choice,
        array $allScores
    ): array
    {
        $gemini = new GeminiAIService();
        $prompt = $this->buildStage3Prompt(
            $stage1Content,
            $stage2Content,
            $stage1Choice,
            $stage2Choice,
            $allScores
        );

        $response = $gemini->chat([
            ['role' => 'system', 'content' => 'Cierra el escenario con feedback constructivo. Responde solo en JSON.'],
            ['role' => 'user', 'content' => $prompt]
        ], 2000, 0.7);

        return json_decode($response, true);
    }
}
```

### 5.3 Modificar `ProgramAnalysisService`

**Agregar método**:
```php
public function analyzeAndIdentifySoftSkills(string $filePath, string $title, ?string $codigoPrograma = null): array
{
    // 1. Análisis normal del programa
    $analysis = $this->analyzeFromFile($filePath, $title, $codigoPrograma);

    // 2. Identificación de soft skills del sector
    $sectorService = new SectorAnalysisService();
    $softSkillsData = $sectorService->identifySoftSkills($analysis);

    // 3. Combinar resultados
    $analysis['sector'] = $softSkillsData['sector'];
    $analysis['soft_skills'] = $softSkillsData['soft_skills'];
    $analysis['soft_skills_generated'] = true;

    return $analysis;
}
```

---

## 6. Flujo de Trabajo Completo

### 6.1 Carga de Programa por Instructor

```php
// ProgramController.php - método analyze()

public function analyze(int $id): void
{
    $program = $this->programModel->findByIdForInstructor($id, $this->getUserId());

    // 1. Análisis del programa + identificación de soft skills
    $analysisService = new ProgramAnalysisService();
    $fullAnalysis = $analysisService->analyzeAndIdentifySoftSkills(
        $program['competencias_text'],
        $program['title'],
        $program['codigo_programa']
    );

    // 2. Guardar análisis general
    $this->programModel->updateAnalysis($id, $fullAnalysis);

    // 3. Guardar soft skills en tabla dedicada
    if (!empty($fullAnalysis['soft_skills'])) {
        $softSkillModel = new ProgramSoftSkill();
        $softSkillModel->saveSoftSkills($id, $fullAnalysis['soft_skills']);
    }

    // 4. Actualizar estado
    $this->programModel->updateStatus($id, 'completed');

    // 5. Redirigir a vista con soft skills
    Router::redirect('/instructor/programs/' . $id . '?analyzed=1');
}
```

### 6.2 Visualización para Instructor

**Vista**: `app/views/programs/show.php`

```php
<?php if ($program['soft_skills_generated']): ?>
<div class="bg-white rounded-xl shadow-lg p-8 mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        <i class="fas fa-brain text-sena-violet mr-2"></i>
        Soft Skills Identificadas para Sector: <?= htmlspecialchars($program['sector']) ?>
    </h2>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($softSkills as $skill): ?>
        <div class="border-l-4 border-sena-green bg-green-50 p-6 rounded-lg">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg text-gray-800">
                    <?= htmlspecialchars($skill['soft_skill_name']) ?>
                </h3>
                <span class="bg-sena-green text-white px-3 py-1 rounded-full text-sm font-bold">
                    <?= number_format($skill['weight'], 0) ?>%
                </span>
            </div>

            <p class="text-gray-600 text-sm mb-4">
                <?= htmlspecialchars($skill['description']) ?>
            </p>

            <div class="bg-white rounded-lg p-4">
                <p class="font-semibold text-sm text-gray-700 mb-2">Criterios de Evaluación:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <?php foreach ($skill['criteria_json'] as $criterion): ?>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-sena-green mt-1"></i>
                        <span><?= htmlspecialchars($criterion) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-8 flex gap-4">
        <a href="<?= Router::url('/instructor/programs/' . $program['id'] . '/generate-scenarios') ?>"
           class="btn-primary">
            <i class="fas fa-magic mr-2"></i>
            Generar Escenarios Basados en estas Soft Skills
        </a>

        <button onclick="exportSoftSkillsPDF()" class="btn-secondary">
            <i class="fas fa-file-documento mr-2"></i>
            Exportar Análisis documento
        </button>
    </div>
</div>
<?php endif; ?>
```

### 6.3 Inicio de Sesión de Escenario (Aprendiz)

```php
// PlayerController.php - método startDynamicSession()

public function startDynamicSession(int $scenarioId): void
{
    $userId = $this->getUserId();
    $user = $this->userModel->findById($userId);

    // 1. Obtener programa del aprendiz
    $programId = $this->getProgramIdForUser($userId);
    $softSkills = $this->softSkillModel->getByProgramId($programId);

    // 2. Crear sesión
    $sessionId = $this->sessionModel->createDynamic([
        'user_id' => $userId,
        'scenario_id' => $scenarioId,
        'program_id' => $programId,
        'current_stage' => 1,
        'context_json' => json_encode([
            'soft_skills' => $softSkills,
            'program_id' => $programId
        ])
    ]);

    // 3. Generar etapa 1 con IA
    $dynamicService = new DynamicScenarioService();
    $stage1 = $dynamicService->generateStage1($programId, $softSkills);

    // 4. Guardar etapa 1 en sesión
    $this->sessionModel->saveStage($sessionId, 1, $stage1);

    // 5. Renderizar vista con etapa 1
    $this->view('scenarios/play_dynamic', [
        'session_id' => $sessionId,
        'stage' => 1,
        'content' => $stage1,
        'soft_skills' => $softSkills
    ]);
}
```

### 6.4 Registro de Decisión y Generación de Siguiente Etapa

```php
// PlayerController.php - método recordDynamicDecision()

public function recordDynamicDecision(): void
{
    $sessionId = (int)($_POST['session_id'] ?? 0);
    $stage = (int)($_POST['stage'] ?? 0);
    $chosenOption = (int)($_POST['option'] ?? 0);

    // 1. Obtener contexto de la sesión
    $session = $this->sessionModel->findById($sessionId);
    $context = json_decode($session['context_json'], true);
    $softSkills = $context['soft_skills'];

    // 2. Obtener contenido de la etapa actual
    $currentStageContent = json_decode($session["stage{$stage}_json"], true);

    // 3. Extraer scores de la opción elegida
    $option = $currentStageContent['options'][$chosenOption];
    $scores = $option['scores'] ?? [];

    // 4. Guardar decisión
    $this->decisionModel->create([
        'session_id' => $sessionId,
        'stage' => $stage,
        'step_number' => $stage,
        'option_chosen' => $chosenOption,
        'scores_impact' => json_encode($scores),
        'soft_skills_evaluated' => json_encode($scores),
        'feedback_type' => $option['feedback'] ?? 'neutral'
    ]);

    // 5. Actualizar scores acumulados
    $this->sessionModel->updateScores($sessionId, $scores);

    // 6. Generar siguiente etapa si no es la última
    if ($stage < 3) {
        $nextStage = $stage + 1;

        $dynamicService = new DynamicScenarioService();

        if ($nextStage === 2) {
            // Generar etapa 2 basada en respuesta de etapa 1
            $stage1Content = json_decode($session['stage1_json'], true);
            $stage2 = $dynamicService->generateStage2($stage1Content, $chosenOption, $softSkills);
            $this->sessionModel->saveStage($sessionId, 2, $stage2);
        } else {
            // Generar etapa 3 (cierre)
            $stage1Content = json_decode($session['stage1_json'], true);
            $stage2Content = json_decode($session['stage2_json'], true);
            $stage1Choice = $this->decisionModel->getChoice($sessionId, 1);
            $stage2Choice = $chosenOption;
            $allScores = $this->sessionModel->getAccumulatedScores($sessionId);

            $stage3 = $dynamicService->generateStage3(
                $stage1Content,
                $stage2Content,
                $stage1Choice,
                $stage2Choice,
                $allScores
            );
            $this->sessionModel->saveStage($sessionId, 3, $stage3);
        }

        // Actualizar etapa actual
        $this->sessionModel->updateStage($sessionId, $nextStage);

        // Responder con siguiente etapa
        echo json_encode([
            'success' => true,
            'next_stage' => $nextStage,
            'redirect' => Router::url('/sessions/' . $sessionId . '/stage/' . $nextStage)
        ]);
    } else {
        // Sesión completada
        $this->sessionModel->complete($sessionId);

        // Verificar logros
        $achievementController = new AchievementController();
        $achievementController->checkUnlocks();

        echo json_encode([
            'success' => true,
            'completed' => true,
            'redirect' => Router::url('/sessions/' . $sessionId . '/results')
        ]);
    }
}
```

---

## 7. Plan de Implementación (Sprints)

### Sprint 1: Análisis de Sector y Soft Skills (1 semana)

**Objetivo**: Identificar soft skills del sector al cargar programa.

**Tareas**:
1. ✅ Configurar API Key de Gemini en `.env`
2. ✅ Crear migración para `program_soft_skills`
3. ✅ Crear modelo `ProgramSoftSkill.php`
4. ✅ Crear servicio `SectorAnalysisService.php`
5. ✅ Modificar `ProgramAnalysisService` para incluir identificación de soft skills
6. ✅ Actualizar controlador `ProgramController::analyze()`
7. ✅ Crear vista de visualización de soft skills en `programs/show.php`

**Entregables**:
- Instructor puede cargar programa y ver las 5 soft skills identificadas
- Las soft skills se guardan en BD
- Interfaz muestra criterios de evaluación

**Criterios de aceptación**:
- [ ] Al analizar un programa, se identifican automáticamente 5 soft skills
- [ ] Los pesos suman exactamente 100%
- [ ] Se muestran criterios específicos del sector
- [ ] La interfaz es clara y visualmente atractiva

---

### Sprint 2: Generación Dinámica de Etapas (2 semanas)

**Objetivo**: Generar escenarios en tiempo real con 3 etapas.

**Tareas**:
1. ✅ Crear migraciones para modificar tabla `sessions`
2. ✅ Crear modelo de sesión dinámica
3. ✅ Crear servicio `DynamicScenarioService.php`
4. ✅ Implementar prompts para etapa 1, 2 y 3
5. ✅ Modificar controlador `PlayerController`
6. ✅ Crear vista `scenarios/play_dynamic.php`
7. ✅ Implementar AJAX para flujo de etapas

**Entregables**:
- Aprendices pueden jugar escenarios dinámicos
- Cada etapa se genera basándose en la anterior
- Las respuestas impactan en las siguientes etapas

**Criterios de aceptación**:
- [ ] Etapa 1 se genera correctamente con contexto del programa
- [ ] Etapa 2 refleja la decisión tomada en etapa 1
- [ ] Etapa 3 proporciona cierre coherente y feedback
- [ ] Las soft skills evaluadas coinciden con las del programa

---

### Sprint 3: Evaluación y Gamificación (1 semana)

**Objetivo**: Sumar puntos de las 3 etapas y actualizar gamificación.

**Tareas**:
1. ✅ Modificar tabla `decisions` para incluir stage y soft_skills_evaluated
2. ✅ Actualizar lógica de puntuación en `SessionModel`
3. ✅ Crear vista de resultados con desglose por soft skill
4. ✅ Integrar con sistema de logros existente
5. ✅ Crear ranking basado en soft skills del programa

**Entregables**:
- Vista de resultados muestra evaluación por soft skill
- Logros se desbloquean basándose en desempeño
- Ranking refleja las soft skills del programa

**Criterios de aceptación**:
- [ ] Los resultados muestran puntuación por cada soft skill
- [ ] La puntuación total es coherente
- [ ] Los logros se desbloquean correctamente
- [ ] El ranking es justo y motivador

---

### Sprint 4: Refinamiento y Optimización (1 semana)

**Objetivo**: Optimizar prompts, mejorar UX y añadir exportación.

**Tareas**:
1. ✅ Optimizar prompts de Gemini para reducir tokens
2. ✅ Implementar caché de generaciones similares
3. ✅ Añadir loading states y animaciones
4. ✅ Crear exportación documento de soft skills
5. ✅ Testing completo del flujo
6. ✅ Documentación de usuario

**Entregables**:
- Generación más rápida y eficiente
- UX fluida y sin errores
- Documentación completa

**Criterios de aceptación**:
- [ ] Tiempo de generación < 10 segundos por etapa
- [ ] No hay errores de IA en condiciones normales
- [ ] La interfaz es responsive y accesible
- [ ] La documentación es clara

---

## 8. Modificaciones al SRS

### 8.1 Sección a Actualizar: "3. Requisitos Funcionales"

**Agregar nueva subsección**:

#### 3.X Generación Dinámica de Escenarios con IA

**RF-X1**: El sistema debe generar escenarios en **tiempo real** usando Gemini API.

**RF-X2**: Cada escenario debe constar de **3 etapas secuenciales**:
- Etapa 1: Presentación del problema
- Etapa 2: Consecuencia adaptada a la respuesta del aprendiz
- Etapa 3: Cierre con evaluación integral

**RF-X3**: Las etapas deben evaluarse diferentes soft skills en cada momento:
- Etapa 1: 2 soft skills principales
- Etapa 2: 2 soft skills complementarias
- Etapa 3: Todas las soft skills + feedback

**RF-X4**: Los escenarios deben basarse en las **5 soft skills** identificadas del análisis del programa.

#### 3.Y Análisis Inteligente de Programas

**RF-Y1**: Al cargar un programa documento, el sistema debe:
1. Extraer el texto del documento
2. Analizar el sector laboral
3. Identificar las 5 soft skills más relevantes para ese sector
4. Definir criterios de evaluación específicos
5. Asignar pesos relativos (suma = 100%)

**RF-Y2**: El sistema debe mostrar al instructor:
- Sector identificado
- Las 5 soft skills con descripción
- Criterios de evaluación por soft skill
- Pesos relativos

**RF-Y3**: Los escenarios generados para ese programa deben evaluar **únicamente** esas 5 soft skills.

### 8.2 Sección a Actualizar: "6. Modelo de Datos"

**Agregar tabla `program_soft_skills`** con la especificación completa.

**Modificar tabla `sessions`** para incluir campos de generación dinámica.

**Modificar tabla `decisions`** para incluir stage y soft_skills_evaluated.

---

## 9. Nuevos Roles y Permisos

### 9.1 Rol de Instructor - Funcionalidades Extendidas

**Nuevas capacidades**:

| Funcionalidad | Descripción |
|---------------|-------------|
| **Ver Soft Skills del Programa** | Visualizar las 5 soft skills identificadas con criterios |
| **Exportar Análisis** | Descargar documento con soft skills y criterios de evaluación |
| **Configurar Pesos** | (Opcional) Ajustar manualmente los pesos de las soft skills |
| **Generar Escenarios Basados en Soft Skills** | Crear escenarios que evalúen las soft skills del programa |

### 9.2 Rol de Administrador - Nuevas Vistas

**Dashboard de Soft Skills Global**:
- Ver todas las soft skills identificadas en todos los programas
- Estadísticas de soft skills más comunes por sector
- Comparativa de programas similares

**Gestión de Soft Skills Base**:
- Crear un catálogo de soft skills predefinidas
- Asignar soft skills manualmente a programas sin análisis

---

## 10. Preguntas Pendientes

### 10.1 Decisiones de Diseño

❓ **Pregunta 1**: ¿Los escenarios deben permitir acceso sin login?
**Recomendación**: NO, porque:
- No se puede guardar progreso
- No se puede asociar a un programa
- No tiene sentido la gamificación
- Requiere contexto de soft skills del programa

---

❓ **Pregunta 2**: ¿Las soft skills se pueden editar manualmente por el instructor?
**Opciones**:
- A) Solo lectura (generadas por IA, no editables)
- B) Editables con validación (suma = 100%)
- C) Editables sin restricción

**Recomendación**: **Opción B** - Permitir ajustes con validación.

---

❓ **Pregunta 3**: ¿Qué pasa si el análisis de IA falla?
**Opciones**:
- A) Usar soft skills por defecto (las 4 actuales)
- B) Mostrar error y requerir análisis manual
- C) Permitir al instructor seleccionar de un catálogo

**Recomendación**: **Opción C** - Catálogo de respaldo con soft skills predefinidas por sector.

---

❓ **Pregunta 4**: ¿Los escenarios se pueden guardar para reutilizarlos?
**Opciones**:
- A) Cada sesión genera escenarios únicos (más dinámico)
- B) Guardar escenarios populares en BD (más eficiente)
- C) Híbrido: guardar en caché por 24 horas

**Recomendación**: **Opción C** - Balance entre dinamismo y eficiencia.

---

❓ **Pregunta 5**: ¿Cuántos tokens de Gemini consume una sesión completa?
**Estimación**:
- Etapa 1: ~800 tokens
- Etapa 2: ~800 tokens
- Etapa 3: ~1000 tokens
- **Total**: ~2600 tokens por sesión

**Costo estimado** (con Gemini 2.0 Flash):
- $0.00002 por 1000 tokens de salida
- ~$0.000052 por sesión
- **100 sesiones**: $0.0052 (medio centavo de dólar)

---

❓ **Pregunta 6**: ¿Se necesita moderación de contenido generado?
**Recomendación**: SÍ
- Implementar filtro de contenido inapropiado
- Revisar manualmente primeros escenarios de cada programa
- Botón de "reportar contenido" para aprendices

---

## 11. Próximos Pasos Inmediatos

### Para Comenzar la Implementación

1. **Confirmar decisiones de diseño** (responder preguntas de sección 10)

2. **Configurar API Key**:
   ```bash
   # Editar .env
   GEMINI_API_KEY=AIzaSyCxsDEVNP-C_LWLcLOOwaH8QbPuQbzDcqI
   ```

3. **Ejecutar migraciones**:
   ```bash
   mysql -u root -p rolplay_edu < database/migrations/add_program_soft_skills.sql
   mysql -u root -p rolplay_edu < database/migrations/modify_sessions_dynamic.sql
   ```

4. **Crear archivos base**:
   - `app/services/SectorAnalysisService.php`
   - `app/services/DynamicScenarioService.php`
   - `app/models/ProgramSoftSkill.php`

5. **Probar análisis de programa**:
   - Cargar un programa de ejemplo
   - Verificar que se identifican las soft skills
   - Validar que los criterios son coherentes

---

## 12. Resumen Ejecutivo

### ✅ Lo Que Tenemos

- ✅ Infraestructura de Gemini AI funcionando
- ✅ Sistema de análisis de programas
- ✅ Generación básica de escenarios
- ✅ Sistema de gamificación

### 🔧 Lo Que Falta Implementar

1. **Identificación de soft skills del sector** (1 semana)
2. **Generación dinámica en 3 etapas** (2 semanas)
3. **Evaluación granular y gamificación** (1 semana)
4. **Refinamiento y optimización** (1 semana)

**Total estimado**: **5 semanas**

### 📊 Impacto Esperado

- **Personalización**: Cada programa tiene soft skills específicas de su sector
- **Dinamismo**: Escenarios únicos adaptados a cada aprendiz
- **Relevancia**: Evaluación contextualizada al mundo laboral real
- **Motivación**: Gamificación más significativa y justa

---

**Documento preparado por**: Sistema de Análisis RolPlay EDU
**Próxima revisión**: Después de implementar Sprint 1
**Contacto**: Ver `README.md` para soporte técnico
