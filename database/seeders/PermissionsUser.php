<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Tipos de membresías
        Permission::create([
            'name' => 'membership_types.index',
            'description' => 'Listar tipos de membresías'
        ]);
        Permission::create([
            'name' => 'membership_types.store',
            'description' => 'Registrar tipos de membresías'
        ]);
        Permission::create([
            'name' => 'membership_types.update',
            'description' => 'Actualizar tipos de membresías'
        ]);
        Permission::create([
            'name' => 'membership_types.delete',
            'description' => 'Eliminar tipos de membresías'
        ]);

        //Turnos
        Permission::create([
            'name' => 'turns.index',
            'description' => 'Listar turnos'
        ]);
        Permission::create([
            'name' => 'turns.store',
            'description' => 'Registrar turnos'
        ]);
        Permission::create([
            'name' => 'turns.update',
            'description' => 'Actualizar turnos'
        ]);
        Permission::create([
            'name' => 'turns.delete',
            'description' => 'Eliminar turnos'
        ]);

        //Clientes
        Permission::create([
            'name' => 'clients.index',
            'description' => 'Listar clientes'
        ]);
        Permission::create([
            'name' => 'clients.show',
            'description' => 'Ver información de clientes'
        ]);
        Permission::create([
            'name' => 'clients.store',
            'description' => 'Registrar clientes'
        ]);
        Permission::create([
            'name' => 'clients.update',
            'description' => 'Actualizar clientes'
        ]);
        Permission::create([
            'name' => 'clients.delete',
            'description' => 'Eliminar clientes'
        ]);

        //Clientes - Membresías
        Permission::create([
            'name' => 'client_memberships.store',
            'description' => 'Registrar membresías del cliente'
        ]);
        Permission::create([
            'name' => 'client_memberships.update',
            'description' => 'Actualizar membresías del cliente'
        ]);
        Permission::create([
            'name' => 'client_memberships.delete',
            'description' => 'Eliminar membresías del cliente'
        ]);

        //Clientes - Medidas corporales
        Permission::create([
            'name' => 'client_body_measurements.store',
            'description' => 'Registrar medidas corporales del cliente'
        ]);
        Permission::create([
            'name' => 'client_body_measurements.update',
            'description' => 'Actualizar medidas corporales del cliente'
        ]);
        Permission::create([
            'name' => 'client_body_measurements.delete',
            'description' => 'Eliminar medidas corporales del cliente'
        ]);

        //Marcar asistencias
        Permission::create([
            'name' => 'assistances.mark_attendance',
            'description' => 'Marcar asistencias de clientes'
        ]);

        //Inventarios - Categorías
        Permission::create([
            'name' => 'inventory_categories.index',
            'description' => 'Listar categorías de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_categories.store',
            'description' => 'Registrar categorías de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_categories.update',
            'description' => 'Actualizar categorías de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_categories.delete',
            'description' => 'Eliminar categorías de inventario'
        ]);

        //Inventarios - Productos
        Permission::create([
            'name' => 'inventory_products.index',
            'description' => 'Listar productos de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_products.store',
            'description' => 'Registrar productos de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_products.update',
            'description' => 'Actualizar productos de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_products.delete',
            'description' => 'Eliminar productos de inventario'
        ]);

        //Inventarios - Proveedores
        Permission::create([
            'name' => 'inventory_providers.index',
            'description' => 'Listar proveedores de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_providers.store',
            'description' => 'Registrar proveedores de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_providers.update',
            'description' => 'Actualizar proveedores de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_providers.delete',
            'description' => 'Eliminar proveedores de inventario'
        ]);

        //Inventarios - Compras
        Permission::create([
            'name' => 'inventory_purchases.index',
            'description' => 'Listar compras de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_purchases.store',
            'description' => 'Registrar compras de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_purchases.show',
            'description' => 'Ver detalle de compras de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_purchases.delete',
            'description' => 'Anular compras de inventario'
        ]);

        //Inventarios - Devolución de compras - se deshabilitó
        /*Permission::create([
            'name' => 'inventory_purchase_returns.index',
            'description' => 'Listar devoluciones de compras de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_purchase_returns.store',
            'description' => 'Registrar devoluciones de compras de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_purchase_returns.show',
            'description' => 'Ver información de devoluciones de compras de inventario'
        ]);*/

        //Inventarios - Ajustes de inventarios
        Permission::create([
            'name' => 'inventory_tweaks.index',
            'description' => 'Listar ajustes de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_tweaks.store',
            'description' => 'Registrar ajustes de inventario'
        ]);
        Permission::create([
            'name' => 'inventory_tweaks.show',
            'description' => 'Ver detalle de ajustes de inventario'
        ]);

        //Ventas
        Permission::create([
            'name' => 'sales.index',
            'description' => 'Listar ventas'
        ]);
        Permission::create([
            'name' => 'sales.store',
            'description' => 'Registrar ventas'
        ]);
        Permission::create([
            'name' => 'sales.show',
            'description' => 'Ver detalle de ventas'
        ]);
        Permission::create([
            'name' => 'sales.delete',
            'description' => 'Anular ventas'
        ]);

        //Usuarios del sistema
        Permission::create([
            'name' => 'users.index',
            'description' => 'Listar usuarios del sistema'
        ]);
        Permission::create([
            'name' => 'users.store',
            'description' => 'Registrar usuarios del sistema'
        ]);
        Permission::create([
            'name' => 'users.update_permissions',
            'description' => 'Actualizar permisos de usuarios del sistema'
        ]);
        Permission::create([
            'name' => 'users.update',
            'description' => 'Actualizar usuarios del sistema'
        ]);
        Permission::create([
            'name' => 'users.delete',
            'description' => 'Eliminar usuarios del sistema'
        ]);

        //Empresa
        Permission::create([
            'name' => 'company.index',
            'description' => 'Actualizar datos de empresa'
        ]);
    }
}
