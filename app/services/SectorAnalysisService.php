<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Servicio para analizar el sector laboral y determinar soft skills.
 * Sin dependencia de Gemini: devuelve datos por defecto o los provistos en el analisis.
 */
final class SectorAnalysisService
{
    public function identifySoftSkills(array $programAnalysis): array
    {
        if (!empty($programAnalysis['soft_skills']) && is_array($programAnalysis['soft_skills'])) {
            return [
                'sector' => strtolower(trim((string) ($programAnalysis['sector'] ?? 'general'))),
                'soft_skills' => $programAnalysis['soft_skills'],
            ];
        }

        return $this->getDefaultSoftSkills();
    }

    private function getDefaultSoftSkills(): array
    {
        return [
            'sector' => 'general',
            'soft_skills' => [
                [
                    'nombre' => 'Comunicacion Efectiva',
                    'peso' => 25.0,
                    'criterios' => [
                        'Expresion clara de ideas',
                        'Escucha activa',
                        'Adaptacion del mensaje a la audiencia',
                        'Comunicacion escrita efectiva'
                    ],
                    'descripcion' => 'Habilidad para comunicarse de manera clara y efectiva en contextos laborales diversos.'
                ],
                [
                    'nombre' => 'Trabajo en Equipo',
                    'peso' => 20.0,
                    'criterios' => [
                        'Colaboracion con companeros',
                        'Resolucion constructiva de conflictos',
                        'Contribucion activa al grupo',
                        'Respeto por la diversidad'
                    ],
                    'descripcion' => 'Capacidad para trabajar eficientemente con otras personas hacia objetivos comunes.'
                ],
                [
                    'nombre' => 'Liderazgo',
                    'peso' => 20.0,
                    'criterios' => [
                        'Toma de iniciativa',
                        'Motivacion de otros',
                        'Delegacion efectiva',
                        'Toma de responsabilidad'
                    ],
                    'descripcion' => 'Habilidad para guiar y motivar a otros hacia el logro de objetivos.'
                ],
                [
                    'nombre' => 'Toma de Decisiones',
                    'peso' => 20.0,
                    'criterios' => [
                        'Analisis de situaciones',
                        'Evaluacion de alternativas',
                        'Decisiones fundamentadas',
                        'Asuncion de consecuencias'
                    ],
                    'descripcion' => 'Capacidad para analizar situaciones y tomar decisiones acertadas bajo presion.'
                ],
                [
                    'nombre' => 'Adaptabilidad',
                    'peso' => 15.0,
                    'criterios' => [
                        'Flexibilidad ante cambios',
                        'Aprendizaje continuo',
                        'Gestion de la incertidumbre',
                        'Innovacion y creatividad'
                    ],
                    'descripcion' => 'Habilidad para adaptarse a nuevas situaciones y aprender continuamente.'
                ]
            ]
        ];
    }
}
