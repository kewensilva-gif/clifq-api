<?php

namespace Database\Seeders;

use App\Models\Equipament;
use Database\Factories\EquipamentFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EquipamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipament::factory()->create([
            'name' => 'Difratômetro de raio-X Panalytical',
            'description' => 'O difratômetro de raio-X Panalytical é um equipamento usado para análise de materiais cristalinos
            através da técnica de difração de raios-X. Ele é projetado para determinar a estrutura cristalina de uma
            substância, permitindo a identificação de fases cristalinas, a determinação de parâmetros de rede e a
            análise quantitativa de fases presentes em uma amostra. O difratômetro de raio-X utiliza um feixe de
            raios-X que é direcionado para a amostra, e os padrões de difração resultantes são detectados e
            analisados para extrair informações sobre a estrutura cristalina da substância. Essas informações são
            valiosas em uma variedade de campos, incluindo ciência dos materiais, geologia, química e ciências
            biológicas. O Panalytical é uma marca conhecida por oferecer difratômetros de alta qualidade e
            precisão para uma variedade de aplicações de análise de materiais.
            ',
            'brand' => 'Panalytical',
            'model' => 'modelo',
            'image' => 'images/diafratometro.jpg',
            'manufacturer_number' => "21",
            'asset_number' => "12",
        ]);
        Equipament::factory(20)->create();
    }
}
