<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Genera escenarios IA a partir del analisis de un programa.
 * La generacion en backend se deshabilita para usar Puter.js en el navegador.
 */
final class ScenarioGeneratorService
{
    public function generateForProgram(int $programId, int $instructorId, ?string $focus = null): int
    {
        throw new RuntimeException('Generacion de escenarios en backend deshabilitada. Usa Puter.js desde la interfaz.');
    }

    public function validateScenarioPayload(array $payload): array
    {
        $title = trim((string) ($payload['title'] ?? 'Escenario IA'));
        $description = trim((string) ($payload['description'] ?? 'Escenario generado por IA'));
        $area = trim((string) ($payload['area'] ?? 'general'));
        $difficulty = trim((string) ($payload['difficulty'] ?? 'basico'));
        $steps = $payload['steps'] ?? [];

        if (!is_array($steps) || empty($steps)) {
            throw new RuntimeException('El escenario no contiene pasos validos.');
        }

        return [
            'title' => $title,
            'description' => $description,
            'area' => $area,
            'difficulty' => $difficulty,
            'steps' => $steps,
        ];
    }
}
