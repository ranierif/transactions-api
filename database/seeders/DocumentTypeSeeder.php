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
            'type' => 'CPF',
            'title' => 'Comum',
        ]);

        DocumentType::create([
            'type' => 'CNPJ',
            'title' => 'Lojista',
        ]);
    }
}
