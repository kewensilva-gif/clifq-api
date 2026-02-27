<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EquipamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
     * Lista de equipamentos de laboratório comuns
     * 
     * @return string
     */
    private function getRandomLabEquipment(): string
    {
        $labEquipments = [
            'Microscópio Óptico',
            'Espectrômetro de Massa',
            'Centrífuga',
            'Autoclave', 
            'Espectrofotômetro UV-Visível',
            'Balança Analítica',
            'Agitador Magnético',
            'Estufa Bacteriológica',
            'Cromatógrafo Líquido',
            'Cromatógrafo a Gás',
            'Difratômetro de Raios-X',
            'Fluorímetro',
            'Medidor de pH',
            'Banho-Maria',
            'Destilador de Água',
            'Condutivímetro',
            'Medidor de Oxigênio Dissolvido',
            'Equipamento de PCR',
            'Contador de Células',
            'Analisador Bioquímico',
            'Titulador Automático',
            'Pipetador Automático',
            'Microscópio Eletrônico',
            'Ultramicrotomo',
            'Homogeneizador',
            'Rotaevaporador',
            'Câmara de Fluxo Laminar',
            'Freezer Ultra-Baixa Temperatura',
            'Osmômetro',
            'Refratômetro',
            'Fermentador',
            'Sequenciador de DNA',
            'Analisador de Partículas',
            'Reômetro',
            'Polarímetro',
            'Viscosímetro',
            'Liofilizador',
            'Calorímetro',
            'Sistema de Purificação de Água',
            'Contador Geiger'
        ];
        
        return $labEquipments[array_rand($labEquipments)] . ' ' . fake()->randomNumber(3);
    }

    public function definition(): array
    {
        return [
            'name' => $this->getRandomLabEquipment(),
            'description' => fake()->text(),
            'manufacturer_number' => fake()->randomNumber(),
            'asset_number' => fake()->randomNumber(),
            'brand' => fake()->company(), // Substituído por company() que gera strings mais curtas
            'model' => fake()->word() . ' ' . fake()->randomNumber(3), // Substituído por texto mais curto
            'image' => fake()->url()
        ];
    }
}