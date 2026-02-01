<?php

declare(strict_types=1);

use App\Core\Router;

$programs = $programs ?? [];
$user = $user ?? [];
?>

<style>
.program-card {
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
    position: relative;
}

.program-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(57, 169, 0, 0.15);
    border-color: #39A900;
}

.program-card-header {
    background: linear-gradient(135deg, #39A900 0%, #007832 100%);
    padding: 24px;
    color: white;
    position: relative;
}

.program-card-body {
    padding: 24px;
}

.sector-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.skill-chip {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    background: #f0fdf4;
    color: #166534;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    margin: 4px;
}

.empty-state {
    text-align: center;
    padding: 64px 32px;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 16px;
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 24px;
    background: #e5e7eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #9ca3af;
}
</style>

<!-- Header -->
<section class="card">
    <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:12px;">
        <div>
            <h1 style="margin:0 0 8px 0; font-size:2rem; color:#1f2937;">
                🎓 Programas de Formación
            </h1>
            <p class="muted" style="margin:0;">
                Explora los programas disponibles y practica tus soft skills con escenarios dinámicos
            </p>
        </div>
        <div style="display:flex; gap:8px;">
            <a class="btn" style="border:1px solid #e5e7eb;" href="<?= Router::homeUrl() ?>">
                <svg style="width:16px; height:16px; display:inline; vertical-align:middle; margin-right:4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Inicio
            </a>
            <a class="btn" style="border:1px solid #e5e7eb;" href="<?= Router::url('/routes') ?>">
                <svg style="width:16px; height:16px; display:inline; vertical-align:middle; margin-right:4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Mis Rutas
            </a>
        </div>
    </div>
</section>

<!-- Estadísticas rápidas -->
<?php if (!empty($programs)): ?>
<section class="card" style="margin-top:16px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
        <div style="text-align:center; padding:16px; background:#f0fdf4; border-radius:12px;">
            <div style="font-size:2.5rem; font-weight:800; color:#39A900; margin-bottom:4px;">
                <?= count($programs) ?>
            </div>
            <div style="color:#166534; font-size:0.9rem; font-weight:600;">
                Programas Disponibles
            </div>
        </div>
        <div style="text-align:center; padding:16px; background:#eff6ff; border-radius:12px;">
            <div style="font-size:2.5rem; font-weight:800; color:#2563eb; margin-bottom:4px;">
                <?php
                $uniqueSectors = array_unique(array_column($programs, 'sector'));
                echo count(array_filter($uniqueSectors));
                ?>
            </div>
            <div style="color:#1e40af; font-size:0.9rem; font-weight:600;">
                Sectores Diferentes
            </div>
        </div>
        <div style="text-align:center; padding:16px; background:#fef3c7; border-radius:12px;">
            <div style="font-size:2.5rem; font-weight:800; color:#d97706; margin-bottom:4px;">
                ∞
            </div>
            <div style="color:#92400e; font-size:0.9rem; font-weight:600;">
                Escenarios Posibles
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Grid de programas -->
<section style="margin-top:24px;">
    <?php if (empty($programs)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                📚
            </div>
            <h2 style="color:#1f2937; margin:0 0 12px 0;">No hay programas disponibles</h2>
            <p style="color:#6b7280; max-width:500px; margin:0 auto 24px;">
                Actualmente no hay programas de formación disponibles para practicar. Consulta con tu instructor o vuelve más tarde.
            </p>
            <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                <a href="<?= Router::homeUrl() ?>" class="btn btn-primary">
                    Ir al Inicio
                </a>
                <a href="<?= Router::url('/scenarios') ?>" class="btn" style="border:1px solid #e5e7eb;">
                    Ver Escenarios Estáticos
                </a>
            </div>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:24px;">
            <?php foreach ($programs as $program):
                $programId = (int)($program['id'] ?? 0);
                $title = htmlspecialchars($program['title'] ?? 'Programa');
                $sector = htmlspecialchars(ucfirst($program['sector'] ?? 'general'));
                $hasSoftSkills = !empty($program['soft_skills_count']) && (int)$program['soft_skills_count'] >= 5;
                $softSkillsCount = (int)($program['soft_skills_count'] ?? 0);
            ?>
                <div class="program-card">
                    <!-- Header -->
                    <div class="program-card-header">
                        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px;">
                            <span class="sector-badge">
                                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <?= $sector ?>
                            </span>
                            <?php if ($hasSoftSkills): ?>
                                <span style="background:rgba(255,255,255,0.3); padding:4px 10px; border-radius:12px; font-size:0.85rem; font-weight:700;">
                                    ✓ Listo
                                </span>
                            <?php endif; ?>
                        </div>
                        <h3 style="margin:0; font-size:1.3rem; line-height:1.4;">
                            <?= $title ?>
                        </h3>
                    </div>

                    <!-- Body -->
                    <div class="program-card-body">
                        <?php if ($hasSoftSkills): ?>
                            <!-- Soft skills preview -->
                            <div style="margin-bottom:16px;">
                                <div style="color:#6b7280; font-size:0.85rem; font-weight:600; margin-bottom:8px;">
                                    🎯 <?= $softSkillsCount ?> Soft Skills a Evaluar
                                </div>
                                <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                    <?php
                                    // Mostrar primeras 3 soft skills si existen
                                    if (!empty($program['sample_skills'])) {
                                        $sampleSkills = array_slice($program['sample_skills'], 0, 3);
                                        foreach ($sampleSkills as $skill) {
                                            echo '<span class="skill-chip">' . htmlspecialchars($skill) . '</span>';
                                        }
                                        if ($softSkillsCount > 3) {
                                            echo '<span class="skill-chip">+' . ($softSkillsCount - 3) . ' más</span>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <p style="color:#6b7280; font-size:0.9rem; line-height:1.6; margin:0 0 20px 0;">
                                Practica con escenarios dinámicos generados por IA que se adaptan a tus decisiones en tiempo real.
                            </p>

                            <!-- Botón de acción -->
                            <form method="post" action="<?= Router::url('/learner/programs/' . $programId . '/start-dynamic') ?>" style="margin:0;">
                                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; font-size:1rem; justify-content:center; display:flex; align-items:center; gap:8px;">
                                    <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Iniciar Simulación</span>
                                </button>
                            </form>

                            <div style="margin-top:12px; padding:12px; background:#f9fafb; border-radius:8px; text-align:center;">
                                <div style="color:#6b7280; font-size:0.8rem;">
                                    ⚡ Escenario único de <strong>3 etapas</strong>
                                </div>
                            </div>

                        <?php else: ?>
                            <!-- Programa sin soft skills -->
                            <div style="padding:32px; text-align:center; background:#fef3c7; border-radius:12px;">
                                <div style="font-size:2rem; margin-bottom:8px;">⏳</div>
                                <div style="color:#92400e; font-weight:600; margin-bottom:4px;">
                                    En configuración
                                </div>
                                <div style="color:#78350f; font-size:0.85rem;">
                                    El instructor aún no ha analizado este programa
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Información adicional -->
        <div class="card" style="margin-top:32px; background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:2px solid #3b82f6;">
            <div style="display:flex; gap:16px; align-items:start;">
                <div style="font-size:3rem;">💡</div>
                <div style="flex:1;">
                    <h3 style="margin:0 0 8px 0; color:#1e40af;">¿Cómo funcionan los escenarios dinámicos?</h3>
                    <ul style="color:#1e40af; line-height:1.8; margin:0; padding-left:20px;">
                        <li><strong>Etapa 1:</strong> Se te presenta una situación laboral inicial</li>
                        <li><strong>Etapa 2:</strong> El escenario se adapta según tu primera decisión</li>
                        <li><strong>Etapa 3:</strong> Cierre con feedback personalizado de IA</li>
                        <li><strong>Evaluación:</strong> Recibes puntajes en las 5 soft skills del programa</li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
