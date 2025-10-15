<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Usuario::factory(10)->create();

        $admin = \App\Models\Usuario::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
        ]);

        $user = \App\Models\Usuario::factory()->create([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@gmail.com',
        ]);

        

        Cliente::factory(25)->create();
        Proveedor::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            Producto::factory()->create([
                'codigo_producto' => IdGenerator::generate([
                    'table' => 'products',
                    'field' => 'codigo_producto',
                    'length' => 4,
                    'prefix' => 'PC'
                ])
            ]);
        }

        Categoria::factory(5)->create();

        Permission::create(['name' => 'pos.menu', 'group_name' => 'pos']);
        Permission::create(['name' => 'customer.menu', 'group_name' => 'customer']);
        Permission::create(['name' => 'supplier.menu', 'group_name' => 'supplier']);
       
        Permission::create(['name' => 'category.menu', 'group_name' => 'category']);
        Permission::create(['name' => 'product.menu', 'group_name' => 'product']);
        Permission::create(['name' => 'orders.menu', 'group_name' => 'orders']);
        Permission::create(['name' => 'stock.menu', 'group_name' => 'stock']);
        Permission::create(['name' => 'roles.menu', 'group_name' => 'roles']);
        Permission::create(['name' => 'user.menu', 'group_name' => 'user']);
       

        Role::create(['name' => 'SuperAdmin'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'Admin'])->givePermissionTo(['customer.menu', 'user.menu', 'supplier.menu']);
        Role::create(['name' => 'Account'])->givePermissionTo(['customer.menu', 'user.menu', 'supplier.menu']);
        Role::create(['name' => 'Manager'])->givePermissionTo(['stock.menu', 'orders.menu', 'product.menu']);

        $admin->assignRole('SuperAdmin');
        $user->assignRole('Account');
    }
}
