<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usuario 1',
            'email' => 'usuario1@example.com',
            'password' => Hash::make('123456'),
        ]);
        User::factory()->create([
            'name' => 'Usuario 2',
            'email' => 'usuario2@example.com',
            'password' => Hash::make('654321'),
        ]);
        User::factory()->create([
            'name' => 'Usuario 3',
            'email' => 'usuario3@example.com',
            'password' => Hash::make('456789'),
        ]);

        Categoria::factory()->create([
            'nome' => 'Perfumaria',
            'descricao' => 'Produtos de perfumaria',
        ]);
        Categoria::factory()->create([
            'nome' => 'Cosméticos',
            'descricao' => 'Produtos de cosméticos',
        ]);
        Categoria::factory()->create([
            'nome' => 'Vitaminas e Suplementos',
            'descricao' => 'Produtos de vitaminas e suplementos',
        ]);
        Categoria::factory()->create([
            'nome' => 'Medicamentos',
            'descricao' => 'Produtos de medicamentos',
        ]);

        Produto::factory()->create([
            'nome' => 'Perfume XYZ',
            'descricao' => 'Perfume XYZ',
            'preco' => 100.00,
            'estoque' => 10,
            'categorias' => [1],
        ]);
        Produto::factory()->create([
            'nome' => 'Esmalte XYZ',
            'descricao' => 'Esmalte XYZ',
            'preco' => 50.00,
            'estoque' => 10,
            'categorias' => [2],
        ]);
        Produto::factory()->create([
            'nome' => 'Vitamina C XYZ',
            'descricao' => 'Vitamina C XYZ',
            'preco' => 30.00,
            'estoque' => 10,
            'categorias' => [3],
        ]);
        Produto::factory()->create([
            'nome' => 'Paracetamol XYZ',
            'descricao' => 'Paracetamol XYZ',
            'preco' => 10.00,
            'estoque' => 10,
            'categorias' => [4],
        ]);
    }
}
