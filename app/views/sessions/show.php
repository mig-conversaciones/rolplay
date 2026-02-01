<?php

declare(strict_types=1);

use App\Core\Router;

$session = $session ?? null;
$decisions = $decisions ?? [];
?>
<?php if (!$session): ?>
    <section class="card">
        <h2 style="margin-top:0;">Sesion no disponible</h2>
        <a class="btn btn-primary" href="<?= Router::url('/profile') ?>">Volver al perfil</a>
    </section>
    <?php return; ?>
<?php endif; ?>

<section class="card">
    <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:start;">
        <div>
            <h2 style="margin:0 0 6px 0;">Detalle de sesion #<?= (int) $session['id'] ?></h2>
            <p class="muted" style="margin:0;"><?= htmlspecialchars((string) ($session['scenario_title'] ?? 'Escenario')) ?></p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <a class="btn" style="border:1px solid #e5e7eb;" href="<?= Router::url('/profile') ?>">Perfil</a>
            <a class="btn btn-primary" href="<?= Router::url('/scenarios/' . (int) ($session['scenario_id'] ?? 0)) ?>">Jugar de nuevo</a>
        </div>
    </div>
</section>

<section class="card" style="margin-top:16px;">
    <h3 style="margin-top:0;">Resumen</h3>
    <div class="row">
        <div class="card">
            <div class="muted">Puntaje final</div>
            <div style="font-size:1.8rem; font-weight:700;"><?= (int) ($session['final_score'] ?? 0) ?></div>
        </div>
        <div class="card">
            <div class="muted">Progreso</div>
            <div style="font-size:1.8rem; font-weight:700;"><?= (int) round((float) ($session['completion_percentage'] ?? 0)) ?>%</div>
        </div>
        <div class="card">
            <div class="muted">Decisiones</div>
            <div style="font-size:1.8rem; font-weight:700;"><?= (int) ($session['decisions_count'] ?? 0) ?></div>
        </div>
        <div class="card">
            <div class="muted">Inicio</div>
            <div style="font-size:1.1rem; font-weight:600;"><?= htmlspecialchars((string) ($session['started_at'] ?? '')) ?></div>
        </div>
    </div>

    <?php $scores = $session['scores'] ?? []; ?>
    <div style="margin-top:8px;" class="muted">
        <?php if (empty($scores)): ?>
            Sin impactos acumulados aun.
        <?php else: ?>
            <?php
            $parts = [];
            foreach ($scores as $k => $v) {
                $parts[] = htmlspecialchars((string) $k) . ': ' . (int) $v;
            }
            echo 'Impacto por competencia: ' . implode(' · ', $parts);
            ?>
        <?php endif; ?>
    </div>
</section>

<section class="card" style="margin-top:16px;">
    <h3 style="margin-top:0;">Decisiones tomadas</h3>
    <?php if (empty($decisions)): ?>
        <p class="muted">Aun no hay decisiones registradas para esta sesion.</p>
    <?php else: ?>
        <div style="display:grid; gap:10px;">
            <?php foreach ($decisions as $d): ?>
                <?php
                $ft = (string) ($d['feedback_type'] ?? 'neutral');
                $badgeBg = $ft === 'good' ? '#ecfdf5' : ($ft === 'bad' ? '#fef2f2' : '#eff6ff');
                $badgeBd = $ft === 'good' ? '#a7f3d0' : ($ft === 'bad' ? '#fecaca' : '#bfdbfe');
                $badgeFg = $ft === 'good' ? '#065f46' : ($ft === 'bad' ? '#991b1b' : '#1e3a8a');
                ?>
                <article style="border:1px solid #e5e7eb; border-radius:12px; padding:12px; background:white;">
                    <div style="display:flex; justify-content:space-between; gap:8px; flex-wrap:wrap; align-items:center;">
                        <div>
                            <strong>Paso <?= (int) ($d['step_number'] ?? 0) + 1 ?></strong>
                            <span class="muted" style="margin-left:6px;">Opcion <?= chr(65 + (int) ($d['option_chosen'] ?? 0)) ?></span>
                        </div>
                        <span style="font-size:0.85rem; padding:4px 8px; border-radius:999px; background:<?= $badgeBg ?>; border:1px solid <?= $badgeBd ?>; color:<?= $badgeFg ?>;">
                            <?= htmlspecialchars($ft) ?>
                        </span>
                    </div>

                    <?php $impact = $d['scores_impact'] ?? []; ?>
                    <?php if (!empty($impact)): ?>
                        <div class="muted" style="margin-top:6px; font-size:0.92rem;">
                            <?php
                            $parts = [];
                            foreach ($impact as $k => $v) {
                                $parts[] = htmlspecialchars((string) $k) . ': ' . (int) $v;
                            }
                            echo 'Impacto: ' . implode(' · ', $parts);
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="muted" style="margin-top:6px; font-size:0.9rem;">
                        <?= htmlspecialchars((string) ($d['timestamp'] ?? '')) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>