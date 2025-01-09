<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SamplesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\LcpController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\MethodsController;
use App\Http\Controllers\ParameterCombinationController;
use App\Http\Controllers\ParametersController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VueFormController;
use App\Http\Controllers\WaterSamplesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::prefix('/admin')->group(function () {
            Route::get('/', 'index')->name('admin.index');
        });
    });
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::delete('/roles/remove-permission/{role}/{permission}', [RoleController::class, 'removePermission'])
        ->name('roles.remove_permission');
    Route::resource('/permissions', PermissionController::class);
});

Route::controller(PagesController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/nosotros', 'us')->name('us');
    Route::get('/contacto', 'contact')->name('contact');
    Route::get('/testing-eloquent', 'testingEloquent');
    Route::get('/filters', 'filters');
    Route::delete('/logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::prefix('/clientes')->group(function () {
        Route::get('/', [ClientsController::class, 'index'])->name('clients.index');
        Route::get('/create', [ClientsController::class, 'create'])->name('clients.create');
        Route::post('/', [ClientsController::class, 'store'])->name('clients.store');
        Route::get('/{client}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
        Route::put('/{client}/{cesavedac}/set-cesavedac', [ClientsController::class, 'setCesavedac'])->name('clients.set-cesavedac');
        Route::get('/clients_by_name', [ClientsController::class, 'clientsByName']);
    });
    Route::controller(UserController::class)->group(function () {
        Route::prefix('/users')->group(function () {
            Route::get('/', 'index')->name('users.index');
            Route::get('/create', 'create')->name('users.create');
            Route::post('/', 'store')->name('users.store');
            Route::get('/{user}/show', 'show')->name('users.show');
            Route::get('/{user}/edit', 'edit')->name('users.edit');
            Route::put('/{user}', 'update')->name('users.update');
            Route::delete('/{user}', 'destroy')->name('users.destroy');
        });
    });

    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
        Route::get('/show/{id}', [OrdersController::class, 'show'])->name('orders.show');
        Route::get('/edit/{id}', [OrdersController::class, 'edit'])->name('orders.edit');
        Route::get('/change-page', [OrdersController::class, 'changePage']);
        Route::get('/get-client-for-order', [OrdersController::class, 'getClientForOrder']);
        /*Route::get('/test', [TestController::class, 'test']);
        Route::get('/test-dynamic-form', [TestController::class, 'viewTestDynamicForm']);
        Route::get('/testing', [TestController::class, 'viewTestingForm']);
        Route::post('/test-dynamic-form', [TestController::class, 'testDynamicForm']);*/
        Route::post('/', [OrdersController::class, 'store'])->name('orders.store');
        Route::put('/{id}', [OrdersController::class, 'update'])->name('orders.update');
        Route::delete('/{id}', [OrdersController::class, 'delete'])->name('orders.delete');
        Route::patch('/editPartialInfo/{id}', [OrdersController::class, 'editPartialInfo'])->name('orders.partialEdit');
        Route::post('/toggle-cesavedac', [OrdersController::class, 'toggleCesavedac']);
        Route::post('/toggle-supervision', [OrdersController::class, 'toggleSupervision']);
        Route::post('/toggle-hoja-campo', [OrdersController::class, 'toggleHojaCampo']);
        Route::post('/toggle-cadena-custodia', [OrdersController::class, 'toggleCadenaCustodia']);
        Route::post('/toggle-croquis', [OrdersController::class, 'toggleCroquis']);
        Route::post('/toggle-reporte-entregado', [OrdersController::class, 'toggleReporteEntregado']);
        Route::post('/filter', [OrdersController::class, 'filter']);
    });

    Route::controller(WaterSamplesController::class)->group(function () {
        Route::prefix('/water_samples')->group(function () {
            Route::get('/create/{folio}/{numero_muestras}/{inicio_muestras}', 'create')
                ->name('water_samples.create');
            Route::post('/', 'store')
                ->name('water_samples.store');
            Route::get('/{sample}/edit', 'edit')
                ->name('water_samples.edit');
            Route::get('/editAll/{folio}/{aguas_alimentos}', 'editAllWater')
                ->name('water_samples.edit_all');
            Route::delete('/{waterSample}', 'destroy')
                ->name('water_samples.destroy');
                Route::get('/create/v2/{folio}/{numero_muestras}/{inicio_muestras}', 'createV2')
                ->name('water_samples.create_v2');
            Route::post('/v2', 'storeV2')
                ->name('water_samples.store_v2');
            Route::delete('/v2/{waterSample}', 'destroyV2')
                ->name('water_samples.destroy_v2');
            Route::get('/v2/get_rule_params/{ruleId}', 'getRuleParams');
        });
    });

    Route::prefix('/units')->group(function () {
        Route::get('/get-units', [UnitsController::class, 'get']);
        Route::get('/', [UnitsController::class, 'index'])->name('units.index');
        Route::get('/create', [UnitsController::class, 'create'])
            ->name('units.create');
        Route::post('/', [UnitsController::class, 'store'])->name('units.store');
        Route::get('/change-page', [UnitsController::class, 'changePage']);
        Route::get('/filter', [UnitsController::class, 'filter']);
        Route::get('/show/{id}', [UnitsController::class, 'show']);
        Route::get('/edit/{id}', [UnitsController::class, 'edit']);
        Route::put('/{id}', [UnitsController::class, 'update']);
        Route::delete('/{id}', [UnitsController::class, 'destroy'])->name('units.destroy');
    });

    Route::prefix('/methods')->group(function () {
        Route::get('/', [MethodsController::class, 'index'])->name('methods.index');
        Route::get('/create', [MethodsController::class, 'create'])->name('methods.create');
        Route::post('/', [MethodsController::class, 'store'])->name('methods.store');
        Route::get('/{method:id_metodo}/show', [MethodsController::class, 'show'])->name('methods.show');
        Route::get('/{method:id_metodo}/edit', [MethodsController::class, 'edit'])->name('methods.edit');
        Route::put('/{method:id_metodo}', [MethodsController::class, 'update'])->name('methods.update');
        Route::delete('/{method:id_metodo}', [MethodsController::class, 'destroy'])->name('methods.destroy');
        Route::get('/change-page', [MethodsController::class, 'changePage']);
    });

    Route::prefix('/lcps')->group(function () {
        Route::get('/', [LcpController::class, 'index'])->name('lcps.index');
        Route::get('/create', [LcpController::class, 'create'])->name('lcps.create');
        Route::post('/{parameter}', [LcpController::class, 'store'])->name('lcps.store');
        Route::get('/{lcp}/show', [LcpController::class, 'show'])->name('lcps.show');
        Route::get('/{lcp}/edit', [LcpController::class, 'edit'])->name('lcps.edit');
        Route::put('/{lcp}', [LcpController::class, 'update'])->name('lcps.update');
        Route::delete('/{parameter}/{lcp}', [LcpController::class, 'destroy'])->name('lcps.destroy');
    });

    Route::prefix('/parameters')->group(function () {
        Route::get('/', [ParametersController::class, 'index'])->name('parameters.index');
        Route::get('/filter', [ParametersController::class, 'filter'])->name('parameters.filter');
        Route::get('/create', [ParametersController::class, 'create'])->name('parameters.create');
        Route::get('/test', [ParametersController::class, 'test'])->name('parameters.test');
        Route::get('/{parameter}/show', [ParametersController::class, 'show'])->name('parameters.show');
        Route::post('/', [ParametersController::class, 'store']);
        Route::get('/{parameter}/edit', [ParametersController::class, 'edit'])->name('parameters.edit');
        Route::put('/{id}', [ParametersController::class, 'update']);
        Route::delete('/{parameter}', [ParametersController::class, 'destroy'])->name('parameters.destroy');
    });

    Route::resource('rules', RulesController::class);
    Route::get('/rules/show-filter/{ruleId}', [RulesController::class, 'showFilter']);
    Route::resource('parameters-combinations', ParameterCombinationController::class);
    Route::get('/parameters-combinations/{parameter}/get-lcps', [ParameterCombinationController::class, 'getLCPs']);
    Route::post('/parameters-combinations/{rule}/add-param-combination', [ParameterCombinationController::class, 'addParamCombination'])
        ->name('parameters-combinations.add_param_combination');
    Route::delete('/parameters-combinations/{id}/remove-param-combination', [ParameterCombinationController::class, 'removeParamCombination'])
        ->name('parameters-combinations.remove_param_combination');
    
    Route::prefix('/vue-form-tests')->group(function () {
        Route::get('/test1', [VueFormController::class, 'test1'])->name('vue_form_test.test1');
        Route::post('/test1', [VueFormController::class, 'test1Store'])->name('vue_form_test.test1_store');
        Route::get('/test2', [VueFormController::class, 'test2'])->name('vue_form_test.test2');
    });

    Route::controller(TaskController::class)->group(function () {
        Route::prefix('/tasks')->group(function () {
            Route::get('/', 'index')->name('tasks.index');
            Route::get('/create', 'create')->name('tasks.create');
            Route::post('/', 'store')->name('tasks.store');
            Route::get('/{id}/edit', 'edit')->name('tasks.edit');
            Route::delete('/{id}', 'destroy')->name('tasks.delete');
        });
    });

});



require __DIR__.'/auth.php';
