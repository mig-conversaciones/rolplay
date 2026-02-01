<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Servicio para generar escenarios dinamicos en 3 etapas con IA.
 * La generacion en backend se deshabilita para usar Puter.js en el navegador.
 */
final class DynamicScenarioService
{
    public function __construct()
    {
        throw new RuntimeException('Generacion dinamica en backend deshabilitada. Usa Puter.js desde la interfaz.');
    }

    public function generateStage1(array $program, array $softSkills, ?string $focus = null): array
    {
        throw new RuntimeException('Generacion dinamica en backend deshabilitada.');
    }

    public function generateStage2(
        array $stage1Content,
        int $chosenOptionIndex,
        array $softSkills,
        array $program
    ): array {
        throw new RuntimeException('Generacion dinamica en backend deshabilitada.');
    }

    public function generateStage3(
        array $stage1Content,
        array $stage2Content,
        int $stage1Choice,
        int $stage2Choice,
        array $softSkills,
        array $accumulatedScores,
        array $program
    ): array {
        throw new RuntimeException('Generacion dinamica en backend deshabilitada.');
    }
}
