<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('document_types')->insert([
            [
                'name' => 'Purchase Request',
                'description' => 'Pengajuan pembelian barang atau kebutuhan perusahaan.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}