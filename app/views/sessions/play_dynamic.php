<?php

declare(strict_types=1);

use App\Core\Router;

$session = $session ?? [];
$currentStage = $currentStage ?? 1;
$stageContent = $stageContent ?? [];
$user = $user ?? [];
$previousDecisions = $previousDecisions ?? [];

$sessionId = (int)($session['id'] ?? 0);
?>

<style>
.stage-indicator {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 32px;
}

.stage-indicator .stage {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #6b7280;
    position: relative;
}

.stage-indicator .stage.completed {
    background: #39A900;
    color: white;
}

.stage-indicator .stage.active {
    background: #39A900;
    color: white;
    box-shadow: 0 0 0 4px rgba(57, 169, 0, 0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 4px rgba(57, 169, 0, 0.2); }
    50% { box-shadow: 0 0 0 8px rgba(57, 169, 0, 0.1); }
}

.stage-indicator .stage::after {
    content: '';
    position: absolute;
    right: -12px;
    width: 12px;
    height: 2px;
    background: #e5e7eb;
}

.stage-indicator .stage:last-child::after {
    display: none;
}

.option-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    position: relative;
}

.option-card:hover {
    border-color: #39A900;
    box-shadow: 0 4px 12px rgba(57, 169, 0, 0.15);
    transform: translateY(-2px);
}

.option-card.selected {
    border-color: #39A900;
    background: #f0fdf4;
}

.option-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.option-label {
    display: flex;
    align-items: start;
    gap: 12px;
}

.option-number {
    width: 32px;
    height: 32px;
    background: #e5e7eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #4b5563;
    flex-shrink: 0;
}

.option-card.selected .option-number {
    background: #39A900;
    color: white;
}

.submit-btn {
    background: #39A900;
    color: white;
    padding: 14px 32px;
    border-radius: 8px;
    border: none;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.submit-btn:hover:not(:disabled) {
    background: #007832;
    box-shadow: 0 4px 12px rgba(57, 169, 0, 0.3);
}

.submit-btn:disabled {
    background: #9ca3af;
    cursor: not-allowed;
}

#loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

#loading-overlay.show {
    display: flex;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid rgba(255, 255, 255, 0.3);
    border-top-color: #39A900;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.context-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-left: 4px solid #39A900;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.previous-decisions {
    background: #f9fafb;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
}
</style>

<!-- Loading overlay -->
<div id="loading-overlay">
    <div style="text-align:center;">
        <div class="spinner"></div>
        <p style="color:white; margin-top:16px; font-size:1.1rem;">Generando siguiente etapa...</p>
    </div>
</div>

<!-- Indicador de etapas -->
<div class="stage-indicator">
    <div class="stage <?= $currentStage >= 1 ? ($currentStage == 1 ? 'active' : 'completed') : '' ?>">
        <?= $currentStage > 1 ? '✓' : '1' ?>
    </div>
    <div class="stage <?= $currentStage >= 2 ? ($currentStage == 2 ? 'active' : 'completed') : '' ?>">
        <?= $currentStage > 2 ? '✓' : '2' ?>
    </div>
    <div class="stage <?= $currentStage >= 3 ? 'active' : '' ?>">
        3
    </div>
</div>

<!-- Título principal -->
<section class="card">
    <h1 style="margin:0 0 12px 0; color:#1f2937; font-size:2rem;">
        <?= htmlspecialchars($stageContent['title'] ?? 'Escenario Dinámico') ?>
    </h1>
    <div style="color:#6b7280; font-size:0.95rem;">
        Etapa <?= $currentStage ?> de 3 • Sesión #<?= $sessionId ?>
    </div>
</section>

<!-- Decisiones previas (si existen) -->
<?php if (!empty($previousDecisions) && $currentStage > 1): ?>
<section class="previous-decisions">
    <h3 style="margin:0 0 12px 0; color:#4b5563; font-size:1.1rem;">
        📝 Tus decisiones anteriores:
    </h3>
    <?php foreach ($previousDecisions as $decision): ?>
        <div style="padding:8px 0; color:#6b7280;">
            <strong>Etapa <?= (int)$decision['stage'] ?>:</strong> Elegiste la opción <?= chr(65 + (int)$decision['option_chosen']) ?>
        </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>

<!-- Contexto del escenario -->
<?php if ($currentStage === 1): ?>
    <!-- Etapa 1: Mostrar contexto y situación -->
    <section class="context-box">
        <h2 style="margin:0 0 16px 0; color:#065f46; font-size:1.3rem;">
            📚 Contexto del Escenario
        </h2>
        <div style="color:#065f46; line-height:1.7; margin-bottom:16px;">
            <?= nl2br(htmlspecialchars($stageContent['context'] ?? '')) ?>
        </div>
        <h3 style="margin:16px 0 12px 0; color:#065f46; font-size:1.1rem;">
            Situación:
        </h3>
        <div style="color:#065f46; line-height:1.7;">
            <?= nl2br(htmlspecialchars($stageContent['situation'] ?? '')) ?>
        </div>
    </section>

<?php elseif ($currentStage === 2): ?>
    <!-- Etapa 2: Mostrar consecuencia y nueva situación -->
    <section class="context-box">
        <h2 style="margin:0 0 16px 0; color:#065f46; font-size:1.3rem;">
            🔄 Consecuencias de tu decisión
        </h2>
        <div style="color:#065f46; line-height:1.7; margin-bottom:16px;">
            <?= nl2br(htmlspecialchars($stageContent['consequence'] ?? '')) ?>
        </div>
        <h3 style="margin:16px 0 12px 0; color:#065f46; font-size:1.1rem;">
            Nueva Situación:
        </h3>
        <div style="color:#065f46; line-height:1.7;">
            <?= nl2br(htmlspecialchars($stageContent['new_situation'] ?? '')) ?>
        </div>
    </section>

<?php else: ?>
    <!-- Etapa 3: Mostrar resolución y situación final -->
    <section class="context-box">
        <h2 style="margin:0 0 16px 0; color:#065f46; font-size:1.3rem;">
            🏁 Desenlace Final
        </h2>
        <div style="color:#065f46; line-height:1.7; margin-bottom:16px;">
            <?= nl2br(htmlspecialchars($stageContent['resolution'] ?? '')) ?>
        </div>
        <h3 style="margin:16px 0 12px 0; color:#065f46; font-size:1.1rem;">
            Situación Final:
        </h3>
        <div style="color:#065f46; line-height:1.7;">
            <?= nl2br(htmlspecialchars($stageContent['final_situation'] ?? '')) ?>
        </div>
    </section>
<?php endif; ?>

<!-- Pregunta -->
<section class="card" style="margin-top:24px;">
    <h2 style="margin:0 0 24px 0; color:#1f2937; font-size:1.5rem;">
        <?= htmlspecialchars($stageContent['question'] ?? '¿Qué decides hacer?') ?>
    </h2>

    <!-- Opciones -->
    <form id="decision-form" style="display:grid; gap:16px;">
        <?php
        $options = $stageContent['options'] ?? [];
        foreach ($options as $index => $option):
            $optionLetter = chr(65 + $index); // A, B, C
        ?>
            <label class="option-card" data-index="<?= $index ?>">
                <input type="radio" name="option" value="<?= $index ?>" required>
                <div class="option-label">
                    <div class="option-number"><?= $optionLetter ?></div>
                    <div style="flex:1;">
                        <div style="color:#1f2937; line-height:1.6; font-size:1.05rem;">
                            <?= htmlspecialchars($option['text'] ?? '') ?>
                        </div>
                    </div>
                </div>
            </label>
        <?php endforeach; ?>
    </form>

    <!-- Botón de enviar -->
    <div style="margin-top:32px; text-align:center;">
        <button type="button" id="submit-btn" class="submit-btn" disabled>
            <span>Confirmar Decisión</span>
            <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</section>

<script>
// Manejar selección de opciones
const optionCards = document.querySelectorAll('.option-card');
const submitBtn = document.getElementById('submit-btn');
const form = document.getElementById('decision-form');

optionCards.forEach(card => {
    card.addEventListener('click', function() {
        // Remover selección previa
        optionCards.forEach(c => c.classList.remove('selected'));

        // Seleccionar esta opción
        this.classList.add('selected');
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;

        // Habilitar botón
        submitBtn.disabled = false;
    });
});

// Enviar decisión
submitBtn.addEventListener('click', async function() {
    const selectedOption = form.querySelector('input[name="option"]:checked');

    if (!selectedOption) {
        alert('Por favor selecciona una opción');
        return;
    }

    const optionValue = selectedOption.value;

    // Mostrar loading
    document.getElementById('loading-overlay').classList.add('show');
    submitBtn.disabled = true;

    try {
        const response = await fetch('<?= Router::url('/sessions/' . $sessionId . '/submit-decision') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'option=' + encodeURIComponent(optionValue)
        });

        const data = await response.json();

        if (data.success) {
            if (data.completed) {
                // Redirigir a resultados
                window.location.href = '<?= Router::url('/sessions/' . $sessionId . '/results') ?>';
            } else {
                // Recargar para mostrar siguiente etapa
                window.location.reload();
            }
        } else {
            alert('Error: ' + (data.message || 'No se pudo procesar la decisión'));
            document.getElementById('loading-overlay').classList.remove('show');
            submitBtn.disabled = false;
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión. Por favor intenta de nuevo.');
        document.getElementById('loading-overlay').classList.remove('show');
        submitBtn.disabled = false;
    }
});
</script>
