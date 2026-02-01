<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Analisis de programas: solo usa datos digitados o provistos por el cliente.
 * Se elimina dependencia de Gemini. Si faltan datos, devuelve un stub basico.
 */
final class ProgramAnalysisService
{
    public function analyzeFromInputs(
        string $title,
        ?string $codigoPrograma,
        string $competencias,
        string $perfilEgreso
    ): array {
        $now = date('Y-m-d H:i:s');
        $fileName = 'manual-input';

        $competenciasList = $this->splitLines($competencias);
        $perfil = trim($perfilEgreso);

        if ($perfil === '' || empty($competenciasList)) {
            return $this->stub($title, $codigoPrograma, $fileName, $now, 'Faltan competencias o perfil de egreso.');
        }

        return [
            'nombre' => $title,
            'codigo_programa' => $codigoPrograma,
            'nivel' => 'por_definir',
            'perfil_egresado' => $perfil,
            'competencias' => $competenciasList,
            'resultados_aprendizaje' => [],
            'contextos_laborales' => [],
            '_meta' => [
                'stub' => false,
                'file' => $fileName,
                'generated_at' => $now,
                'source' => 'local',
            ],
        ];
    }

    /**
     * Analiza el programa y agrega soft skills por defecto si no hay otras fuentes.
     */
    public function analyzeInputsAndIdentifySoftSkills(
        string $title,
        ?string $codigoPrograma,
        string $competencias,
        string $perfilEgreso
    ): array {
        $analysis = $this->analyzeFromInputs($title, $codigoPrograma, $competencias, $perfilEgreso);

        try {
            $sectorService = new SectorAnalysisService();
            $softSkillsData = $sectorService->identifySoftSkills($analysis);

            $analysis['sector'] = $softSkillsData['sector'];
            $analysis['soft_skills'] = $softSkillsData['soft_skills'];
            $analysis['soft_skills_generated'] = !empty($softSkillsData['soft_skills']);
        } catch (\Throwable $e) {
            $analysis['sector'] = 'general';
            $analysis['soft_skills'] = [];
            $analysis['soft_skills_generated'] = false;
        }

        return $analysis;
    }

    private function splitLines(string $text): array
    {
        $lines = preg_split('/\r?\n/', $text) ?: [];
        $lines = array_map('trim', $lines);
        return array_values(array_filter($lines, static fn ($line) => $line !== ''));
    }

    private function stub(string $title, ?string $codigoPrograma, string $fileName, string $now, string $reason): array
    {
        return [
            'nombre' => $title,
            'codigo_programa' => $codigoPrograma,
            'nivel' => 'por_definir',
            'perfil_egresado' => 'Analisis pendiente o con error. Revisa _meta.reason.',
            'competencias' => [
                'Comunicacion',
                'Liderazgo',
                'Trabajo en Equipo',
                'Toma de Decisiones',
            ],
            'resultados_aprendizaje' => [
                'Analisis automatico pendiente',
            ],
            'contextos_laborales' => [
                'Pendiente de analisis',
            ],
            '_meta' => [
                'stub' => true,
                'file' => $fileName,
                'generated_at' => $now,
                'reason' => $reason,
                'source' => 'local',
            ],
        ];
    }
}
