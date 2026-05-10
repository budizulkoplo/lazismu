<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

// Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserProjectController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitDetailController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LazismuReportController;
use App\Http\Controllers\LazismuDashboardController;
use App\Http\Controllers\MuzakiController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\KodeSetoranController;
use App\Http\Controllers\KodetransaksiController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\MuzakiAuthController;
use App\Http\Controllers\MuzakiPortalController;
use App\Http\Controllers\ProjectSelectionController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\HRISController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\PlottingUnitKerjaController;
use App\Http\Controllers\KelompokJamController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengajuanIzinController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\MasterGajiController;

// Mobile
use App\Http\Controllers\Mobile\DashboardController;
use App\Http\Controllers\Mobile\MobileProjectController;
use App\Http\Controllers\Mobile\MobileProfileController;
use App\Http\Controllers\Mobile\PresensiController;
use App\Http\Controllers\Mobile\KalenderController;
/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

// Halaman utama aplikasi untuk muzaki
Route::get('/', [MuzakiAuthController::class, 'create'])->name('home');

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Pilih Project (akses tanpa middleware check.project)
Route::middleware(['auth', 'verified', 'global.app'])->group(function () {
    Route::get('/choose-project', [ProjectSelectionController::class, 'index'])->name('choose.project');
    Route::post('/choose-project', [ProjectSelectionController::class, 'store'])->name('choose.project.store');
});

    // Dashboard
    Route::get('/dashboard', [LazismuDashboardController::class, 'index'])
        ->middleware('global.app:admin')
        ->name('dashboard');

    // Profile
    Route::prefix('profile')->middleware(['role:superadmin|admin|hrd|pengurus|keuangan|direktur|manager|adminpt', 'global.app'])->group(function () {
        
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/', [ProfileController::class, 'upload'])->name('profile.upload');
    });

    // Users
    Route::prefix('users')->middleware(['role:superadmin|admin|hrd|pengurus|keuangan|direktur|manager|adminpt', 'global.app'])->group(function () {
        Route::get('/list', [UsersController::class, 'index'])->name('users.list');
        Route::get('/getdata', [UsersController::class, 'getdata'])->name('users.getdata');
        Route::post('/assignRole', [UsersController::class, 'kasihRole'])->name('users.assignRole');
        Route::post('/password/update', [UsersController::class, 'updatePassword'])->name('users.updatepassword');
        Route::post('/store', [UsersController::class, 'store'])->name('users.store');
        Route::get('/getcode', [UsersController::class, 'getcode'])->name('users.getcode');

        // Role management
        Route::get('/permission', [UserRoleController::class, 'PermissionByRole']);
        Route::post('/add', [UserRoleController::class, 'addRole']);
        Route::delete('/delr', [UserRoleController::class, 'deleteRole']);
        Route::delete('/delp', [UserRoleController::class, 'deletePermission']);
    });

    // Roles
    Route::prefix('roles')->middleware(['role:superadmin', 'global.app'])->group(function () {
        Route::get('/list', [UserRoleController::class, 'index'])->name('roles.list');
        Route::get('/permission', [UserRoleController::class, 'PermissionByRole']);
        Route::post('/add', [UserRoleController::class, 'addRole']);
        Route::delete('/delr', [UserRoleController::class, 'deleteRole']);
        Route::delete('/delp', [UserRoleController::class, 'deletePermission']);
        Route::post('/swcp', [UserRoleController::class, 'PermissionfromRole'])->name('roles.switch');
    });

    // Menu
    Route::prefix('menu')->middleware(['role:superadmin', 'global.app'])->group(function () {
        Route::get('/list', [MenuController::class, 'index'])->name('menu.list');
        Route::get('/data/{role}', [MenuController::class, 'datamenu'])->name('menu.data');
        Route::put('/update', [MenuController::class, 'update'])->name('menu.update');
    });

    Route::prefix('setting')->middleware(['role:superadmin|admin|keuangan|direktur|manager', 'global.app'])->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::prefix('lazismu')->name('lazismu.')->middleware(['role:superadmin|admin|operator|laporan|keuangan|direktur|manager', 'global.app'])->group(function () {
        Route::get('/dashboard', [LazismuDashboardController::class, 'index'])->name('dashboard');

        Route::get('/muzaki', [MuzakiController::class, 'index'])->name('muzaki.index');
        Route::post('/muzaki', [MuzakiController::class, 'store'])->name('muzaki.store');
        Route::put('/muzaki/{muzaki}', [MuzakiController::class, 'update'])->name('muzaki.update');
        Route::delete('/muzaki/{muzaki}', [MuzakiController::class, 'destroy'])->name('muzaki.destroy');
        Route::get('/muzaki/{muzaki}/barcode', [MuzakiController::class, 'barcode'])->name('muzaki.barcode');
        Route::get('/muzaki/{muzaki}/kartu', [MuzakiController::class, 'card'])->name('muzaki.card');

        Route::get('/program', [ProgramController::class, 'index'])->name('program.index');
        Route::post('/program', [ProgramController::class, 'store'])->name('program.store');
        Route::put('/program/{program}', [ProgramController::class, 'update'])->name('program.update');
        Route::delete('/program/{program}', [ProgramController::class, 'destroy'])->name('program.destroy');

        Route::get('/kode-setoran', [KodeSetoranController::class, 'index'])->name('kode-setoran.index');
        Route::post('/kode-setoran', [KodeSetoranController::class, 'store'])->name('kode-setoran.store');
        Route::put('/kode-setoran/{kodeSetoran}', [KodeSetoranController::class, 'update'])->name('kode-setoran.update');
        Route::delete('/kode-setoran/{kodeSetoran}', [KodeSetoranController::class, 'destroy'])->name('kode-setoran.destroy');

        Route::get('/kodetransaksi', [KodetransaksiController::class, 'index'])->name('kodetransaksi.index');
        Route::post('/kodetransaksi', [KodetransaksiController::class, 'store'])->name('kodetransaksi.store');
        Route::put('/kodetransaksi/{kodetransaksi}', [KodetransaksiController::class, 'update'])->name('kodetransaksi.update');
        Route::delete('/kodetransaksi/{kodetransaksi}', [KodetransaksiController::class, 'destroy'])->name('kodetransaksi.destroy');
        Route::post('/kodetransaksi/header', [KodetransaksiController::class, 'storeHeader'])->name('kodetransaksi.header.store');
        Route::put('/kodetransaksi/header/{header}', [KodetransaksiController::class, 'updateHeader'])->name('kodetransaksi.header.update');
        Route::delete('/kodetransaksi/header/{header}', [KodetransaksiController::class, 'destroyHeader'])->name('kodetransaksi.header.destroy');

        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::put('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
        Route::delete('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
        Route::post('/pengeluaran/editor-image', [PengeluaranController::class, 'uploadEditorImage'])->name('pengeluaran.editor-image');

        Route::get('/setoran', [SetoranController::class, 'index'])->name('setoran.index');
        Route::post('/setoran', [SetoranController::class, 'store'])->name('setoran.store');
        Route::get('/setoran/{setoran}/print', [SetoranController::class, 'print'])->name('setoran.print');
        Route::put('/setoran/{setoran}', [SetoranController::class, 'update'])->name('setoran.update');
        Route::delete('/setoran/{setoran}', [SetoranController::class, 'destroy'])->name('setoran.destroy');

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/cashflow', [LazismuReportController::class, 'cashflow'])->name('cashflow');
            Route::get('/program', [LazismuReportController::class, 'program'])->name('program');
            Route::get('/infaq', [LazismuReportController::class, 'infaq'])->name('infaq');
            Route::get('/zakat', [LazismuReportController::class, 'zakat'])->name('zakat');
        });
    });

    // Static file (private doc/img)
    Route::prefix('doc')->group(function () {
        Route::get('download/{filename}', function ($filename) {
            if (!Auth::check()) abort(403);
            $path = storage_path("app/private/doc/{$filename}");
            if (!file_exists($path)) abort(404);
            return Response::download($path);
        });
        Route::get('file/{path}/{filename}', function ($path, $filename) {
            if (!Auth::check()) abort(403);
            $path = storage_path("app/private/img/{$path}/{$filename}");
            if (!File::exists($path)) abort(404);
            $file = File::get($path);
            $type = File::mimeType($path);
            return Response::make($file, 200)->header("Content-Type", $type);
        });
    });

Route::middleware(['auth'])->group(function () {
    Route::get('slip/{payroll_id}', [PayrollController::class, 'downloadSlip'])->name('slip');
});

require __DIR__ . '/auth.php';

Route::get('/muzaki', [MuzakiAuthController::class, 'create'])->name('muzaki.login');
Route::post('/muzaki', [MuzakiAuthController::class, 'store'])->name('muzaki.login.store');
Route::get('/muzaki/register', [MuzakiAuthController::class, 'register'])->name('muzaki.register');
Route::post('/muzaki/register', [MuzakiAuthController::class, 'storeRegistration'])->name('muzaki.register.store');
Route::redirect('/muzaki/login', '/muzaki');
Route::post('/muzaki/logout', [MuzakiAuthController::class, 'destroy'])->name('muzaki.logout');
Route::middleware('muzaki.auth')->group(function () {
    Route::get('/muzaki/mobile', [MuzakiPortalController::class, 'index'])->name('muzaki.mobile');
    Route::get('/muzaki/profil', [MuzakiPortalController::class, 'profile'])->name('muzaki.profile');
    Route::put('/muzaki/profil', [MuzakiPortalController::class, 'updateProfile'])->name('muzaki.profile.update');
    Route::get('/muzaki/setoran', [MuzakiPortalController::class, 'setoranInfo'])->name('muzaki.setoran.info');
    Route::get('/muzaki/riwayat', [MuzakiPortalController::class, 'riwayat'])->name('muzaki.riwayat');
    Route::get('/muzaki/program/{program}', [MuzakiPortalController::class, 'programDetail'])->name('muzaki.program.detail');
    Route::get('/muzaki/riwayat/{jenis}', [MuzakiPortalController::class, 'riwayatDetail'])->name('muzaki.riwayat.detail');
    Route::redirect('/muzaki/portal', '/muzaki/mobile')->name('muzaki.portal');
});
