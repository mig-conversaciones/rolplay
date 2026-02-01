<?php

declare(strict_types=1);

use App\Core\Router;

$session = $session ?? [];
$decisions = $decisions ?? [];
$softSkills = $softSkills ?? [];
$finalScores = $finalScores ?? [];
$overallFeedback = $overallFeedback ?? '';
$user = $user ?? [];

$sessionId = (int)($session['id'] ?? 0);
$finalScore = (int)($session['final_score'] ?? 0);
?>

<style>
.results-header {
    background: linear-gradient(135deg, #39A900 0%, #007832 100%);
    color: white;
    border-radius: 16px;
    padding: 48px 32px;
    text-align: center;
    margin-bottom: 32px;
    box-shadow: 0 10px 30px rgba(57, 169, 0, 0.3);
}

.score-circle {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.score-value {
    font-size: 3.5rem;
    font-weight: 800;
    color: #39A900;
}

.skill-bar-container {
    background: #f3f4f6;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 16px;
}

.skill-bar {
    background: #e5e7eb;
    border-radius: 8px;
    height: 40px;
    position: relative;
    overflow: hidden;
    margin-top: 12px;
}

.skill-bar-fill {
    height: 100%;
    border-radius: 8px;
    transition: width 1s ease-out;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 12px;
    color: white;
    font-weight: 700;
}

.skill-bar-fill.positive {
    background: linear-gradient(90deg, #39A900 0%, #34d058 100%);
}

.skill-bar-fill.negative {
    background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
}

.skill-bar-fill.neutral {
    background: linear-gradient(90deg, #6b7280 0%, #9ca3af 100%);
}

.feedback-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-left: 4px solid #39A900;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    line-height: 1.8;
    color: #065f46;
}

.decision-timeline {
    position: relative;
    padding-left: 40px;
}

.decision-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}

.decision-item {
    position: relative;
    margin-bottom: 24px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
}

.decision-item::before {
    content: '';
    position: absolute;
    left: -33px;
    top: 20px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #39A900;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #39A900;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge.success {
    background: #dcfce7;
    color: #166534;
}

.badge.warning {
    background: #fef3c7;
    color: #92400e;
}

.badge.danger {
    background: #fee2e2;
    color: #991b1b;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<!-- Header de resultados -->
<div class="results-header animate-in">
    <div class="score-circle">
        <div class="score-value"><?= $finalScore ?></div>
    </div>
    <h1 style="margin:0 0 12px 0; font-size:2.5rem;">¡Escenario Completado!</h1>
    <p style="margin:0; font-size:1.2rem; opacity:0.9;">
        <?php
        $percentage = count($softSkills) > 0 ? ($finalScore / (count($softSkills) * 10)) * 100 : 0;
        if ($percentage >= 80) {
            echo '🌟 Excelente desempeño';
        } elseif ($percentage >= 60) {
            echo '👍 Buen desempeño';
        } elseif ($percentage >= 40) {
            echo '📈 Desempeño aceptable';
        } else {
            echo '💪 Oportunidad de mejora';
        }
        ?>
    </p>
</div>

<!-- Feedback general de la IA -->
<?php if ($overallFeedback): ?>
<section class="card animate-in" style="animation-delay: 0.1s;">
    <h2 style="margin:0 0 16px 0; color:#1f2937; font-size:1.5rem;">
        💬 Feedback del Instructor Virtual
    </h2>
    <div class="feedback-box">
        <?= nl2br(htmlspecialchars($overallFeedback)) ?>
    </div>
</section>
<?php endif; ?>

<!-- Logros Desbloqueados -->
<?php if (!empty($recentAchievements)): ?>
<section class="card animate-in" style="animation-delay: 0.15s; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b;">
    <h2 style="margin:0 0 20px 0; color:#92400e; font-size:1.5rem; display:flex; align-items:center; gap:12px;">
        <span style="font-size:2rem;">🏆</span>
        ¡Nuevos Logros Desbloqueados!
    </h2>

    <div style="display:grid; gap:16px;">
        <?php foreach ($recentAchievements as $achievement): ?>
            <div style="background:white; border-radius:12px; padding:20px; display:flex; gap:16px; align-items:center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="width:60px; height:60px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas <?= htmlspecialchars($achievement['icon'] ?? 'fa-trophy') ?>" style="font-size:1.8rem; color:white;"></i>
                </div>
                <div style="flex:1;">
                    <h3 style="margin:0 0 6px 0; color:#92400e; font-size:1.2rem;">
                        <?= htmlspecialchars($achievement['name'] ?? 'Logro') ?>
                    </h3>
                    <p style="margin:0 0 8px 0; color:#78350f; font-size:0.9rem;">
                        <?= htmlspecialchars($achievement['description'] ?? '') ?>
                    </p>
                    <div style="display:flex; gap:12px; align-items:center;">
                        <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; background:#fef3c7; border-radius:20px; font-size:0.85rem; font-weight:600; color:#92400e;">
                            <i class="fas fa-star"></i>
                            <?= (int)($achievement['points'] ?? 0) ?> puntos
                        </span>
                        <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; background:#dcfce7; border-radius:20px; font-size:0.85rem; font-weight:600; color:#166534;">
                            <i class="fas fa-check-circle"></i>
                            Desbloqueado
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin-top:20px; text-align:center; padding:16px; background:rgba(255,255,255,0.5); border-radius:8px;">
        <p style="margin:0; color:#92400e; font-size:0.95rem;">
            <i class="fas fa-info-circle" style="margin-right:6px;"></i>
            Visita la <a href="<?= \App\Core\Router::url('/achievements') ?>" style="color:#d97706; font-weight:700; text-decoration:underline;">Galería de Logros</a> para ver todos tus logros desbloqueados
        </p>
    </div>
</section>
<?php endif; ?>

<!-- Evaluación por Soft Skill -->
<section class="card animate-in" style="animation-delay: 0.2s;">
    <h2 style="margin:0 0 24px 0; color:#1f2937; font-size:1.5rem;">
        📊 Evaluación por Soft Skills
    </h2>

    <?php
    // Calcular puntaje máximo posible (10 puntos por soft skill)
    $maxScore = count($softSkills) * 10;

    foreach ($softSkills as $skill):
        $skillName = $skill['soft_skill_name'] ?? '';
        $weight = (float)($skill['weight'] ?? 0);
        $score = (int)($finalScores[$skillName] ?? 0);

        // Calcular porcentaje (normalizado a 100)
        $percentage = ($score + 10) / 20 * 100; // Rango de -10 a +10 normalizado a 0-100
        $percentage = max(0, min(100, $percentage));

        // Determinar clase de color
        if ($score >= 7) {
            $colorClass = 'positive';
            $badgeClass = 'success';
            $badgeIcon = '✓';
        } elseif ($score >= 0) {
            $colorClass = 'neutral';
            $badgeClass = 'warning';
            $badgeIcon = '~';
        } else {
            $colorClass = 'negative';
            $badgeClass = 'danger';
            $badgeIcon = '!';
        }
    ?>
        <div class="skill-bar-container">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <div style="flex:1;">
                    <strong style="color:#1f2937; font-size:1.05rem;">
                        <?= htmlspecialchars($skillName) ?>
                    </strong>
                    <span style="color:#6b7280; font-size:0.9rem; margin-left:8px;">
                        (Peso: <?= number_format($weight, 0) ?>%)
                    </span>
                </div>
                <span class="badge <?= $badgeClass ?>">
                    <?= $badgeIcon ?> <?= $score > 0 ? '+' : '' ?><?= $score ?> puntos
                </span>
            </div>
            <div class="skill-bar">
                <div class="skill-bar-fill <?= $colorClass ?>" style="width:<?= $percentage ?>%;">
                    <?= number_format($percentage, 0) ?>%
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div style="margin-top:24px; padding:16px; background:#f9fafb; border-radius:8px; text-align:center;">
        <div style="color:#6b7280; font-size:0.9rem; margin-bottom:4px;">Puntuación Total</div>
        <div style="color:#1f2937; font-size:2rem; font-weight:700;">
            <?= $finalScore ?> / <?= $maxScore ?>
        </div>
        <div style="color:#6b7280; font-size:0.85rem;">
            (<?= number_format(($finalScore / $maxScore) * 100, 1) ?>% del máximo posible)
        </div>
    </div>
</section>

<!-- Timeline de decisiones -->
<section class="card animate-in" style="animation-delay: 0.3s;">
    <h2 style="margin:0 0 24px 0; color:#1f2937; font-size:1.5rem;">
        🔄 Recorrido de tus Decisiones
    </h2>

    <div class="decision-timeline">
        <?php foreach ($decisions as $decision):
            $stage = (int)($decision['stage'] ?? 1);
            $optionLetter = chr(65 + (int)$decision['option_chosen']); // A, B, C
            $scoresImpact = $decision['scores_impact'] ?? [];
        ?>
            <div class="decision-item">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px;">
                    <div>
                        <strong style="color:#39A900; font-size:0.9rem;">ETAPA <?= $stage ?></strong>
                        <div style="color:#1f2937; font-weight:600; margin-top:4px;">
                            Elegiste la Opción <?= $optionLetter ?>
                        </div>
                    </div>
                    <div style="text-align:right; font-size:0.85rem; color:#6b7280;">
                        <?= date('H:i', strtotime($decision['timestamp'] ?? 'now')) ?>
                    </div>
                </div>

                <?php if (!empty($scoresImpact)): ?>
                    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:12px;">
                        <?php foreach ($scoresImpact as $skillName => $points): ?>
                            <span class="badge <?= $points > 0 ? 'success' : ($points < 0 ? 'danger' : 'warning') ?>">
                                <?= htmlspecialchars($skillName) ?>: <?= $points > 0 ? '+' : '' ?><?= $points ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Acciones -->
<section class="card animate-in" style="animation-delay: 0.4s; text-align:center;">
    <h3 style="margin:0 0 24px 0; color:#1f2937;">¿Qué deseas hacer ahora?</h3>
    <div style="display:flex; flex-wrap:wrap; gap:12px; justify-content:center;">
        <a href="<?= Router::url('/profile') ?>" class="btn" style="border:1px solid #e5e7eb;">
            <svg style="width:20px; height:20px; display:inline; vertical-align:middle; margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Ver Mi Perfil
        </a>
        <a href="<?= Router::url('/routes') ?>" class="btn btn-primary">
            <svg style="width:20px; height:20px; display:inline; vertical-align:middle; margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Explorar Más Rutas
        </a>
        <a href="<?= Router::url('/achievements') ?>" class="btn" style="border:1px solid #e5e7eb;">
            <svg style="width:20px; height:20px; display:inline; vertical-align:middle; margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
            Ver Logros
        </a>
    </div>
</section>

<script>
// Animar barras de progreso cuando se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    const bars = document.querySelectorAll('.skill-bar-fill');

    setTimeout(() => {
        bars.forEach(bar => {
            const targetWidth = bar.style.width;
            bar.style.width = '0%';

            setTimeout(() => {
                bar.style.width = targetWidth;
            }, 100);
        });
    }, 500);
});
</script>
