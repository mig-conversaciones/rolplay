<?php

declare(strict_types=1);

use App\Core\Router;
use App\Models\SystemSetting;

$errors = $errors ?? [];
$old = $old ?? [];
$puterEnabled = (SystemSetting::get('puter_enabled', '1') ?? '1') === '1';
$puterAutoLogin = (SystemSetting::get('puter_auto_login', '0') ?? '0') === '1';
$puterLoginHint = (string) (SystemSetting::get('puter_login_hint', '') ?? '');
$puterPromptMode = (string) (SystemSetting::get('puter_prompt_mode', 'login') ?? 'login');
$puterHasHint = $puterLoginHint !== '';
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-clipboard-list text-sena-green mr-2"></i>
                Cargar Programa
            </h1>
            <p class="text-neu-text-light">
                Registra las competencias principales y el perfil de egreso para generar escenarios automaticamente
            </p>
        </div>
        <a href="<?= Router::url('/instructor/programs') ?>" class="neu-flat px-4 py-2 rounded-full text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
</div>

<!-- Formulario -->
<div class="neu-flat p-6 max-w-3xl">
    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Por favor corrige los siguientes errores:</strong>
            </div>
            <ul class="list-disc list-inside text-sm">
                <?php foreach ($errors as $fieldErrors): ?>
                    <?php foreach ($fieldErrors as $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= Router::url('/instructor/programs') ?>" class="space-y-6" enctype="multipart/form-data">
        <!-- PDF fuente -->
        <div>
            <label for="program_pdf" class="block text-sm font-semibold text-neu-text-main mb-2">
                PDF del programa <span class="text-red-500">*</span>
            </label>
            <input
                id="program_pdf"
                name="program_pdf"
                type="file"
                accept="application/pdf"
                required
                class="neu-input w-full"
            >
            <p class="text-xs text-neu-text-light mt-2">
                Sube el PDF oficial del programa. Se analizara con IA para extraer competencias y perfil.
            </p>
            <div class="flex flex-wrap items-center gap-3 mt-3">
                <button type="button" id="analyze-pdf-btn" class="neu-btn-primary px-5 py-2 rounded-full">
                    <i class="fas fa-file-pdf mr-2"></i> Analizar PDF con IA
                </button>
                <button type="button" id="puter-login-btn" class="neu-flat px-4 py-2 rounded-full text-sm">
                    <i class="fas fa-user-check mr-2"></i> Iniciar sesion con Puter
                </button>
                <span id="pdf-analysis-status" class="text-sm text-neu-text-light"></span>
            </div>
            <p class="text-xs text-neu-text-light mt-2">
                Puedes guardar el usuario en configuracion (Admin &gt; Settings &gt; Integracion con Puter) para facilitar el acceso.
            </p>
        </div>
        <!-- Titulo -->
        <div>
            <label for="title" class="block text-sm font-semibold text-neu-text-main mb-2">
                Titulo del programa <span class="text-red-500">*</span>
            </label>
            <input
                id="title"
                name="title"
                type="text"
                required
                class="neu-input w-full"
                placeholder="Ej: Tecnologia en Desarrollo de Software"
                value="<?= htmlspecialchars((string) ($old['title'] ?? '')) ?>"
            >
        </div>

        <!-- Codigo del programa -->
        <div>
            <label for="codigo_programa" class="block text-sm font-semibold text-neu-text-main mb-2">
                Codigo del programa
                <span class="font-normal text-neu-text-light">(opcional)</span>
            </label>
            <input
                id="codigo_programa"
                name="codigo_programa"
                type="text"
                class="neu-input w-full"
                placeholder="Ej: 228106"
                value="<?= htmlspecialchars((string) ($old['codigo_programa'] ?? '')) ?>"
            >
        </div>

        <!-- Competencias principales -->
        <div>
            <label for="competencias" class="block text-sm font-semibold text-neu-text-main mb-2">
                Competencias principales <span class="text-red-500">*</span>
            </label>
            <textarea
                id="competencias"
                name="competencias"
                required
                rows="5"
                class="neu-input w-full"
                placeholder="Enumera las competencias principales del programa..."
            ><?= htmlspecialchars((string) ($old['competencias'] ?? '')) ?></textarea>
        </div>

        <!-- Perfil de egreso -->
        <div>
            <label for="perfil_egreso" class="block text-sm font-semibold text-neu-text-main mb-2">
                Perfil de egreso <span class="text-red-500">*</span>
            </label>
            <textarea
                id="perfil_egreso"
                name="perfil_egreso"
                required
                rows="5"
                class="neu-input w-full"
                placeholder="Describe el perfil de egreso del programa..."
            ><?= htmlspecialchars((string) ($old['perfil_egreso'] ?? '')) ?></textarea>
        </div>

        <input type="hidden" id="analysis_json" name="analysis_json" value="<?= htmlspecialchars((string) ($old['analysis_json'] ?? '')) ?>">
        <input type="hidden" id="analysis_source" name="analysis_source" value="<?= htmlspecialchars((string) ($old['analysis_source'] ?? '')) ?>">

        <!-- Info adicional -->
        <div class="neu-flat p-4 rounded-xl bg-blue-50 border border-blue-200">
            <div class="flex gap-3">
                <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Consejo</p>
                    <p>
                        Escribe competencias claras y un perfil de egreso conciso.
                        El sistema analizara esa informacion para generar escenarios de roleplay.
                    </p>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-3 pt-4">
            <button type="submit" id="submit-program-btn" class="neu-btn-primary px-6 py-3 rounded-full opacity-60" disabled>
                <i class="fas fa-upload mr-2"></i> Guardar Programa
            </button>
            <a href="<?= Router::url('/instructor/programs') ?>" class="neu-flat px-6 py-3 rounded-full">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" crossorigin="anonymous"></script>
<script>
    const pdfInput = document.getElementById('program_pdf');
    const analyzeBtn = document.getElementById('analyze-pdf-btn');
    const statusEl = document.getElementById('pdf-analysis-status');
    const titleInput = document.getElementById('title');
    const codigoInput = document.getElementById('codigo_programa');
    const competenciasInput = document.getElementById('competencias');
    const perfilInput = document.getElementById('perfil_egreso');
    const analysisInput = document.getElementById('analysis_json');
    const analysisSourceInput = document.getElementById('analysis_source');
    const submitBtn = document.getElementById('submit-program-btn');
    const puterLoginBtn = document.getElementById('puter-login-btn');
    const puterMiniModalId = 'puter-mini-modal';

    function setStatus(message, type) {
        if (!statusEl) return;
        statusEl.textContent = message || '';
        statusEl.className = 'text-sm ' + (type === 'error' ? 'text-red-600' : 'text-neu-text-light');
    }

    function ensurePuterMiniModal() {
        let modal = document.getElementById(puterMiniModalId);
        if (modal) return modal;
        modal = document.createElement('div');
        modal.id = puterMiniModalId;
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
        const modal = document.getElementById(puterMiniModalId);
        if (modal) modal.style.display = 'none';
    }

    function clampPromptText(text, maxChars) {
        if (!text) return '';
        if (text.length <= maxChars) return text;
        const head = text.slice(0, Math.floor(maxChars * 0.6));
        const tail = text.slice(-Math.floor(maxChars * 0.3));
        return `${head}\n...\n${tail}`;
    }

    async function extractPdfText(file) {
        if (!window.pdfjsLib) {
            throw new Error('PDF.js no esta disponible.');
        }

        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const arrayBuffer = await file.arrayBuffer();
        const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
        let fullText = '';

        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum += 1) {
            const page = await pdf.getPage(pageNum);
            const content = await page.getTextContent();
            const strings = content.items.map(item => item.str);
            fullText += strings.join(' ') + '\n';
            setStatus(`Leyendo PDF (${pageNum}/${pdf.numPages})...`);
        }

        return fullText.trim();
    }

    function buildAnalysisPrompt(pdfText) {
        return `Analiza el siguiente programa de formacion del SENA y extrae:
1. nombre
2. nivel
3. perfil_egresado (resumen 2-3 lineas)
4. competencias (lista)
5. resultados_aprendizaje (max 5)
6. contextos_laborales (lista)
7. sector (tecnologia, salud, comercio, industrial, agropecuario, servicios, construccion, turismo, gastronomia, educacion, logistica, otro)
8. soft_skills (5 habilidades blandas con nombre, peso, criterios y descripcion)

Responde SOLO en JSON con esta estructura:
{
  "nombre": "",
  "nivel": "",
  "perfil_egresado": "",
  "competencias": [],
  "resultados_aprendizaje": [],
  "contextos_laborales": [],
  "codigo_programa": "",
  "sector": "",
  "soft_skills": [
    {
      "nombre": "",
      "peso": 25,
      "criterios": [],
      "descripcion": ""
    }
  ]
}

CONTENIDO DEL PDF:
${pdfText}`;
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

    function isAllCaps(text) {
        if (!text) return false;
        const letters = text.match(/[A-ZÁÉÍÓÚÜÑ]/g);
        const lower = text.match(/[a-záéíóúüñ]/g);
        if (!letters) return false;
        if (lower && lower.length > 2) return false;
        const ratio = letters.length / Math.max(1, text.replace(/[^A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/g, '').length);
        return ratio >= 0.8;
    }

    function normalizeTextCase(text) {
        const value = String(text ?? '').trim();
        if (value === '') return '';
        if (!isAllCaps(value)) return value;
        const lower = value.toLowerCase();
        return lower.charAt(0).toUpperCase() + lower.slice(1);
    }

    function normalizeList(list) {
        if (!Array.isArray(list)) return [];
        return list.map(item => normalizeTextCase(item)).filter(item => item !== '');
    }

    function normalizeAnalysis(analysis) {
        const normalized = {
            nombre: normalizeTextCase(analysis.nombre || ''),
            codigo_programa: analysis.codigo_programa || '',
            nivel: normalizeTextCase(analysis.nivel || ''),
            perfil_egresado: normalizeTextCase(analysis.perfil_egresado || ''),
            competencias: normalizeList(analysis.competencias),
            resultados_aprendizaje: normalizeList(analysis.resultados_aprendizaje),
            contextos_laborales: normalizeList(analysis.contextos_laborales),
            sector: normalizeTextCase(analysis.sector || ''),
            soft_skills: Array.isArray(analysis.soft_skills)
                ? analysis.soft_skills.map(skill => ({
                    nombre: normalizeTextCase(skill?.nombre || skill?.name || ''),
                    peso: skill?.peso ?? skill?.weight ?? 20,
                    criterios: normalizeList(skill?.criterios || skill?.criteria),
                    descripcion: normalizeTextCase(skill?.descripcion || skill?.description || '')
                }))
                : [],
            _meta: {
                source: 'puter',
                model: 'gemini-2.5-flash',
                generated_at: new Date().toISOString()
            }
        };

        return normalized;
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

    const puterSettings = {
        enabled: <?= $puterEnabled ? 'true' : 'false' ?>,
        autoLogin: <?= $puterAutoLogin ? 'true' : 'false' ?>,
        loginHint: <?= json_encode($puterLoginHint, JSON_UNESCAPED_UNICODE) ?>,
        promptMode: <?= json_encode($puterPromptMode, JSON_UNESCAPED_UNICODE) ?>
    };

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

    async function analyzePdfWithPuter() {
        if (!pdfInput || !pdfInput.files || !pdfInput.files[0]) {
            setStatus('Selecciona un PDF primero.', 'error');
            return;
        }

        if (!window.puter) {
            setStatus('Puter.js no esta disponible. Recarga la pagina.', 'error');
            return;
        }

        if (!puterSettings.enabled) {
            setStatus('La integracion con Puter esta desactivada en configuracion.', 'error');
            return;
        }

        if (!await ensurePuterSession()) {
            setStatus('Debes iniciar sesion en Puter para analizar. Se abrira una ventana de inicio.', 'error');
            return;
        }

        try {
            setStatus('Extrayendo texto del PDF...');
            const rawText = await extractPdfText(pdfInput.files[0]);
            const clippedText = clampPromptText(rawText, 20000);

            setStatus('Analizando con IA...');
            const prompt = buildAnalysisPrompt(clippedText);

            const stream = await puter.ai.chat([
                { role: 'system', content: 'Responde solo con JSON valido, sin comentarios, sin comas finales y sin explicaciones.' },
                { role: 'user', content: prompt }
            ], { model: 'gemini-2.5-flash' });

            const rawResponse = await readStream(stream);
            const parsed = parseJsonSafely(rawResponse);
            const analysis = normalizeAnalysis(parsed);

            if (analysis.nombre && !titleInput.value.trim()) {
                titleInput.value = analysis.nombre;
            }
            if (analysis.codigo_programa && !codigoInput.value.trim()) {
                codigoInput.value = analysis.codigo_programa;
            }
            if (analysis.competencias.length && !competenciasInput.value.trim()) {
                competenciasInput.value = analysis.competencias.join('\n');
            }
            if (analysis.perfil_egresado && !perfilInput.value.trim()) {
                perfilInput.value = analysis.perfil_egresado;
            }

            analysisInput.value = JSON.stringify(analysis);
            analysisSourceInput.value = 'puter';
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-60');
            }
            setStatus('Analisis listo. Los datos fueron cargados desde la IA.');
        } catch (error) {
            console.error(error);
            setStatus('Error analizando PDF: ' + error.message, 'error');
        }
    }

    function lockManualFields() {
        [titleInput, codigoInput, competenciasInput, perfilInput].forEach(el => {
            if (!el) return;
            el.readOnly = true;
            el.setAttribute('aria-readonly', 'true');
            el.classList.add('opacity-60');
        });
    }

    function unlockManualFields() {
        [titleInput, codigoInput, competenciasInput, perfilInput].forEach(el => {
            if (!el) return;
            el.readOnly = false;
            el.removeAttribute('aria-readonly');
            el.classList.remove('opacity-60');
        });
    }

    lockManualFields();
    if (submitBtn) {
        const hasAnalysis = analysisInput && analysisInput.value.trim() !== '';
        submitBtn.disabled = !hasAnalysis;
        if (hasAnalysis) {
            submitBtn.classList.remove('opacity-60');
        } else {
            submitBtn.classList.add('opacity-60');
        }
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

    if (analyzeBtn) {
        analyzeBtn.addEventListener('click', analyzePdfWithPuter);
    }

    if (puterLoginBtn) {
        puterLoginBtn.addEventListener('click', async () => {
            if (!puterSettings.enabled) {
                setStatus('La integracion con Puter esta desactivada en configuracion.', 'error');
                return;
            }
            const ok = await ensurePuterSession();
            if (ok) {
                setStatus('Sesion de Puter activa.', 'ok');
            } else {
                setStatus('No se pudo iniciar sesion en Puter.', 'error');
            }
        });
    }

    if (puterSettings.autoLogin && puterSettings.enabled) {
        window.addEventListener('load', () => {
            ensurePuterSession();
        });
    }
</script>
