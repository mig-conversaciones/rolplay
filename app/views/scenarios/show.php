<?php

declare(strict_types=1);

use App\Core\Router;
use App\Models\SystemSetting;

$scenario = $scenario ?? null;
$steps = $scenario['steps'] ?? [];
$puterEnabled = (SystemSetting::get('puter_enabled', '1') ?? '1') === '1';
$puterAutoLogin = (SystemSetting::get('puter_auto_login', '0') ?? '0') === '1';
$puterLoginHint = (string) (SystemSetting::get('puter_login_hint', '') ?? '');
$puterPromptMode = (string) (SystemSetting::get('puter_prompt_mode', 'login') ?? 'login');
?>
<?php if (!$scenario): ?>
    <section class="card">
        <h2 style="margin-top:0;">Escenario no disponible</h2>
        <a class="btn btn-primary" href="<?= Router::url('/scenarios') ?>">Volver a escenarios</a>
    </section>
    <?php return; ?>
<?php endif; ?>

<section class="neu-flat p-6">
    <div class="flex justify-between gap-4 flex-wrap items-start">
        <div>
            <h2 class="text-2xl font-bold text-neu-text-main m-0 mb-2"><?= htmlspecialchars($scenario['title']) ?></h2>
            <p class="text-neu-text-light m-0"><?= htmlspecialchars($scenario['description']) ?></p>
        </div>
        <div class="text-sm text-neu-text-light min-w-[200px]">
            <div><strong>Area:</strong> <?= htmlspecialchars($scenario['area']) ?></div>
            <div><strong>Dificultad:</strong> <?= htmlspecialchars($scenario['difficulty']) ?></div>
            <div><strong>Duracion:</strong> <?= (int) ($scenario['estimated_duration'] ?? 15) ?> min</div>
        </div>
    </div>
    <div class="mt-4 flex gap-2 flex-wrap">
        <a class="neu-flat px-4 py-2 rounded-full" href="<?= Router::url('/scenarios') ?>">Lista</a>
        <a class="neu-flat px-4 py-2 rounded-full" href="<?= Router::homeUrl() ?>"><i class="fas fa-home mr-1"></i>Inicio</a>
    </div>
</section>

<section class="neu-flat p-6 mt-4">
    <h3 class="text-xl font-bold text-neu-text-main mt-0">Simulacion (base)</h3>
    <p class="text-neu-text-light">Simulacion interactiva: avanza paso a paso y guarda decisiones en la base de datos.</p>
</section>

<?php if (empty($steps)): ?>
    <section class="neu-flat p-6 mt-4">
        <h3 class="text-xl font-bold text-neu-text-main mt-0">Sin pasos</h3>
        <p class="text-neu-text-light">El escenario no tiene pasos definidos en <code>steps_json</code>.</p>
    </section>
<?php else: ?>
    <?php
    $playableSteps = array_values(array_filter($steps, static function ($s): bool {
        $options = $s['options'] ?? [];
        return is_array($options) && count($options) > 0;
    }));
    $finalSteps = array_values(array_filter($steps, static function ($s): bool {
        $options = $s['options'] ?? [];
        return !is_array($options) || count($options) === 0;
    }));
    $totalPlayable = count($playableSteps);
    ?>

    <section class="neu-flat p-6 mt-4">
        <div class="flex justify-between gap-2 items-center flex-wrap">
            <h3 class="text-xl font-bold text-neu-text-main m-0">Simulacion</h3>
        <div class="text-neu-text-light text-sm">
            Progreso: <span id="rp-progress">0</span>% - Decisiones: <span id="rp-decisions">0</span>/<?= (int) $totalPlayable ?>
        </div>
        </div>
        <div id="rp-scoreboard" class="text-neu-text-light mt-2 text-sm"></div>
    </section>

    <section class="neu-flat p-6 mt-4">
        <div class="flex justify-between gap-2 items-center">
            <h3 id="rp-step-title" class="text-xl font-bold text-neu-text-main m-0">Paso 1</h3>
            <span id="rp-step-id" class="text-neu-text-light text-sm"></span>
        </div>
        <p id="rp-step-text" class="mt-2"></p>
        <div id="rp-options" class="grid gap-2 mt-2"></div>
        <div id="rp-feedback" class="mt-2"></div>
        <div id="rp-summary" class="mt-4"></div>
        <div class="mt-4 flex gap-2 flex-wrap">
            <button id="rp-next" class="neu-btn-primary px-4 py-2 rounded-full" type="button" disabled>Siguiente</button>
            <a class="neu-flat px-4 py-2 rounded-full" href="<?= Router::url('/scenarios') ?>">Salir</a>
        </div>
    </section>

    <div id="rp-summary-modal" style="display:none; position:fixed; inset:0; z-index:1050; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
        <div class="neu-flat p-6" style="max-width:520px; width:100%;">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-lg font-semibold text-neu-text-main m-0">Resumen del escenario</h4>
                <button id="rp-summary-close" class="neu-flat px-3 py-1 rounded-full text-sm" type="button">Cerrar</button>
            </div>
            <div id="rp-summary-content" class="text-neu-text-light text-sm"></div>
            <div class="mt-4 flex justify-end">
                <a class="neu-btn-primary px-4 py-2 rounded-full text-sm" href="<?= Router::url('/scenarios') ?>">Volver a escenarios</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const scenarioId = <?= (int) ($scenario['id'] ?? 0) ?>;
            const steps = <?= json_encode(array_values($steps), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
            const rpStepTitle = document.getElementById('rp-step-title');
            const rpStepId = document.getElementById('rp-step-id');
            const rpStepText = document.getElementById('rp-step-text');
            const rpOptions = document.getElementById('rp-options');
            const rpFeedback = document.getElementById('rp-feedback');
            const rpNext = document.getElementById('rp-next');
            const rpProgress = document.getElementById('rp-progress');
            const rpDecisions = document.getElementById('rp-decisions');
            const rpScoreboard = document.getElementById('rp-scoreboard');
            const puterSettings = {
                enabled: <?= $puterEnabled ? 'true' : 'false' ?>,
                autoLogin: <?= $puterAutoLogin ? 'true' : 'false' ?>,
                loginHint: <?= json_encode($puterLoginHint, JSON_UNESCAPED_UNICODE) ?>,
                promptMode: <?= json_encode($puterPromptMode, JSON_UNESCAPED_UNICODE) ?>
            };

            let currentIndex = 0;
            let decisionsCount = 0;
            let currentScores = {};
            let sessionId = null;
            let chosenOptionIndex = null;
            let totalPlayable = steps.filter(step => Array.isArray(step.options) && step.options.length > 0).length;
            let generatingNext = false;

            function isPuterLoggedIn() {
                if (!window.puter) return false;
                if (typeof puter.isLoggedIn === 'function') return puter.isLoggedIn();
                if (puter.auth && typeof puter.auth.isSignedIn === 'function') return puter.auth.isSignedIn();
                if (puter.auth && typeof puter.auth.isLoggedIn === 'function') return puter.auth.isLoggedIn();
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

            function setProgress() {
                const progress = totalPlayable > 0 ? Math.min(100, Math.round((decisionsCount / totalPlayable) * 100)) : 0;
                rpProgress.textContent = progress;
                rpDecisions.textContent = decisionsCount;
            }

            function updateScoreboard() {
                const entries = Object.entries(currentScores);
                if (!entries.length) {
                    rpScoreboard.textContent = '';
                    return;
                }
                rpScoreboard.textContent = entries.map(([key, value]) => {
                    const score = Number(value || 0);
                    const tag = score < 0 ? 'Mejora' : score > 0 ? 'Favorable' : 'Neutral';
                    return `${key}: ${score} (${tag})`;
                }).join(' - ');
            }

            function optionButton(opt, optIndex) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'neu-flat p-4 text-left rounded-lg';
                btn.innerHTML = `<strong>Opcion ${String.fromCharCode(65 + optIndex)}</strong><div class="mt-1">${opt.text || ''}</div>`;
                btn.addEventListener('click', () => onChooseOption(opt, optIndex, btn));
                return btn;
            }

            function feedbackBox(text, kind) {
                const div = document.createElement('div');
                const palette = kind === 'good'
                    ? 'bg-green-100 border-green-200 text-green-800'
                    : kind === 'bad'
                        ? 'bg-red-100 border-red-200 text-red-800'
                        : 'bg-blue-100 border-blue-200 text-blue-800';
                div.className = `p-4 rounded-lg ${palette}`;
                const labelMap = {
                    good: 'Bueno',
                    bad: 'Malo',
                    neutral: 'Neutral',
                    god: 'Bueno'
                };
                const normalized = String(text || '').trim();
                const lower = normalized.toLowerCase();
                div.textContent = labelMap[lower] ?? normalized;
                return div;
            }

            function renderStep(step, index) {
                rpStepTitle.textContent = `Paso ${index + 1}`;
                rpStepId.textContent = step.id !== undefined ? `#${step.id}` : '';
                rpStepText.textContent = step.text || step.situation || step.context || '';
                rpOptions.innerHTML = '';
                rpFeedback.innerHTML = '';
                const summary = document.getElementById('rp-summary');
                if (summary) summary.innerHTML = '';
                chosenOptionIndex = null;
                rpNext.disabled = true;

                const options = Array.isArray(step.options) ? step.options : [];
                if (!options.length) {
                    if (step.feedbackText) {
                        rpFeedback.appendChild(feedbackBox(step.feedbackText, 'neutral'));
                    }
                    rpNext.disabled = true;
                    if (index >= steps.length - 1) {
                        showSummaryModal();
                    }
                    return;
                }

                options.forEach((opt, idx) => {
                    rpOptions.appendChild(optionButton(opt, idx));
                });
            }

            function recordDecision(step, optionIndex, optionData) {
                if (!sessionId) return;
                const payload = new URLSearchParams();
                payload.set('session_id', String(sessionId));
                payload.set('step_number', String(currentIndex + 1));
                payload.set('option_chosen', String(optionIndex));
                payload.set('feedback_type', String(optionData.feedback || 'neutral'));
                payload.set('scores_impact', JSON.stringify(optionData.scores || {}));
                payload.set('total_steps', String(totalPlayable));
                payload.set('decisions_count', String(decisionsCount));

                fetch('<?= Router::url('/api/decisions') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: payload.toString()
                }).catch(() => {
                    // no-op
                });
            }

            function applyScores(scores) {
                if (!scores || typeof scores !== 'object') return;
                Object.entries(scores).forEach(([key, value]) => {
                    const current = Number(currentScores[key] || 0);
                    currentScores[key] = current + Number(value || 0);
                });
            }

            function onChooseOption(optionData, optionIndex, button) {
                if (chosenOptionIndex !== null) return;
                chosenOptionIndex = optionIndex;
                const buttons = rpOptions.querySelectorAll('button');
                buttons.forEach(btn => btn.disabled = true);
                button.classList.add('ring-2', 'ring-sena-green');

                decisionsCount += 1;
                applyScores(optionData.scores || {});
                setProgress();
                updateScoreboard();

                const feedbackText = optionData.feedbackText || optionData.feedback_text || optionData.feedback || '';
                if (feedbackText) {
                    rpFeedback.appendChild(feedbackBox(feedbackText, optionData.feedback || 'neutral'));
                }

                recordDecision(steps[currentIndex], optionIndex, optionData);
                rpNext.disabled = false;
            }

            async function startSession() {
                try {
                    const res = await fetch(`<?= Router::url('/api/sessions/start') ?>/${scenarioId}`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (data && data.success && data.data && data.data.session_id) {
                        sessionId = data.data.session_id;
                    }
                } catch (err) {
                    // no-op
                }
            }

            async function readStream(stream) {
                if (typeof stream === 'string') return stream;
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
                if (!text) throw new Error('Respuesta vacia de IA.');
                const cleaned = text.replace(/```json|```/g, '').trim();
                try {
                    return JSON.parse(cleaned);
                } catch (primaryError) {
                    const extracted = extractJsonBlock(cleaned);
                    if (!extracted) throw primaryError;
                    return JSON.parse(extracted);
                }
            }

            function buildNextStepPrompt(history, skillKeys) {
                const base = `Genera el siguiente paso de la simulacion con base en el caso y la decision tomada. Responde SOLO en JSON.\n\nHISTORIAL:\n${history}\n\nEVALUA ESTAS COMPETENCIAS:\n${skillKeys.join(', ')}\n\nResponde con JSON:\n{\n  \"id\": 0,\n  \"text\": \"Situacion siguiente\",\n  \"options\": [\n    {\n      \"text\": \"Opcion A\",\n      \"feedback\": \"good|neutral|bad\",\n      \"scores\": {\n        \"${skillKeys[0] || 'Comunicacion'}\": 5\n      },\n      \"feedbackText\": \"Retroalimentacion breve\"\n    }\n  ]\n}\n\nSi el caso ya debe cerrarse, responde:\n{\n  \"id\": 99,\n  \"text\": \"Cierre\",\n  \"feedbackText\": \"Conclusiones del caso\",\n  \"options\": []\n}`;
                return base;
            }

            function buildHistory() {
                return steps.map((step, idx) => {
                    const chosen = step._chosenOption != null
                        ? step.options?.[step._chosenOption]?.text || ''
                        : '';
                    return `Paso ${idx + 1}: ${step.text || ''}\nDecision: ${chosen}`;
                }).join('\n\n');
            }

            async function generateNextStepWithAI() {
                if (!window.puter) {
                    return null;
                }
                if (!puterSettings.enabled) {
                    return null;
                }
                if (!await ensurePuterSession()) {
                    return null;
                }
                if (generatingNext) return null;
                generatingNext = true;
                const history = buildHistory();
                const sampleScores = steps[currentIndex]?.options?.[chosenOptionIndex]?.scores || {};
                const skillKeys = Object.keys(sampleScores).length ? Object.keys(sampleScores) : ['Comunicacion', 'Liderazgo', 'Trabajo en Equipo', 'Toma de Decisiones'];
                const prompt = buildNextStepPrompt(history, skillKeys);

                try {
                    const stream = await puter.ai.chat([
                        { role: 'system', content: 'Responde solo con JSON valido y sin explicaciones.' },
                        { role: 'user', content: prompt }
                    ], { model: 'gemini-2.5-flash' });
                    const response = await readStream(stream);
                    return parseJsonSafely(response);
                } catch (err) {
                    console.error('Error generando siguiente paso:', err);
                    return null;
                } finally {
                    generatingNext = false;
                }
            }

            async function handleNext() {
                const isLast = currentIndex >= steps.length - 1;
                if (!isLast) {
                    currentIndex += 1;
                    renderStep(steps[currentIndex], currentIndex);
                    return;
                }

                const currentStep = steps[currentIndex];
                if (currentStep && Array.isArray(currentStep.options) && currentStep.options.length > 0) {
                    const nextStep = await generateNextStepWithAI();
                    if (nextStep) {
                        steps.push(nextStep);
                        if (Array.isArray(nextStep.options) && nextStep.options.length > 0) {
                            totalPlayable += 1;
                        }
                        currentIndex += 1;
                        renderStep(nextStep, currentIndex);
                        return;
                    }
                }

                showSummaryModal();
                rpNext.disabled = true;
            }

            function showSummaryModal() {
                const summary = document.getElementById('rp-summary');
                const entries = Object.entries(currentScores);
                const lines = entries.length
                    ? entries.map(([key, value]) => {
                        const score = Number(value || 0);
                        const tag = score < 0 ? 'Desfavorable (mejora requerida)' : score > 0 ? 'Favorable' : 'Neutral';
                        return `<li>${key}: ${score} - ${tag}</li>`;
                    }).join('')
                    : '<li>No hay puntajes registrados.</li>';
                if (summary) {
                    summary.innerHTML = '';
                }
                const modal = document.getElementById('rp-summary-modal');
                const modalContent = document.getElementById('rp-summary-content');
                if (modalContent) {
                    modalContent.innerHTML = `<ul class="list-disc list-inside">${lines}</ul>`;
                }
                if (modal) {
                    modal.style.display = 'flex';
                }
            }

            function init() {
                if (!steps.length) return;
                setProgress();
                updateScoreboard();
                renderStep(steps[currentIndex], currentIndex);
                rpNext.addEventListener('click', handleNext);
                startSession();
                const closeBtn = document.getElementById('rp-summary-close');
                const modal = document.getElementById('rp-summary-modal');
                if (closeBtn && modal) {
                    closeBtn.addEventListener('click', () => {
                        modal.style.display = 'none';
                    });
                    modal.addEventListener('click', (event) => {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                }
                if (puterSettings.autoLogin && puterSettings.enabled) {
                    ensurePuterSession();
                }
            }

            init();
        })();
    </script>
<?php endif; ?>
