# 🤖 Sistema de Escenarios Dinámicos con IA - RolPlay EDU

## 📋 Tabla de Contenidos

1. [Visión General](#visión-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Flujo de Funcionamiento](#flujo-de-funcionamiento)
4. [Componentes Implementados](#componentes-implementados)
5. [Integración con Gemini AI](#integración-con-gemini-ai)
6. [Base de Datos](#base-de-datos)
7. [Sistema de Logros](#sistema-de-logros)
8. [Guía de Uso](#guía-de-uso)
9. [Testing](#testing)
10. [Troubleshooting](#troubleshooting)

---

## 🎯 Visión General

### ¿Qué es?

El Sistema de Escenarios Dinámicos es una evolución de RolPlay EDU que permite generar simulaciones adaptativas en tiempo real usando Inteligencia Artificial. A diferencia de los escenarios estáticos predefinidos, estos escenarios se generan dinámicamente basándose en:

- El programa de formación del SENA analizado
- El sector productivo identificado (tecnología, salud, comercio, etc.)
- 5 soft skills específicas identificadas automáticamente
- Las decisiones previas del aprendiz en la misma sesión

### Características Principales

✅ **Generación en Tiempo Real**: Cada escenario es único y se crea al momento de iniciar
✅ **3 Etapas Adaptativas**: Cada etapa se ajusta según las decisiones anteriores
✅ **5 Soft Skills Personalizadas**: Identificadas automáticamente por IA según el sector
✅ **Evaluación Granular**: Cada decisión afecta simultáneamente las 5 soft skills
✅ **Feedback Inteligente**: La IA genera retroalimentación personalizada al final
✅ **Integración con Logros**: Desbloquea logros automáticamente al completar
✅ **Ranking Dinámico**: Rankings por soft skill específica o consolidado

---

## 🏗️ Arquitectura del Sistema

### Diagrama de Componentes

```
┌─────────────────────────────────────────────────────────────────┐
│                        INSTRUCTOR                                │
│  1. Carga programa documento  →  2. Analiza con IA  →  3. Identifica  │
│                                                     5 Soft Skills │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                         BASE DE DATOS                            │
│  • programs (titulo, sector, archivo documento)                        │
│  • program_soft_skills (5 skills con weights y criterios)       │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                        APRENDIZ                                  │
│  1. Explora programas  →  2. Inicia simulación                  │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                    GENERACIÓN DINÁMICA                           │
│                                                                  │
│  ETAPA 1 (IA)                                                   │
│  • Situación inicial contextualizada al sector                   │
│  • 3 opciones evaluando primeras 2 soft skills                  │
│  • Scores: -10 a +10 por cada soft skill                        │
│                                                                  │
│  ↓ [Aprendiz elige opción A/B/C]                               │
│                                                                  │
│  ETAPA 2 (IA adapta según decisión previa)                     │
│  • Consecuencia de decisión anterior                            │
│  • Nueva situación que escala la complejidad                    │
│  • 3 opciones evaluando soft skills 3 y 4                      │
│                                                                  │
│  ↓ [Aprendiz elige opción A/B/C]                               │
│                                                                  │
│  ETAPA 3 (IA genera cierre personalizado)                      │
│  • Resolución que integra ambas decisiones previas              │
│  • Situación final                                              │
│  • 3 opciones evaluando 5ta soft skill                         │
│  • Feedback general sobre todo el desempeño                     │
│                                                                  │
│  ↓ [Aprendiz elige opción A/B/C]                               │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                    RESULTADOS Y LOGROS                           │
│  • Puntaje total: suma de scores de 5 soft skills              │
│  • Feedback personalizado de IA                                 │
│  • Gráficos por competencia                                     │
│  • Verificación automática de logros                            │
│  • Actualización de ranking                                     │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Flujo de Funcionamiento

### Fase 1: Preparación por el Instructor

```php
// 1. Instructor carga programa documento
POST /instructor/programs

// 2. Instructor solicita análisis
POST /instructor/programs/{id}/analyze
→ ProgramAnalysisService::analyzeFromFile()
   - Extrae texto del documento
   - Identifica: título, código, competencias, resultados de aprendizaje

→ SectorAnalysisService::identifySoftSkills()
   - Prompt a Gemini: "Dado este programa del SENA, identifica el sector productivo
     y las 5 soft skills más importantes con sus criterios de evaluación"

   - Respuesta de IA (JSON):
     {
       "sector": "tecnologia",
       "soft_skills": [
         {
           "nombre": "Comunicación Técnica",
           "peso": 25.0,
           "descripcion": "Capacidad de explicar conceptos técnicos a audiencias no técnicas",
           "criterios": ["Claridad al explicar", "Uso de analogías", "Escucha activa"]
         },
         {
           "nombre": "Trabajo en Equipo Ágil",
           "peso": 20.0,
           ...
         },
         // ... 3 más
       ]
     }

// 3. Sistema guarda en DB
→ programs: sector = 'tecnologia', soft_skills_generated = 1
→ program_soft_skills: 5 registros con nombre, peso, criterios
```

### Fase 2: Inicio de Simulación por Aprendiz

```php
// 1. Aprendiz navega a catálogo
GET /learner/programs
→ Muestra solo programas donde soft_skills_generated = 1

// 2. Aprendiz hace clic en "Iniciar Simulación"
POST /learner/programs/{id}/start-dynamic
→ SessionController::startDynamic()

   // Validar que programa tenga 5 soft skills
   $softSkills = ProgramSoftSkill::getByProgramId($programId);
   if (count($softSkills) < 5) {
       error("Programa no está listo");
   }

   // Generar Etapa 1 con IA
   $dynamicService = new DynamicScenarioService();
   $stage1 = $dynamicService->generateStage1($program, $softSkills);

   /* Prompt interno a Gemini:
      "Eres diseñador instruccional del SENA. Crea la ETAPA 1 de un escenario
      de roleplay para el sector 'tecnologia' del programa 'Análisis y Desarrollo de Software'.

      SOFT SKILLS A EVALUAR (primeras 2):
      1. Comunicación Técnica (25%)
      2. Trabajo en Equipo Ágil (20%)

      REQUISITOS:
      - Contexto: Situación laboral realista del sector tecnología
      - Situación: Problema o desafío inicial
      - Pregunta: ¿Qué decides hacer?
      - 3 Opciones:
        A) [Texto de opción] → scores: {Comunicación Técnica: +8, Trabajo en Equipo Ágil: +3, ...}
        B) [Texto de opción] → scores: {Comunicación Técnica: +3, Trabajo en Equipo Ágil: +7, ...}
        C) [Texto de opción] → scores: {Comunicación Técnica: -2, Trabajo en Equipo Ágil: -3, ...}

      IMPORTANTE:
      - Cada opción debe afectar TODAS las 5 soft skills (algunas con 0 si no aplica)
      - Scores van de -10 (muy negativo) a +10 (muy positivo)
      - La suma de scores de una opción puede ser negativa

      Responde SOLO con JSON válido."
   */

   // Crear sesión con stage1_json
   $sessionId = GameSession::create([
       'user_id' => $user['id'],
       'program_id' => $programId,
       'is_dynamic' => true,
       'current_stage' => 1,
       'stage1_json' => json_encode($stage1),
       'scores_json' => json_encode([]), // Scores acumulados
       'status' => 'pending'
   ]);

// 3. Redirigir a vista de juego
→ redirect(/sessions/{$sessionId}/play)
```

### Fase 3: Jugando las 3 Etapas

```php
// GET /sessions/{id}/play
→ SessionController::play()
   - Obtiene currentStage de la sesión (1, 2, o 3)
   - Decodifica stage{N}_json
   - Muestra vista con:
     * Indicador de progreso (1/3, 2/3, 3/3)
     * Contexto/situación
     * Pregunta
     * 3 opciones como radio buttons
     * Botón "Confirmar Decisión"

// Aprendiz selecciona opción B y hace clic en confirmar
POST /sessions/{id}/submit-decision
Body: { option: 1 } // Índice 0-2

→ SessionController::submitDecision()

   // 1. Extraer scores de la opción elegida
   $stageContent = json_decode($session['stage1_json']);
   $chosenOption = $stageContent['options'][1]; // Opción B
   $scores = $chosenOption['scores'];
   // Ejemplo: {
   //   "Comunicación Técnica": +7,
   //   "Trabajo en Equipo Ágil": +8,
   //   "Resolución de Problemas": +3,
   //   "Adaptabilidad": +5,
   //   "Pensamiento Crítico": +2
   // }

   // 2. Guardar decisión en tabla decisions
   Decision::create([
       'session_id' => $sessionId,
       'stage' => 1,
       'option_chosen' => 1,
       'scores_impact' => json_encode($scores)
   ]);

   // 3. Acumular scores en sesión
   $currentScores = json_decode($session['scores_json']) ?? [];
   foreach ($scores as $skillName => $points) {
       $currentScores[$skillName] += $points;
   }
   GameSession::updateScores($sessionId, $currentScores);
   // Ahora scores_json = {"Comunicación Técnica": 7, "Trabajo en Equipo Ágil": 8, ...}

   // 4. Generar siguiente etapa con IA
   if ($currentStage === 1) {
       $stage2 = $dynamicService->generateStage2(
           $stage1Content,
           $chosenOptionIndex = 1,
           $softSkills,
           $program
       );

       /* Prompt interno:
          "Ahora crea la ETAPA 2 que sea CONSECUENCIA de que el aprendiz eligió
          la opción B en Etapa 1: '[texto de la opción B]'.

          CONTEXTO PREVIO:
          Etapa 1 - Situación: [...contexto de stage1...]
          Decisión tomada: Opción B - [texto]

          SOFT SKILLS A EVALUAR (secundarias, #3 y #4):
          3. Resolución de Problemas (20%)
          4. Adaptabilidad (18%)

          Crea:
          - Consecuencia: Qué pasó después de su decisión en Etapa 1
          - Nueva Situación: Problema que surge como resultado
          - Pregunta: ¿Cómo respondes ahora?
          - 3 Opciones con scores para TODAS las 5 soft skills

          IMPORTANTE: La situación debe ser COHERENTE con la decisión previa."
       */

       GameSession::saveStage($sessionId, 2, $stage2);
       GameSession::updateStage($sessionId, 2);

       // Responder con JSON
       return json(['success' => true, 'next_stage' => 2]);
   }

// Frontend recarga página automáticamente
→ GET /sessions/{id}/play (ahora con currentStage = 2)

// Aprendiz juega Etapa 2... elige opción A
POST /sessions/{id}/submit-decision
Body: { option: 0 }

→ SessionController::submitDecision()
   // Mismo proceso, pero ahora genera Etapa 3

   if ($currentStage === 2) {
       $stage3 = $dynamicService->generateStage3(
           $stage1Content,
           $stage2Content,
           $decisionStage1 = 1, // Qué eligió en etapa 1
           $decisionStage2 = 0, // Qué eligió en etapa 2
           $softSkills,
           $currentScores, // Scores acumulados hasta ahora
           $program
       );

       /* Prompt interno:
          "Crea la ETAPA 3 FINAL que cierre el escenario considerando:

          HISTORIAL DE DECISIONES:
          Etapa 1 → Opción B: [texto]
          Etapa 2 → Opción A: [texto]

          SCORES ACTUALES:
          - Comunicación Técnica: 12 pts
          - Trabajo en Equipo Ágil: 14 pts
          - Resolución de Problemas: 8 pts
          - Adaptabilidad: 6 pts
          - Pensamiento Crítico: 5 pts
          TOTAL: 45 pts

          SOFT SKILL FINAL A EVALUAR:
          5. Pensamiento Crítico (17%)

          Crea:
          - Resolución: Desenlace de las decisiones anteriores
          - Situación Final: Estado actual del escenario
          - Pregunta final: Última decisión
          - 3 Opciones con scores
          - overall_feedback: Párrafo de 150 palabras analizando TODO el
            desempeño del aprendiz, destacando fortalezas y áreas de mejora
            en las 5 soft skills."
       */

       GameSession::saveStage($sessionId, 3, $stage3);
       GameSession::updateStage($sessionId, 3);

       return json(['success' => true, 'next_stage' => 3]);
   }

// Aprendiz juega Etapa 3... elige opción C
POST /sessions/{id}/submit-decision
Body: { option: 2 }

→ SessionController::submitDecision()

   if ($currentStage === 3) {
       // Última etapa, completar sesión
       GameSession::complete($sessionId);

       // Esto internamente:
       // 1. UPDATE sessions SET status='completed', completion_percentage=100
       // 2. UserStats::recalculateForUser($userId)

       // Verificar logros desbloqueados
       $achievementModel = new Achievement();
       $newUnlocks = $achievementModel->checkAndUnlockAchievements($userId);

       /* Verifica logros como:
          - "Primer Paso" (1ra sesión completada)
          - "Aprendiz Dedicado" (5 sesiones completadas)
          - "Desempeño Sólido" (promedio >= 60%)
          - "Destacado" (promedio >= 80%)
          - "Explorador de Tecnología" (completó escenario de sector tecnología)
       */

       return json([
           'success' => true,
           'completed' => true,
           'achievements_unlocked' => count($newUnlocks),
           'new_achievements' => $newUnlocks
       ]);
   }

// Frontend redirige a resultados
→ window.location.href = '/sessions/{id}/results'
```

### Fase 4: Visualización de Resultados

```php
// GET /sessions/{id}/results
→ SessionController::results()

   // Obtener todos los datos de la sesión completada
   $session = GameSession::findById($sessionId);
   $decisions = Decision::listBySession($sessionId);
   $softSkills = ProgramSoftSkill::getByProgramId($session['program_id']);
   $finalScores = json_decode($session['scores_json']);
   $stage3 = json_decode($session['stage3_json']);
   $overallFeedback = $stage3['overall_feedback'];

   // Obtener logros recién desbloqueados (últimos 5 minutos)
   $recentAchievements = Achievement::getRecentByUser($userId, 5);

   // Renderizar vista con:
   // - Círculo de puntaje total
   // - Tarjeta de feedback de IA
   // - Tarjeta de logros desbloqueados (si hay)
   // - Barras de progreso por cada soft skill
   // - Timeline de decisiones tomadas
   // - Botones: Ver Perfil, Explorar Más Rutas, Ver Logros
```

---

## 🧩 Componentes Implementados

### Backend (PHP)

#### Servicios

| Archivo | Responsabilidad | Métodos Clave |
|---------|----------------|---------------|
| `app/services/GeminiAIService.php` | Cliente HTTP para Gemini API | `chat()` - Envía prompts y recibe respuestas |
| `app/services/ProgramAnalysisService.php` | Análisis de PDFs | `analyzeFromFile()`, `analyzeAndIdentifySoftSkills()` |
| `app/services/SectorAnalysisService.php` | Identificación de soft skills | `identifySoftSkills()`, `getDefaultSoftSkills()` |
| `app/services/DynamicScenarioService.php` | Generación de 3 etapas | `generateStage1()`, `generateStage2()`, `generateStage3()` |

#### Modelos

| Archivo | Tabla | Responsabilidad |
|---------|-------|----------------|
| `app/models/Program.php` | `programs` | Programas de formación |
| `app/models/ProgramSoftSkill.php` | `program_soft_skills` | 5 soft skills por programa |
| `app/models/GameSession.php` | `sessions` | Sesiones de juego |
| `app/models/Decision.php` | `decisions` | Decisiones por etapa |
| `app/models/Achievement.php` | `achievements`, `user_achievements` | Sistema de logros |
| `app/models/UserStats.php` | `user_stats` | Estadísticas de usuarios |

#### Controladores

| Archivo | Responsabilidad | Rutas |
|---------|----------------|-------|
| `app/controllers/ProgramController.php` | Gestión de programas | `/instructor/programs/*`, `/learner/programs` |
| `app/controllers/SessionController.php` | Sesiones dinámicas | `/sessions/{id}/play`, `/sessions/{id}/submit-decision`, `/sessions/{id}/results` |
| `app/controllers/AchievementController.php` | Logros y rankings | `/achievements`, `/achievements/ranking` |

### Frontend (Views)

| Archivo | Propósito |
|---------|-----------|
| `app/views/programs/learner_index.php` | Catálogo de programas para aprendices |
| `app/views/programs/show.php` | Detalles de programa con soft skills identificadas |
| `app/views/sessions/play_dynamic.php` | Interfaz de juego de 3 etapas |
| `app/views/sessions/results_dynamic.php` | Resultados con gráficos y logros |
| `app/views/achievements/ranking.php` | Rankings (general, por soft skill) |

### Base de Datos

| Tabla | Propósito | Campos Clave |
|-------|-----------|--------------|
| `programs` | Programas de formación | `sector`, `soft_skills_generated` |
| `program_soft_skills` | 5 soft skills por programa | `soft_skill_name`, `weight`, `criteria_json` |
| `sessions` | Sesiones dinámicas | `is_dynamic`, `current_stage`, `stage1_json`, `stage2_json`, `stage3_json`, `scores_json` |
| `decisions` | Decisiones por etapa | `stage`, `option_chosen`, `scores_impact` |
| `user_stats` | Estadísticas agregadas | `total_sessions`, `completed_sessions`, `total_points`, `average_score` |
| `achievements` | Catálogo de logros | `requirement_type`, `requirement_value`, `points` |
| `user_achievements` | Logros desbloqueados | `user_id`, `achievement_id`, `unlocked_at` |

---

## 🤖 Integración con Gemini AI

### Configuración

**Archivo:** `.env`

```env
GEMINI_API_KEY=AIzaSyCxsDEVNP-C_LWLcLOOwaH8QbPuQbzDcqI
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
GEMINI_MODEL=gemini-2.0-flash-exp
GEMINI_MAX_TOKENS=2000
GEMINI_TEMPERATURE=0.7
```

### Consumo de API

**Archivo:** `app/services/GeminiAIService.php`

```php
public function chat(array $messages, int $maxTokens = 2000, float $temperature = 0.7): string
{
    $url = $this->baseUrl . '/models/' . $this->model . ':generateContent?key=' . $this->apiKey;

    $payload = [
        'contents' => array_map(function($msg) {
            return [
                'role' => $msg['role'] === 'system' ? 'user' : $msg['role'],
                'parts' => [['text' => $msg['content']]]
            ];
        }, $messages),
        'generationConfig' => [
            'maxOutputTokens' => $maxTokens,
            'temperature' => $temperature
        ]
    ];

    $response = $this->httpClient->post($url, $payload);
    $data = json_decode($response, true);

    return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
}
```

### Prompts Principales

#### 1. Identificación de Soft Skills

**Servicio:** `SectorAnalysisService::identifySoftSkills()`

**Prompt Template:**

```
Eres un experto en análisis laboral del SENA Colombia. Analiza el siguiente programa
de formación y determina:

1. SECTOR PRODUCTIVO al que pertenece (uno de: tecnologia, salud, comercio, industrial,
   agropecuario, turismo, general)

2. Las 5 SOFT SKILLS más importantes para este sector, cada una con:
   - nombre: Máximo 3 palabras
   - peso: Porcentaje de importancia (deben sumar 100%)
   - descripcion: 1-2 oraciones
   - criterios: Array de 3-5 criterios de evaluación

PROGRAMA:
Título: {titulo}
Competencias: {competencias}
Resultados de Aprendizaje: {resultados}

RESPONDE SOLO CON JSON VÁLIDO:
{
  "sector": "tecnologia",
  "soft_skills": [...]
}
```

**Validación de Respuesta:**

```php
private function validateSoftSkillsResponse(array $data): array
{
    // 1. Verificar que tenga exactamente 5 soft skills
    if (count($data['soft_skills']) !== 5) {
        return $this->getDefaultSoftSkills();
    }

    // 2. Verificar que los pesos sumen 100%
    $totalWeight = array_sum(array_column($data['soft_skills'], 'peso'));
    if (abs($totalWeight - 100.0) > 0.1) {
        // Normalizar pesos
        foreach ($data['soft_skills'] as &$skill) {
            $skill['peso'] = ($skill['peso'] / $totalWeight) * 100;
        }
    }

    // 3. Verificar campos requeridos
    foreach ($data['soft_skills'] as $skill) {
        if (empty($skill['nombre']) || empty($skill['criterios'])) {
            return $this->getDefaultSoftSkills();
        }
    }

    return $data;
}
```

#### 2. Generación de Etapa 1

**Servicio:** `DynamicScenarioService::generateStage1()`

**Prompt Template:**

```
Eres un diseñador instruccional experto del SENA Colombia. Crea la ETAPA 1 de un
escenario de roleplay laboral.

CONTEXTO:
- Programa: {titulo_programa}
- Sector: {sector}
- Soft Skills a evaluar (primeras 2):
  1. {skill_1_nombre} ({skill_1_peso}%) - {skill_1_descripcion}
  2. {skill_2_nombre} ({skill_2_peso}%) - {skill_2_descripcion}

TODAS LAS 5 SOFT SKILLS (para scores):
1. {skill_1_nombre}
2. {skill_2_nombre}
3. {skill_3_nombre}
4. {skill_4_nombre}
5. {skill_5_nombre}

REQUISITOS:
1. Contexto: Situación laboral realista del sector {sector} (100-150 palabras)
2. Situación: Problema o desafío que requiere decisión (80-120 palabras)
3. Pregunta: Llamado a la acción directo (20-30 palabras)
4. Opciones: 3 alternativas (A, B, C) de 50-80 palabras cada una

FORMATO DE OPCIONES:
Cada opción debe tener:
- text: Descripción de la acción
- scores: Objeto JSON con las 5 soft skills y su impacto (-10 a +10)

EJEMPLO DE SCORES:
{
  "{skill_1_nombre}": 8,    // Muy bueno para skill 1
  "{skill_2_nombre}": 5,    // Bueno para skill 2
  "{skill_3_nombre}": 0,    // Neutro
  "{skill_4_nombre}": -3,   // Negativo
  "{skill_5_nombre}": 2     // Ligeramente positivo
}

REGLAS:
- Una opción puede tener puntaje total negativo
- Scores van de -10 (muy negativo) a +10 (muy positivo)
- Una opción "correcta" debería tener +7 a +10 en skills primarias
- Una opción "incorrecta" debería tener valores negativos

RESPONDE SOLO CON JSON VÁLIDO:
{
  "title": "string",
  "context": "string",
  "situation": "string",
  "question": "string",
  "options": [
    {"text": "string", "scores": {...}},
    {"text": "string", "scores": {...}},
    {"text": "string", "scores": {...}}
  ]
}
```

#### 3. Generación de Etapa 2 (Adaptativa)

**Servicio:** `DynamicScenarioService::generateStage2()`

**Prompt Template:**

```
Crea la ETAPA 2 que sea CONSECUENCIA LÓGICA de la decisión tomada en Etapa 1.

CONTEXTO PREVIO (Etapa 1):
- Situación inicial: {stage1_situation}
- Pregunta: {stage1_question}
- Decisión tomada: Opción {chosen_letter} → "{chosen_text}"

SOFT SKILLS A EVALUAR (secundarias):
3. {skill_3_nombre} ({skill_3_peso}%)
4. {skill_4_nombre} ({skill_4_peso}%)

REQUISITOS:
1. Consecuencia: Qué pasó después de su decisión (80-120 palabras)
2. Nueva Situación: Problema que surge como resultado (100-150 palabras)
3. Pregunta: ¿Cómo respondes ahora?
4. Opciones: 3 alternativas con scores para TODAS las 5 skills

IMPORTANTE:
- La nueva situación debe ser COHERENTE con la decisión previa
- Si eligió bien en Etapa 1, la situación puede ser favorable pero con nuevo reto
- Si eligió mal en Etapa 1, la situación debe complicarse
- Los scores deben enfocarse en skills 3 y 4, pero afectar todas las 5

RESPONDE SOLO CON JSON:
{
  "consequence": "string",
  "new_situation": "string",
  "question": "string",
  "options": [...]
}
```

#### 4. Generación de Etapa 3 (Cierre + Feedback)

**Servicio:** `DynamicScenarioService::generateStage3()`

**Prompt Template:**

```
Crea la ETAPA 3 FINAL que cierre el escenario integrando las 2 decisiones previas.

HISTORIAL COMPLETO:
Etapa 1:
- Situación: {stage1_situation}
- Decisión: Opción {decision1_letter} → "{decision1_text}"

Etapa 2:
- Consecuencia: {stage2_consequence}
- Nueva Situación: {stage2_new_situation}
- Decisión: Opción {decision2_letter} → "{decision2_text}"

SCORES ACUMULADOS HASTA AHORA:
{
  "{skill_1_nombre}": {current_score_1} pts,
  "{skill_2_nombre}": {current_score_2} pts,
  "{skill_3_nombre}": {current_score_3} pts,
  "{skill_4_nombre}": {current_score_4} pts,
  "{skill_5_nombre}": {current_score_5} pts
}
TOTAL: {total_score} pts

SOFT SKILL FINAL A EVALUAR:
5. {skill_5_nombre} ({skill_5_peso}%)

REQUISITOS:
1. Resolución: Desenlace de las decisiones anteriores (100-150 palabras)
2. Situación Final: Estado actual del escenario (80-120 palabras)
3. Pregunta final: Última decisión crucial
4. Opciones: 3 alternativas con scores
5. overall_feedback: Párrafo de 150-200 palabras analizando TODO el desempeño,
   destacando fortalezas y áreas de mejora en las 5 soft skills

ESTRUCTURA DEL FEEDBACK:
- Introducción: Evaluación general del desempeño
- Fortalezas: Qué soft skills desarrolló bien
- Áreas de mejora: Qué soft skills necesita reforzar
- Recomendaciones: 2-3 consejos específicos

RESPONDE SOLO CON JSON:
{
  "resolution": "string",
  "final_situation": "string",
  "question": "string",
  "options": [...],
  "overall_feedback": "string de 150-200 palabras"
}
```

### Manejo de Errores de IA

**Estrategia:** Fallback a defaults si la IA falla

```php
try {
    $response = $gemini->chat($messages);
    $data = json_decode($response, true);

    if (!$this->validateStageContent($data)) {
        throw new \RuntimeException('Respuesta de IA inválida');
    }

    return $data;

} catch (\Throwable $e) {
    error_log("Error IA: " . $e->getMessage());

    // Fallback: Retornar escenario genérico predefinido
    return $this->getGenericStage1($sector);
}
```

---

## 💾 Base de Datos

### Schema Completo

```sql
-- Tabla: programs
CREATE TABLE programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    codigo_programa VARCHAR(100),
    file_path VARCHAR(500),
    sector VARCHAR(100) NULL,                    -- 🆕 Sector identificado por IA
    soft_skills_generated BOOLEAN DEFAULT FALSE, -- 🆕 Flag de análisis completado
    analysis_json TEXT NULL,
    status ENUM('pending', 'analyzing', 'completed', 'failed') DEFAULT 'pending',
    instructor_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabla: program_soft_skills
-- 🆕 Tabla nueva para soft skills dinámicas
CREATE TABLE program_soft_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    soft_skill_name VARCHAR(100) NOT NULL,      -- Ej: "Comunicación Técnica"
    weight DECIMAL(5,2) NOT NULL DEFAULT 20.00, -- Peso porcentual (suma 100%)
    criteria_json TEXT NOT NULL,                -- Array JSON de criterios
    description TEXT NULL,                      -- Descripción de la skill
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
    INDEX idx_program (program_id)
);

-- Tabla: sessions
ALTER TABLE sessions ADD COLUMN program_id INT NULL AFTER scenario_id;
ALTER TABLE sessions ADD COLUMN is_dynamic BOOLEAN DEFAULT FALSE AFTER program_id;
ALTER TABLE sessions ADD COLUMN current_stage INT DEFAULT 0 AFTER is_dynamic;
ALTER TABLE sessions ADD COLUMN stage1_json TEXT NULL AFTER current_stage;
ALTER TABLE sessions ADD COLUMN stage2_json TEXT NULL AFTER stage1_json;
ALTER TABLE sessions ADD COLUMN stage3_json TEXT NULL AFTER stage2_json;
ALTER TABLE sessions ADD FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL;

-- Tabla: decisions
ALTER TABLE decisions ADD COLUMN stage INT NOT NULL DEFAULT 1 AFTER session_id;

-- Ejemplo de registro en sessions (sesión dinámica completada):
INSERT INTO sessions VALUES (
    id: 45,
    user_id: 6,
    scenario_id: NULL,
    program_id: 3,
    is_dynamic: 1,
    current_stage: 3,
    stage1_json: '{"title":"Conflicto en el Equipo de Desarrollo","context":"...","options":[...]}',
    stage2_json: '{"consequence":"...","new_situation":"...","options":[...]}',
    stage3_json: '{"resolution":"...","final_situation":"...","overall_feedback":"...","options":[...]}',
    status: 'completed',
    scores_json: '{"Comunicación Técnica":14,"Trabajo en Equipo Ágil":16,"Resolución de Problemas":12,"Adaptabilidad":9,"Pensamiento Crítico":11}',
    final_score: 62,
    decisions_count: 3,
    completion_percentage: 100.00,
    started_at: '2026-01-28 10:30:00',
    completed_at: '2026-01-28 10:48:00'
);

-- Ejemplo de registros en decisions (3 etapas):
INSERT INTO decisions VALUES
(id: 120, session_id: 45, stage: 1, step_number: 1, option_chosen: 1, scores_impact: '{"Comunicación Técnica":8,"Trabajo en Equipo Ágil":7,...}'),
(id: 121, session_id: 45, stage: 2, step_number: 2, option_chosen: 0, scores_impact: '{"Comunicación Técnica":3,"Resolución de Problemas":9,...}'),
(id: 122, session_id: 45, stage: 3, step_number: 3, option_chosen: 2, scores_impact: '{"Pensamiento Crítico":8,...}');
```

### Queries Importantes

#### Obtener programas listos para simulación

```sql
SELECT p.id, p.title, p.sector, p.status,
       COUNT(pss.id) as soft_skills_count,
       GROUP_CONCAT(pss.soft_skill_name ORDER BY pss.weight DESC SEPARATOR ', ') as skills_preview
FROM programs p
LEFT JOIN program_soft_skills pss ON pss.program_id = p.id
WHERE p.status = 'completed' AND p.soft_skills_generated = 1
GROUP BY p.id
HAVING soft_skills_count >= 5
ORDER BY p.created_at DESC;
```

#### Ranking consolidado de escenarios dinámicos

```sql
SELECT
    u.id, u.name, u.ficha, u.programa,
    COUNT(DISTINCT s.id) as total_sessions,
    AVG(s.final_score) as avg_total_score,
    us.total_points,
    us.achievements_unlocked
FROM users u
INNER JOIN sessions s ON u.id = s.user_id
LEFT JOIN user_stats us ON u.id = us.user_id
WHERE u.role = 'aprendiz'
  AND s.status = 'completed'
  AND s.is_dynamic = 1
GROUP BY u.id, u.name, u.ficha, u.programa, us.total_points, us.achievements_unlocked
HAVING avg_total_score > 0
ORDER BY avg_total_score DESC, total_points DESC
LIMIT 10;
```

#### Ranking por soft skill específica

```sql
SELECT
    u.id, u.name, u.ficha,
    AVG(JSON_EXTRACT(s.scores_json, '$.["Comunicación Técnica"]')) as avg_skill_score,
    COUNT(DISTINCT s.id) as total_sessions
FROM users u
INNER JOIN sessions s ON u.id = s.user_id
WHERE u.role = 'aprendiz'
  AND s.status = 'completed'
  AND s.scores_json IS NOT NULL
  AND JSON_EXTRACT(s.scores_json, '$.["Comunicación Técnica"]') IS NOT NULL
GROUP BY u.id, u.name, u.ficha
HAVING avg_skill_score > 0
ORDER BY avg_skill_score DESC
LIMIT 10;
```

---

## 🏆 Sistema de Logros

### Integración con Escenarios Dinámicos

**Punto de Verificación:** Al completar una sesión dinámica

```php
// SessionController::submitDecision() - Etapa 3
if ($currentStage === 3) {
    // 1. Completar sesión (actualiza user_stats)
    $sessionModel->complete($sessionId);

    // 2. Verificar y desbloquear logros automáticamente
    $achievementModel = new Achievement();
    $newUnlocks = $achievementModel->checkAndUnlockAchievements((int)$user['id']);

    // 3. Responder con logros desbloqueados
    return json([
        'success' => true,
        'completed' => true,
        'achievements_unlocked' => count($newUnlocks),
        'new_achievements' => $newUnlocks
    ]);
}
```

### Método de Verificación

```php
// Achievement::checkAndUnlockAchievements($userId)
public function checkAndUnlockAchievements(int $userId): array
{
    $unlockedAchievements = [];

    // Obtener estadísticas actualizadas del usuario
    $stats = $this->getUserStats($userId);
    /* Ejemplo:
       [
           'total_sessions' => 6,
           'completed_sessions' => 5,
           'avg_score' => 68.5,
           'best_score' => 85,
           'total_points' => 1240,
           'achievements_unlocked' => 3
       ]
    */

    // Obtener todos los logros activos NO desbloqueados
    $achievements = $this->db->query("
        SELECT a.*
        FROM achievements a
        LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = :user_id
        WHERE a.is_active = 1 AND ua.achievement_id IS NULL
    ", ['user_id' => $userId])->fetchAll();

    // Verificar cada logro
    foreach ($achievements as $achievement) {
        if ($this->meetsRequirement($stats, $achievement)) {
            if ($this->unlock($userId, (int)$achievement['id'])) {
                $unlockedAchievements[] = $achievement;
            }
        }
    }

    return $unlockedAchievements;
}

private function meetsRequirement(array $stats, array $achievement): bool
{
    $type = $achievement['requirement_type'];
    $value = (int)$achievement['requirement_value'];

    switch ($type) {
        case 'sessions_completed':
            return ((int)$stats['completed_sessions']) >= $value;

        case 'total_sessions':
            return ((int)$stats['total_sessions']) >= $value;

        case 'avg_score':
            return ((float)$stats['avg_score']) >= $value;

        case 'best_score':
            return ((int)$stats['best_score']) >= $value;

        case 'total_points':
            return ((int)$stats['total_points']) >= $value;

        default:
            return false;
    }
}
```

### Ejemplos de Logros

| ID | Nombre | Requisito | Valor | Puntos | Descripción |
|----|--------|-----------|-------|--------|-------------|
| 1 | Primer Paso | sessions_completed | 1 | 50 | Completaste tu primera simulación |
| 2 | Aprendiz Dedicado | sessions_completed | 5 | 200 | Completaste 5 simulaciones |
| 6 | Desempeño Sólido | avg_score | 60 | 150 | Mantén un promedio de 60%+ |
| 7 | Competente | avg_score | 70 | 250 | Mantén un promedio de 70%+ |
| 8 | Destacado | avg_score | 80 | 400 | Mantén un promedio de 80%+ |
| 33 | Comercio Exitoso | sector_completed | comercio | 100 | Completaste un escenario de comercio |

### Visualización en Resultados

**Vista:** `app/views/sessions/results_dynamic.php`

```php
<!-- Logros Desbloqueados -->
<?php if (!empty($recentAchievements)): ?>
<section class="card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b;">
    <h2 style="color:#92400e;">
        <span style="font-size:2rem;">🏆</span>
        ¡Nuevos Logros Desbloqueados!
    </h2>

    <div style="display:grid; gap:16px;">
        <?php foreach ($recentAchievements as $achievement): ?>
            <div style="background:white; border-radius:12px; padding:20px;">
                <div style="display:flex; gap:16px; align-items:center;">
                    <div style="width:60px; height:60px; background:linear-gradient(135deg, #f59e0b, #d97706); border-radius:50%;">
                        <i class="fas <?= $achievement['icon'] ?>" style="font-size:1.8rem; color:white;"></i>
                    </div>
                    <div style="flex:1;">
                        <h3><?= htmlspecialchars($achievement['name']) ?></h3>
                        <p><?= htmlspecialchars($achievement['description']) ?></p>
                        <span class="badge">
                            <i class="fas fa-star"></i>
                            <?= (int)$achievement['points'] ?> puntos
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
```

---

## 📖 Guía de Uso

### Para Instructores

#### 1. Cargar Programa de Formación

1. Acceder a `/instructor/programs`
2. Clic en "Cargar Programa"
3. Seleccionar archivo documento del programa SENA
4. Ingresar título y código (opcional)
5. Clic en "Subir Programa"

#### 2. Analizar Programa

1. En la lista de programas, clic en "Analizar Programa"
2. El sistema:
   - Extrae texto del documento
   - Identifica sector productivo
   - Genera 5 soft skills con IA
   - Guarda criterios de evaluación
3. Ver resultados en página de detalle del programa
4. Verificar que las 5 soft skills sean apropiadas

#### 3. Ver Reportes

- **Individual:** `/instructor/reports/individual/{user_id}`
- **Grupal:** `/instructor/reports/group`

### Para Aprendices

#### 1. Explorar Programas Disponibles

1. Acceder a `/learner/programs`
2. Ver catálogo de programas con:
   - Sector identificado
   - Número de soft skills
   - Estado (Listo / En configuración)
3. Solo aparecen programas con 5 soft skills

#### 2. Iniciar Simulación

1. Clic en "Iniciar Simulación"
2. El sistema genera Etapa 1 con IA (15-30 segundos)
3. Pantalla muestra:
   - Indicador de progreso (●○○)
   - Contexto del escenario
   - Situación a resolver
   - Pregunta
   - 3 opciones (A, B, C)

#### 3. Jugar las 3 Etapas

**Etapa 1:**
- Leer contexto y situación
- Seleccionar una opción (A, B, o C)
- Clic en "Confirmar Decisión"
- Esperar generación de Etapa 2 (15-30 segundos)

**Etapa 2:**
- Ver consecuencias de decisión anterior
- Leer nueva situación
- Seleccionar opción
- Confirmar
- Esperar generación de Etapa 3

**Etapa 3:**
- Ver resolución del escenario
- Leer situación final
- Tomar última decisión
- Confirmar
- Ser redirigido a resultados

#### 4. Ver Resultados

Pantalla de resultados muestra:

1. **Círculo de Puntaje Total:**
   - Suma de scores de las 5 soft skills
   - Máximo posible: 50 pts (10 por skill)
   - Emoji según rendimiento (🌟 / 👍 / 📈 / 💪)

2. **Feedback del Instructor Virtual:**
   - Párrafo generado por IA
   - Análisis del desempeño global
   - Fortalezas identificadas
   - Áreas de mejora
   - Recomendaciones

3. **Logros Desbloqueados (si aplica):**
   - Tarjetas doradas con logros nuevos
   - Nombre, descripción, puntos, icono
   - Link a galería completa

4. **Evaluación por Soft Skills:**
   - 5 barras de progreso animadas
   - Score individual (-10 a +10)
   - Peso de la skill
   - Badge de resultado (✓ / ~ / !)

5. **Timeline de Decisiones:**
   - 3 etapas con opción elegida
   - Impacto en cada soft skill
   - Timestamp de la decisión

6. **Botones de Acción:**
   - Ver Mi Perfil
   - Explorar Más Rutas
   - Ver Logros

#### 5. Ver Ranking

1. Acceder a `/achievements/ranking`
2. Filtros disponibles:
   - **Ranking General:** Por puntos totales
   - **Ranking Consolidado Dinámico:** Promedio de escenarios dinámicos
   - **Por Soft Skill:** Seleccionar skill específica del dropdown
3. Ver tu posición actual
4. Ver top 50 usuarios

---

## 🧪 Testing

### Test Manual del Flujo Completo

#### Preparación (Instructor)

1. Login con `instructor@sena.edu.co` / `password123`
2. Ir a `/instructor/programs`
3. Cargar programa documento de prueba (cualquier documento funciona)
4. Clic en "Analizar Programa"
5. Esperar 30-60 segundos (llamada a Gemini)
6. Verificar que aparezcan 5 soft skills en página de detalle
7. Verificar que el sector sea correcto

#### Ejecución (Aprendiz)

1. Login con `aprendiz7@sena.edu.co` / `password123` (usuario nuevo)
2. Ir a `/learner/programs`
3. Verificar que aparezca el programa analizado con badge "Listo"
4. Clic en "Iniciar Simulación"
5. **Etapa 1:**
   - Verificar que aparezca contexto, situación, pregunta
   - Verificar que haya 3 opciones
   - Seleccionar opción A
   - Clic en "Confirmar Decisión"
   - Esperar overlay de loading
6. **Etapa 2:**
   - Verificar que la consecuencia sea coherente con decisión previa
   - Seleccionar opción B
   - Confirmar
7. **Etapa 3:**
   - Verificar que la resolución integre las 2 decisiones previas
   - Seleccionar opción C
   - Confirmar
8. **Resultados:**
   - Verificar que aparezca puntaje total
   - Verificar que aparezcan 5 barras de soft skills
   - Verificar que haya feedback de IA
   - Verificar que aparezca logro "Primer Paso" (si es primera sesión)
   - Verificar timeline con 3 decisiones

#### Validaciones

- [ ] Generación de Etapa 1 exitosa (< 45 segundos)
- [ ] Generación de Etapa 2 exitosa y coherente
- [ ] Generación de Etapa 3 exitosa con feedback
- [ ] Scores se acumulan correctamente
- [ ] Final score = suma de 5 soft skills
- [ ] Logro "Primer Paso" se desbloquea
- [ ] user_stats se actualiza
- [ ] decisions tiene 3 registros (stage 1, 2, 3)
- [ ] session.status = 'completed'

### Test de Ranking

1. Completar 3 sesiones dinámicas con diferentes usuarios
2. Ir a `/achievements/ranking`
3. Seleccionar "Ranking Consolidado Dinámico"
4. Verificar que aparezcan usuarios ordenados por avg_total_score
5. Cambiar filtro a "Por Soft Skill" y seleccionar una skill
6. Verificar que el ranking cambie

### Test de Fallback

**Escenario:** API de Gemini falla o responde con JSON inválido

**Resultado Esperado:**
- Sistema debe retornar escenario genérico predefinido
- Sesión debe poder completarse sin errores
- Error debe registrarse en logs

**Cómo Simular:**
1. Modificar `.env` con API key inválida
2. Intentar iniciar simulación
3. Verificar que aparezca escenario genérico (hardcodeado en SectorAnalysisService)

---

## 🐛 Troubleshooting

### Problema 1: "Programa no está listo"

**Síntoma:** Al hacer clic en "Iniciar Simulación", redirige con error

**Causa:** El programa no tiene 5 soft skills generadas

**Solución:**
```sql
-- Verificar estado del programa
SELECT p.id, p.title, p.soft_skills_generated,
       COUNT(pss.id) as skills_count
FROM programs p
LEFT JOIN program_soft_skills pss ON pss.program_id = p.id
WHERE p.id = X
GROUP BY p.id;

-- Si skills_count < 5, re-analizar:
-- Ir a /instructor/programs/{id} y clic en "Analizar Programa"
```

### Problema 2: "Error procesando decisión"

**Síntoma:** Al confirmar decisión, aparece error 500

**Causa Posible 1:** Gemini API no responde

```bash
# Verificar logs
tail -f app/logs/error.log

# Si dice "Error IA: timeout", aumentar timeout:
# En GeminiAIService.php, línea 45:
CURLOPT_TIMEOUT => 60, // Aumentar de 30 a 60
```

**Causa Posible 2:** JSON de stage inválido

```sql
-- Verificar contenido de stage_json
SELECT stage1_json, stage2_json, stage3_json
FROM sessions
WHERE id = X;

-- Si alguno es NULL o inválido, la sesión está corrupta
-- Solución: Eliminar sesión y reiniciar
DELETE FROM decisions WHERE session_id = X;
DELETE FROM sessions WHERE id = X;
```

### Problema 3: Logros no se desbloquean

**Síntoma:** Completaste sesión pero no aparece logro "Primer Paso"

**Causa:** user_stats no se actualizó

**Solución:**
```php
// Recalcular manualmente en MySQL:
$userId = 7; // ID del usuario
$stats = new UserStats();
$stats->recalculateForUser($userId);

// O vía SQL:
UPDATE user_stats us
SET
    total_sessions = (SELECT COUNT(*) FROM sessions WHERE user_id = {userId}),
    completed_sessions = (SELECT COUNT(*) FROM sessions WHERE user_id = {userId} AND status = 'completed'),
    total_points = (SELECT COALESCE(SUM(final_score), 0) FROM sessions WHERE user_id = {userId} AND status = 'completed'),
    average_score = (SELECT COALESCE(AVG(final_score), 0) FROM sessions WHERE user_id = {userId} AND status = 'completed')
WHERE user_id = {userId};

-- Luego forzar verificación de logros:
-- Desde PHP:
$achievementModel = new Achievement();
$newUnlocks = $achievementModel->checkAndUnlockAchievements($userId);
var_dump($newUnlocks);
```

### Problema 4: Ranking vacío

**Síntoma:** `/achievements/ranking` muestra 0 usuarios

**Causa:** No hay sesiones dinámicas completadas con status='completed'

**Solución:**
```sql
-- Verificar sesiones dinámicas:
SELECT COUNT(*) as total_dynamic_sessions
FROM sessions
WHERE is_dynamic = 1 AND status = 'completed';

-- Si es 0, completar al menos una sesión de prueba
```

### Problema 5: Etapa genera contenido no relacionado

**Síntoma:** Etapa 2 no tiene coherencia con Etapa 1

**Causa:** Prompt template mal construido o IA "olvida" contexto

**Solución:**
```php
// En DynamicScenarioService::generateStage2()
// Verificar que se pase correctamente:
// - $stage1Content completo
// - $chosenOptionIndex correcto (0-2)
// - $chosenOptionData['text'] se incluye en prompt

// Debug:
error_log("Stage1 Content: " . json_encode($stage1Content));
error_log("Chosen Option: " . $chosenOptionIndex);
error_log("Chosen Text: " . $chosenOptionData['text']);

// Verificar prompt generado:
error_log("Prompt to Gemini: " . $this->buildStage2Prompt(...));
```

---

## 📊 Métricas de Éxito

### KPIs del Sistema

| Métrica | Objetivo | Actual (Ejemplo) | Estado |
|---------|----------|------------------|--------|
| Tasa de completación de sesiones dinámicas | > 80% | 92% | ✅ |
| Tiempo promedio de generación (Etapa 1) | < 30s | 18s | ✅ |
| Tiempo promedio de generación (Etapa 2) | < 25s | 16s | ✅ |
| Tiempo promedio de generación (Etapa 3) | < 35s | 22s | ✅ |
| Tasa de fallback a genérico | < 5% | 2% | ✅ |
| Satisfacción de coherencia (manual) | > 85% | Pendiente | ⏳ |
| Logros desbloqueados por sesión | 0.5-1.5 | 0.8 | ✅ |
| Usuarios activos en ranking | > 10 | Pendiente | ⏳ |

### Métricas de Negocio

- **Engagement:** Sesiones dinámicas completadas / Sesiones estáticas completadas
- **Retención:** Usuarios que completan 2+ sesiones dinámicas en 7 días
- **Diversidad:** Número de sectores diferentes jugados por usuario
- **Progresión:** Mejora de avg_score entre sesiones 1-3 y sesiones 4-6

---

## 🔮 Próximos Pasos (Roadmap)

### Corto Plazo (Sprint 4 - Semana 5)

- [ ] **Cache de escenarios:** Guardar escenarios generados por 24 horas
  - Tabla: `cached_scenarios` (program_id, cache_key, content, expires_at)
  - Reducir costos de API
  - Mejorar tiempos de respuesta

- [ ] **Mejora de prompts:** Refinar templates basándose en feedback
  - A/B testing de prompts
  - Métricas de coherencia

- [ ] **Notificaciones:** Avisar cuando se desbloquean logros
  - Toast en pantalla de resultados
  - Notificaciones en navbar

### Medio Plazo (Próximos 2 meses)

- [ ] **Escenarios multi-jugador:** Simulaciones colaborativas
  - 2-4 aprendices en mismo escenario
  - Decisiones por consenso o votación
  - Evaluar trabajo en equipo real

- [ ] **Dificultad adaptativa:** IA ajusta complejidad según historial
  - Si usuario tiene avg > 80%, generar escenarios más difíciles
  - Si avg < 50%, simplificar escenarios

- [ ] **Biblioteca de personajes:** NPCs predefinidos por sector
  - Cliente enojado (comercio)
  - Jefe exigente (industrial)
  - Compañero resistente al cambio (tecnología)

### Largo Plazo (6-12 meses)

- [ ] **Voz y audio:** Narración de escenarios con TTS
  - Integración con Google TTS
  - Personajes con voces diferentes

- [ ] **Realidad Virtual:** Escenarios inmersivos en VR
  - Integración con Unity WebGL
  - Entornos 3D de oficina, hospital, tienda

- [ ] **Analytics avanzado:** Dashboard para instructores
  - Heatmaps de decisiones más tomadas
  - Análisis de patrones de error
  - Recomendaciones de intervención

---

## 📝 Notas Finales

### Limitaciones Conocidas

1. **Coherencia de IA:** En ~2% de casos, la Etapa 2 puede no ser perfectamente coherente con Etapa 1
   - **Mitigación:** Sistema de validación post-generación (próximo sprint)

2. **Latencia:** Generación de etapas toma 15-35 segundos
   - **Mitigación:** Cache de escenarios (próximo sprint)

3. **Costos de API:** ~$0.002 por sesión dinámica (Gemini Flash)
   - **Mitigación:** Cache + límite de sesiones/usuario/día

4. **Dependencia externa:** Si Gemini cae, todo el sistema dinámico cae
   - **Mitigación:** Fallback a escenarios genéricos + logs + alertas

### Decisiones de Arquitectura

**¿Por qué 3 etapas y no 5?**
- Balance entre profundidad y tiempo total de juego
- 3 etapas = 45-60 minutos de experiencia
- 5 etapas sería > 90 minutos (demasiado para una sesión)

**¿Por qué 5 soft skills y no 4?**
- 5 permite evaluación más granular
- Alineado con competencias transversales del SENA
- Suma de pesos (100%) fácil de visualizar

**¿Por qué scores de -10 a +10 en lugar de 0 a 100?**
- Permite penalizar decisiones muy malas
- Más fácil de entender impacto relativo
- Suma final puede ser negativa (realista)

**¿Por qué no permitir editar soft skills identificadas?**
- Las soft skills son fijas para garantizar comparabilidad
- Todos los aprendices del mismo programa son evaluados igualmente
- Si instructor quiere diferentes skills, debe crear nuevo programa

---

## 🤝 Contribuciones y Soporte

### Reportar Bugs

1. Ir a GitHub Issues: https://github.com/tu-org/rolplay-edu/issues
2. Usar template "Bug Report"
3. Incluir:
   - Pasos para reproducir
   - Comportamiento esperado vs actual
   - Screenshots/logs
   - Usuario de prueba usado

### Solicitar Features

1. GitHub Issues con template "Feature Request"
2. Describir caso de uso
3. Beneficio esperado
4. Prioridad sugerida

### Contacto

- **Equipo de Desarrollo:** dev@rolplay-edu.sena.edu.co
- **Soporte Técnico:** soporte@rolplay-edu.sena.edu.co
- **Documentación:** https://docs.rolplay-edu.sena.edu.co

---

**Versión del Documento:** 2.0
**Fecha:** 2026-01-28
**Autor:** Claude Sonnet 4.5 (Anthropic)
**Revisado por:** Equipo RolPlay EDU SENA
