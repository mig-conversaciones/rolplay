<?php

use App\Core\Router;
use App\Models\SystemSetting;

$program = $program ?? null;
$scenarios = $scenarios ?? [];
$puterEnabled = (SystemSetting::get('puter_enabled', '1') ?? '1') === '1';
$puterAutoLogin = (SystemSetting::get('puter_auto_login', '0') ?? '0') === '1';
$puterLoginHint = (string) (SystemSetting::get('puter_login_hint', '') ?? '');
$puterPromptMode = (string) (SystemSetting::get('puter_prompt_mode', 'login') ?? 'login');

// Traducción de estados
$statusLabels = [
    'pending' => 'Pendiente',
    'analyzing' => 'Analizando',
    'completed' => 'Completado',
    'failed' => 'Fallido',
    'error' => 'Error',
    'PENDIENTE' => 'Pendiente',
    'PROCESANDO' => 'Analizando',
    'COMPLETADO' => 'Completado',
    'ERROR' => 'Error'
];
$statusClasses = [
    'pending' => 'bg-yellow-100 text-yellow-800',
    'analyzing' => 'bg-blue-100 text-blue-800',
    'completed' => 'bg-green-100 text-green-800',
    'failed' => 'bg-red-100 text-red-800',
    'error' => 'bg-red-100 text-red-800',
    'PENDIENTE' => 'bg-yellow-100 text-yellow-800',
    'PROCESANDO' => 'bg-blue-100 text-blue-800',
    'COMPLETADO' => 'bg-green-100 text-green-800',
    'ERROR' => 'bg-red-100 text-red-800'
];
$currentStatus = $program['estado_analisis'] ?? ($program['status'] ?? 'pending');
$statusLabel = $statusLabels[$currentStatus] ?? $currentStatus;
$statusClass = $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-800';
?>
<?php if (!$program): ?>
    <section class="neu-flat p-6">
        <h2 class="text-xl font-bold text-neu-text-main mt-0">Programa no disponible</h2>
        <a class="neu-btn-primary px-4 py-2 rounded-full" href="<?= Router::url('/instructor/programs') ?>">Volver</a>
    </section>
    <?php return; ?>
<?php endif; ?>

<section class="neu-flat p-6">
    <div class="flex justify-between gap-4 flex-wrap items-start">
        <div>
            <h2 class="text-2xl font-bold text-neu-text-main m-0 mb-2"><?= htmlspecialchars((string) ($program['title'] ?? 'Programa')) ?></h2>
            <div class="flex items-center gap-3 flex-wrap">
                <?php if (!empty($program['codigo_programa'])): ?>
                    <span class="text-neu-text-light text-sm">Código: <?= htmlspecialchars((string) $program['codigo_programa']) ?></span>
                <?php endif; ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
                    <?= $statusLabel ?>
                </span>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a class="neu-flat px-4 py-2 rounded-full text-sm" href="<?= Router::url('/instructor/programs') ?>">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
            <?php if (!empty($program['pdf_path'])): ?>
                <a class="neu-flat px-4 py-2 rounded-full text-sm" href="<?= htmlspecialchars((string) $program['pdf_path']) ?>" target="_blank" rel="noopener">
                    <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal de carga -->
<div id="loading-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="neu-flat p-6 rounded-xl text-center max-w-sm">
        <div class="text-4xl mb-4"><i class="fas fa-spinner fa-spin text-sena-green"></i></div>
        <p id="loading-message" class="text-neu-text-main font-semibold">Procesando...</p>
        <p id="loading-submessage" class="text-neu-text-light text-sm mt-2">Esto puede tomar hasta 1 minuto si la IA está ocupada.</p>
        <button id="loading-close" class="neu-flat px-4 py-2 rounded-full text-sm mt-4" type="button" onclick="hideLoadingModal()">
            Seguir trabajando
        </button>
    </div>
</div>

<script>
    const programAnalysis = <?= json_encode($program['analysis'] ?? null, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
    const programId = <?= (int) ($program['id'] ?? 0) ?>;
    const puterSettings = {
        enabled: <?= $puterEnabled ? 'true' : 'false' ?>,
        autoLogin: <?= $puterAutoLogin ? 'true' : 'false' ?>,
        loginHint: <?= json_encode($puterLoginHint, JSON_UNESCAPED_UNICODE) ?>,
        promptMode: <?= json_encode($puterPromptMode, JSON_UNESCAPED_UNICODE) ?>
    };

    function showLoadingModal(message) {
        document.getElementById('loading-message').textContent = message;
        document.getElementById('loading-modal').style.display = 'flex';
    }

    function hideLoadingModal() {
        document.getElementById('loading-modal').style.display = 'none';
    }

    function isPuterLoggedIn() {
        if (!window.puter) return false;
        if (typeof puter.isLoggedIn === 'function') {
            return puter.isLoggedIn();
        }
        if (puter.auth && typeof puter.auth.isSignedIn === 'function') {
            return puter.auth.isSignedIn();
        }
        if (puter.auth && typeof puter.auth.isLoggedIn === 'function') {
            return puter.auth.isLoggedIn();
        }
        return false;
    }

    function ensurePuterMiniModal() {
        let modal = document.getElementById('puter-mini-modal');
        if (modal) return modal;
        modal = document.createElement('div');
        modal.id = 'puter-mini-modal';
        modal.style.cssText = 'display:none; position:fixed; right:16px; bottom:16px; z-index:1060; background:rgba(255,255,255,0.95); border:1px solid #e5e7eb; border-radius:999px; padding:8px 14px; font-size:12px; color:#1f2937; box-shadow:0 6px 18px rgba(0,0,0,0.12);';
        modal.textContent = 'Conectando a Puter...';
        document.body.appendChild(modal);
        return modal;
    }

    function showPuterMiniModal(message) {
        const modal = ensurePuterMiniModal();
        modal.textContent = message || 'Conectando a Puter...';
        modal.style.display = 'block';
    }

    function hidePuterMiniModal() {
        const modal = document.getElementById('puter-mini-modal');
        if (modal) modal.style.display = 'none';
    }

    async function ensurePuterSession() {
        if (!window.puter) return false;
        if (!puterSettings.enabled) return false;
        if (isPuterLoggedIn()) return true;

        try {
            showPuterMiniModal('Conectando a Puter...');
            const options = {
                prompt: 'login',
                screen_hint: 'login',
                auth_type: 'login',
                mode: 'signin',
                login_hint: puterSettings.loginHint || undefined,
                loginHint: puterSettings.loginHint || undefined
            };
            if (puter.auth && typeof puter.auth.signIn === 'function') {
                await puter.auth.signIn(options);
            } else if (typeof puter.signIn === 'function') {
                await puter.signIn(options);
            } else if (puter.auth && typeof puter.auth.login === 'function') {
                await puter.auth.login(options);
            }
        } catch (err) {
            console.warn('Puter login cancelado o fallo:', err);
        } finally {
            hidePuterMiniModal();
        }

        return isPuterLoggedIn();
    }

    async function handleGenerateScenario(event) {
        event.preventDefault();
        if (!programAnalysis) {
            alert('El programa debe ser analizado primero.');
            return;
        }

        if (!puterSettings.enabled) {
            alert('La integracion con Puter esta desactivada en configuracion.');
            return;
        }

        if (!await ensurePuterSession()) {
            alert('Debes iniciar sesión en Puter para generar escenarios. Se abrirá una ventana de inicio.');
            return;
        }

        showLoadingModal('Generando escenario con IA...');

        const focus = document.getElementById('focus').value;
        const prompt = buildScenarioPrompt(programAnalysis, focus);

        try {
            const stream = await puter.ai.chat(
                [{
                    role: 'system',
                    content: 'Responde solo con JSON valido y sin explicaciones.'
                }, {
                    role: 'user',
                    content: prompt
                }], {
                    model: 'gemini-2.5-flash'
                }
            );
            
            const fullResponse = await readStream(stream);
            const jsonResponse = parseJsonSafely(fullResponse);

            // Enviar el escenario generado al backend para guardarlo
            const saveResponse = await fetch(`<?= Router::url('/instructor/programs/') ?>${programId}/save-scenario`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonResponse)
            });

            const saveData = await saveResponse.json();

            if (saveData.ok) {
                window.location.reload();
            } else {
                hideLoadingModal();
                alert('Error guardando el escenario: ' + (saveData.message || 'Error desconocido.'));
            }

        } catch (error) {
            hideLoadingModal();
            console.error('Error generando o guardando el escenario:', error);
            alert('Hubo un error al generar el escenario con Puter.js: ' + error.message);
        }
    }

    async function readStream(stream) {
        if (typeof stream === 'string') {
            return stream;
        }
        if (stream && typeof stream.getReader === 'function') {
            let fullResponse = '';
            const reader = stream.getReader();
            const decoder = new TextDecoder();
            while (true) {
                const { done, value } = await reader.read();
                if (done) break;
                fullResponse += decoder.decode(value, { stream: true });
            }
            return fullResponse;
        }
        if (stream && typeof stream.text === 'function') {
            return stream.text();
        }
        return String(stream ?? '');
    }

    function extractJsonBlock(text) {
        if (!text) return '';
        const cleaned = text.replace(/```json|```/g, '').trim();
        const firstBrace = cleaned.indexOf('{');
        const firstBracket = cleaned.indexOf('[');
        let start = -1;
        if (firstBrace >= 0 && firstBracket >= 0) {
            start = Math.min(firstBrace, firstBracket);
        } else {
            start = Math.max(firstBrace, firstBracket);
        }
        if (start === -1) return '';

        const stack = [];
        let inString = false;
        let escape = false;
        for (let i = start; i < cleaned.length; i += 1) {
            const ch = cleaned[i];
            if (inString) {
                if (escape) {
                    escape = false;
                } else if (ch === '\\') {
                    escape = true;
                } else if (ch === '"') {
                    inString = false;
                }
                continue;
            }
            if (ch === '"') {
                inString = true;
                continue;
            }
            if (ch === '{' || ch === '[') {
                stack.push(ch);
            } else if (ch === '}' || ch === ']') {
                stack.pop();
                if (stack.length === 0) {
                    return cleaned.slice(start, i + 1);
                }
            }
        }
        return cleaned.slice(start);
    }

    function parseJsonSafely(text) {
        if (!text) {
            throw new Error('Respuesta vacia de IA.');
        }
        const cleaned = text.replace(/```json|```/g, '').trim();
        try {
            return JSON.parse(cleaned);
        } catch (primaryError) {
            const extracted = extractJsonBlock(cleaned);
            if (!extracted) {
                throw primaryError;
            }
            try {
                return JSON.parse(extracted);
            } catch (secondaryError) {
                const snippet = extracted.slice(0, 400);
                throw new Error('JSON invalido. Fragmento: ' + snippet);
            }
        }
    }

    function buildScenarioPrompt(analysis, focus) {
        const perfil = analysis.perfil_egresado || '';
        const contextos = Array.isArray(analysis.contextos_laborales) ? analysis.contextos_laborales.join(', ') : '';
        const competencias = Array.isArray(analysis.competencias) ? analysis.competencias.join(', ') : '';
        const focusText = focus && focus.trim() !== '' ? focus.trim() : competencias;

        return `Eres un disenador instruccional experto en gamificacion educativa. Crea un escenario laboral simulado para un aprendiz del SENA con este perfil:

PERFIL: ${perfil}
CONTEXTOS LABORALES: ${contextos}
COMPETENCIAS: ${competencias}
COMPETENCIA A ENFATIZAR: ${focusText}

El escenario debe:
1. Ser realista y relevante al contexto laboral del aprendiz
2. Presentar una situacion problematica que requiera toma de decisiones
3. Tener entre 3 y 5 pasos de decision
4. Cada paso debe tener 3-4 opciones de respuesta
5. Cada opcion debe impactar en: Comunicacion, Liderazgo, Trabajo en Equipo, Toma de Decisiones
6. Incluir retroalimentacion al final

Responde SOLO en JSON con esta estructura:
{
  "title": "Titulo del escenario",
  "description": "Descripcion breve",
  "area": "area formativa",
  "difficulty": "basico|intermedio|avanzado",
  "steps": [
    {
      "id": 0,
      "text": "Situacion",
      "options": [
        {
          "text": "Opcion A",
          "result": 1,
          "feedback": "good|neutral|bad",
          "scores": {
            "Comunicacion": 10,
            "Liderazgo": 5,
            "Trabajo en Equipo": 5,
            "Toma de Decisiones": 10
          }
        }
      ]
    },
    {
      "id": 3,
      "text": "Cierre",
      "feedbackText": "Analisis final",
      "options": []
    }
  ]
}`;
    }

    async function startAsyncAnalysis(event) {
        if (event) {
            event.preventDefault();
        }
        const form = document.getElementById('analyze-form');
        if (!form) return false;

        showLoadingModal('Analizando programa...');
        const statusEl = document.getElementById('async-status');
        if (statusEl) {
            statusEl.textContent = 'Análisis en segundo plano iniciado. Puedes seguir navegando.';
        }

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                credentials: 'same-origin'
            });
            const data = await res.json();
            if (!data || !data.ok) {
                hideLoadingModal();
                alert(data && data.message ? data.message : 'No se pudo iniciar el anÃ¡lisis.');
                return false;
            }
            pollAnalysisStatus(<?= (int) ($program['id'] ?? 0) ?>);
        } catch (err) {
            hideLoadingModal();
            alert('No se pudo iniciar el análisis.');
        }
        return false;
    }

    function pollAnalysisStatus(programId) {
        const statusUrl = '<?= Router::url('/instructor/programs/' . (int) ($program['id'] ?? 0) . '/analysis-status') ?>';
        const interval = setInterval(async () => {
            try {
                const res = await fetch(statusUrl + '?t=' + Date.now(), { credentials: 'same-origin' });
                const data = await res.json();
                if (!data || !data.ok) {
                    return;
                }
                if (data.status === 'COMPLETADO') {
                    clearInterval(interval);
                    hideLoadingModal();
                    window.location.reload();
                }
                if (data.status === 'ERROR') {
                    clearInterval(interval);
                    hideLoadingModal();
                    alert('El análisis terminó con error. Revisa el log.');
                }
            } catch (err) {
                // silenciar y mantener polling
            }
        }, 4000);
    }

    if (puterSettings.autoLogin && puterSettings.enabled) {
        window.addEventListener('load', () => {
            ensurePuterSession();
        });
    }
</script>

<section class="neu-flat p-6 mt-4">
    <h3 class="text-xl font-bold text-neu-text-main mt-0">Datos del Programa</h3>
    <div class="grid gap-3">
        <?php if (!empty($program['competencias_text'])): ?>
            <div>
                <strong>Competencias principales:</strong>
                <div class="text-neu-text-light mt-1 whitespace-pre-line">
                    <?= htmlspecialchars((string) $program['competencias_text']) ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($program['perfil_egreso_text'])): ?>
            <div>
                <strong>Perfil de egreso:</strong>
                <div class="text-neu-text-light mt-1 whitespace-pre-line">
                    <?= htmlspecialchars((string) $program['perfil_egreso_text']) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="neu-flat p-6 mt-4">
    <h3 class="text-xl font-bold text-neu-text-main mt-0">Analisis del Programa</h3>
    <?php $analysis = $program['analysis'] ?? null; ?>
    <?php if (!$analysis): ?>
        <p class="text-neu-text-light">Aun no hay analisis. Pulsa "Analizar programa" para extraer competencias.</p>
    <?php else: ?>
        <div class="grid gap-3">
            <?php $softSkills = $analysis['soft_skills'] ?? []; ?>
            <?php if (!empty($analysis['sector'])): ?>
                <div>
                    <strong>Sector sugerido:</strong>
                    <span class="text-neu-text-light ml-1"><?= htmlspecialchars((string) $analysis['sector']) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($softSkills) && is_array($softSkills)): ?>
                <div>
                    <strong>Habilidades blandas recomendadas:</strong>
                    <div class="grid gap-3 mt-2">
                        <?php foreach ($softSkills as $skill): ?>
                            <div class="neu-flat p-4">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <span class="font-semibold text-neu-text-main">
                                        <?= htmlspecialchars((string) ($skill['nombre'] ?? 'Soft skill')) ?>
                                    </span>
                                    <?php if (isset($skill['peso'])): ?>
                                        <span class="text-sm text-neu-text-light">Peso <?= htmlspecialchars((string) $skill['peso']) ?>%</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($skill['descripcion'])): ?>
                                    <p class="text-neu-text-light text-sm mt-2"><?= htmlspecialchars((string) $skill['descripcion']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($skill['criterios']) && is_array($skill['criterios'])): ?>
                                    <ul class="list-disc list-inside text-neu-text-light text-sm mt-2">
                                        <?php foreach ($skill['criterios'] as $criterio): ?>
                                            <li><?= htmlspecialchars((string) $criterio) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-neu-text-light">No se detectaron soft skills. Ejecuta el analisis nuevamente.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<section class="neu-flat p-6 mt-4">
    <h3 class="text-xl font-bold text-neu-text-main mt-0">Acciones IA</h3>
    <div class="flex gap-4 flex-wrap items-end">
        <?php if (!($program['analysis'] ?? null)): ?>
            <form id="analyze-form" method="post" action="<?= Router::url('/instructor/programs/' . (int) ($program['id'] ?? 0) . '/analyze-async') ?>" class="m-0" onsubmit="return startAsyncAnalysis(event)">
                <button class="neu-btn-primary px-4 py-2 rounded-full" type="submit">
                    <i class="fas fa-brain mr-1"></i> Analizar programa
                </button>
            </form>
        <?php endif; ?>

        <form id="generate-scenario-form" method="post" action="<?= Router::url('/instructor/programs/' . (int) ($program['id'] ?? 0) . '/generate-scenarios') ?>" class="flex gap-2 items-end m-0" onsubmit="handleGenerateScenario(event)">
            <div>
                <label for="focus" class="text-neu-text-light text-sm block mb-1">Competencia foco (opcional)</label>
                <select id="focus" name="focus" class="neu-input">
                    <option value="">Selecciona una habilidad...</option>
                    <?php $analysisSkills = (array) (($program['analysis']['soft_skills'] ?? []) ?: []); ?>
                    <?php foreach ($analysisSkills as $skill): ?>
                        <?php $skillName = (string) ($skill['nombre'] ?? ''); ?>
                        <?php if ($skillName !== ''): ?>
                            <option value="<?= htmlspecialchars($skillName) ?>"><?= htmlspecialchars($skillName) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="neu-flat px-4 py-2 rounded-full" type="submit" <?= !($program['analysis'] ?? null) ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?> title="<?= !($program['analysis'] ?? null) ? 'Analiza el programa primero' : 'Generar escenario' ?>">
                <i class="fas fa-magic mr-1"></i> Generar escenario IA
            </button>
        </form>
    </div>
    <div id="async-status" class="text-sm text-neu-text-light mt-3"></div>
</section>

<section class="neu-flat p-6 mt-4">
    <h3 class="text-xl font-bold text-neu-text-main mt-0">Escenarios IA generados</h3>
    <?php if (empty($scenarios)): ?>
        <p class="text-neu-text-light">Aún no hay escenarios IA para este programa. <?= ($program['analysis'] ?? null) ? 'Genera uno con el botón de arriba.' : 'Primero analiza el programa.' ?></p>
    <?php else: ?>
        <div class="grid gap-3">
            <?php foreach ($scenarios as $s): ?>
                <div class="neu-flat p-4">
                    <div class="flex justify-between gap-3 flex-wrap items-center">
                        <div>
                            <div class="font-bold text-neu-text-main"><?= htmlspecialchars((string) ($s['title'] ?? 'Escenario')) ?></div>
                            <?php if (!empty($s['area']) || !empty($s['difficulty'])): ?>
                                <div class="text-sm text-neu-text-light mt-1">
                                    <?php if (!empty($s['area'])): ?><span class="mr-3"><i class="fas fa-tag mr-1"></i><?= htmlspecialchars($s['area']) ?></span><?php endif; ?>
                                    <?php if (!empty($s['difficulty'])): ?><span><i class="fas fa-signal mr-1"></i><?= htmlspecialchars($s['difficulty']) ?></span><?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a class="neu-btn-primary px-4 py-2 rounded-full text-sm" href="<?= Router::url('/scenarios/' . (int) ($s['id'] ?? 0)) ?>">
                            <i class="fas fa-play mr-1"></i> Abrir
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
