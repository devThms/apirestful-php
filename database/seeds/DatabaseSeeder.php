<?php

use App\Model\User;
use App\Model\Product;
use App\Model\Category;
use App\Model\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $cantUsuarios = 100;
        $cantCategorias = 20;
        $cantProductos = 500;
        $cantTransacciones = 500;

        factory(User::class, $cantUsuarios)->create();
        factory(Category::class, $cantCategorias)->create();
        factory(Product::class, $cantProductos)->create()->each(
            function($producto) {
                $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $producto->categories()->attach($categorias);
            }
        );
        factory(Transaction::class, $cantTransacciones)->create();
        

    }
}
