<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentType::create([
            'id' => 1,
            'type' => 'CPF',
            'title' => 'Comum',
        ]);

        DocumentType::create([
            'id' => 2,
            'type' => 'CNPJ',
            'title' => 'Lojista',
        ]);
    }
}
