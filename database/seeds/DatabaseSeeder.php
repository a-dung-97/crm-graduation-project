<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Catalog::create(['name' => 'Khách hàng']);
        Catalog::create(['name' => 'Liên hệ']);
        Catalog::create(['name' => 'Cơ hội']);
        Catalog::create(['name' => 'Hóa đơn']);
        Catalog::create(['name' => 'Tiềm năng']);
        Catalog::create(['name' => 'Đơn hàng']);
        Catalog::create(['name' => 'Báo giá']);
    }
}
