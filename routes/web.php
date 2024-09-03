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
    Route::resource('/permissions', PermissionController::class);
});

Route::controller(PagesController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/nosotros', 'us')->name('us');
    Route::get('/contacto', 'contact')->name('contact');
    Route::get('/testing-eloquent', 'testingEloquent');
    Route::get('/filters', 'filters');
    Route::get('/logout', 'logout');
});

Route::middleware('auth')->group(function () {
    Route::prefix('/clientes')->group(function () {
        Route::get('/', [ClientsController::class, 'index'])->name('clients.index');
        Route::get('/clients_by_name', [ClientsController::class, 'clientsByName']);
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

    Route::prefix('/samples')->group(function () {
        Route::get('/cre/{folio}/{numero_muestras}/{inicio_muestras}', [SamplesController::class, 'createWater'])->name('samples.create_water');
    });

    Route::prefix('/units')->group(function () {
        Route::get('/get-units', [UnitsController::class, 'get']);
        Route::get('/', [UnitsController::class, 'index'])->name('units.index');
        Route::get('/create', [UnitsController::class, 'create'])
            ->name('units.create');
        Route::post('/', [UnitsController::class, 'store']);
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
        Route::delete('/{lcp}', [LcpController::class, 'destroy'])->name('lcps.destroy');
    });

    Route::prefix('/parameters')->group(function () {
        Route::get('/', [ParametersController::class, 'index'])->name('parameters.index');
        Route::get('/filter', [ParametersController::class, 'filter'])->name('parameters.filter');
        Route::get('/create', [ParametersController::class, 'create'])->name('parameters.create');
        Route::get('/{parameter}/show', [ParametersController::class, 'show'])->name('parameters.show');
        Route::post('/', [ParametersController::class, 'store']);
        Route::get('/{parameter}/edit', [ParametersController::class, 'edit'])->name('parameters.edit');
        Route::put('/{id}', [ParametersController::class, 'update']);
        Route::delete('/{parameter}', [ParametersController::class, 'destroy'])->name('parameters.destroy');
    });

    Route::resource('rules', RulesController::class);
    Route::resource('parameters-combinations', ParameterCombinationController::class);
    Route::get('/parameters-combinations/{parameter}/get-lcps', [ParameterCombinationController::class, 'getLCPs']);
    Route::post('/parameters-combinations/{rule}/add-param-combination', [ParameterCombinationController::class, 'addParamCombination'])
        ->name('parameters-combinations.add_param_combination');
});



require __DIR__.'/auth.php';
