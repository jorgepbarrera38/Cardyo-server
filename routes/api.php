<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');

});

Route::middleware('auth')->group(function () {

    //Dashboard
    Route::get('dashboard/get-data', 'DashboardController@getData');

    //Tipos de membresías
    Route::get('membership-types/{membership_type}/edit', 'MembershipTypeController@edit');
    Route::apiResource('membership-types', 'MembershipTypeController');

    //Clientes
    Route::get('clients/{client}/edit', 'ClientController@edit');
    Route::apiResource('clients', 'ClientController');

    //Membresías del cliente
    Route::get('client-memberships/get-data-to-create-membership', 'ClientMembershipController@getDataToCreateMembership');
    Route::get('client-memberships/{client_membership}/get-asistances-from-membership', 'ClientMembershipController@getAsistancesFromMembership');
    Route::post('client-memberships/{client}', 'ClientMembershipController@store');
    Route::get('client-memberships/{client_membership}/edit', 'ClientMembershipController@edit');
    Route::put('client-memberships/{client_membership}/update', 'ClientMembershipController@update');
    Route::delete('client-memberships/{client_membership}/destroy', 'ClientMembershipController@destroy');

    //Medidas corporales del cliente
    Route::post('client-body-measurements/{client}', 'ClientBodyMeasurementController@store');
    Route::get('client-body-measurements/{client_body_measurement}/edit', 'ClientBodyMeasurementController@edit');
    Route::put('client-body-measurements/{client_body_measurement}/update', 'ClientBodyMeasurementController@update');
    Route::delete('client-body-measurements/{client_body_measurement}/destroy', 'ClientBodyMeasurementController@destroy');

    //Asistencias
    Route::get('assitances/get-active-memberships', 'AssistanceController@getActiveMemberships');
    Route::post('assitances/mark-attendance', 'AssistanceController@markAttendance');

    //Turnos
    Route::get('turns/{turn}/edit', 'TurnController@edit');
    Route::apiResource('turns', 'TurnController');

    //Inventario - categorías
    Route::get('inventory-categories/{inventoryCategory}/edit', 'InventoryCategoryController@edit');
    Route::apiResource('inventory-categories', 'InventoryCategoryController');

    //Inventario - productos
    Route::get('inventory-products/get-data-to-create-product', 'InventoryProductController@getDataToCreateProduct');
    Route::get('inventory-products/{inventoryProduct}/edit', 'InventoryProductController@edit');
    Route::apiResource('inventory-products', 'InventoryProductController');

    //Inventario - proveedores
    Route::get('inventory-providers/{inventoryProvider}/edit', 'InventoryProviderController@edit');
    Route::apiResource('inventory-providers', 'InventoryProviderController');

    //Inventario - compras
    Route::get('inventory-purchases/get-data-to-create-purchase', 'InventoryPurchaseController@getDataToCreatePurchase');
    Route::get('inventory-purchases/{inventoryPurchase}', 'InventoryPurchaseController@show');
    Route::apiResource('inventory-purchases', 'InventoryPurchaseController');

    //Inventario - devolución de compras
    Route::get('inventory-purchase-returns/get-data-to-create-purchase-return', 'InventoryPurchaseReturnController@getDataToCreatePurchase');
    Route::get('inventory-purchase-returns/{inventoryPurchaseReturn}', 'InventoryPurchaseReturnController@show');
    Route::apiResource('inventory-purchase-returns', 'InventoryPurchaseReturnController');

    //Inventario - ajustes de inventario
    Route::get('inventory-tweaks/get-data-to-create-inventory-tweak', 'InventoryTweakController@getDataToCreateInventoryTweak');
    Route::apiResource('inventory-tweaks', 'InventoryTweakController');

    //Ventas
    Route::get('sales', 'SaleController@index');
    Route::get('sales/get-data-to-register-sale', 'SaleController@getDataToRegisterSale');
    Route::get('sales/{sale}', 'SaleController@show');
    Route::post('sales', 'SaleController@store');
    Route::delete('sales/{sale}', 'SaleController@destroy');

    //Sistema - usuarios
    Route::get('users/{user}/get-permissions-from-user-and-all-permissions', 'UserController@getPermissionsFromUserAndAllPermissions');
    Route::put('users/{user}/update-permissions', 'UserController@updatePermissions');
    Route::apiResource('users', 'UserController');

    //Empresa
    Route::get('company', 'CompanyController@show');
    Route::post('company', 'CompanyController@store');

    //Reportes - Asistencias
    Route::get('reports/get-assistances', 'Reports\AssistanceController@getAssistances');
    Route::get('reports/get-sales', 'Reports\SaleController@getSales');
});
