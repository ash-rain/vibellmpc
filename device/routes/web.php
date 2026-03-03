<?php

use App\Livewire\Dashboard\ApiPanel;
use App\Livewire\Dashboard\ChatPanel;
use App\Livewire\Dashboard\ModelManager;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Dashboard\SystemSettings;
use App\Livewire\Dashboard\TunnelManager;
use App\Livewire\Dashboard\WorkflowsPanel;
use App\Livewire\Pairing\PairingScreen;
use App\Livewire\TunnelLogin;
use App\Livewire\Wizard\WizardController;
use App\Services\DeviceStateService;
use Illuminate\Support\Facades\Route;

Route::get('/', function (DeviceStateService $stateService) {
    return match ($stateService->getMode()) {
        DeviceStateService::MODE_PAIRING => redirect()->route('pairing'),
        DeviceStateService::MODE_WIZARD => redirect()->route('wizard'),
        DeviceStateService::MODE_DASHBOARD => redirect()->route('dashboard'),
        default => redirect()->route('pairing'),
    };
})->name('home');

// Tunnel authentication gate (password prompt when accessing via tunnel)
Route::get('/tunnel/login', TunnelLogin::class)->name('tunnel.login');

// Pairing screen
Route::get('/pairing', PairingScreen::class)->name('pairing');

// Setup wizard
Route::get('/wizard', WizardController::class)->name('wizard');

// Dashboard — protected by tunnel auth (local access passes through freely)
Route::middleware('tunnel.auth')->group(function () {
    Route::get('/dashboard', Overview::class)->name('dashboard');
    Route::get('/dashboard/models', ModelManager::class)->name('dashboard.models');
    Route::get('/dashboard/chat', ChatPanel::class)->name('dashboard.chat');
    Route::get('/dashboard/workflows', WorkflowsPanel::class)->name('dashboard.workflows');
    Route::get('/dashboard/api', ApiPanel::class)->name('dashboard.api');
    Route::get('/dashboard/tunnels', TunnelManager::class)->name('dashboard.tunnels');
    Route::get('/dashboard/settings', SystemSettings::class)->name('dashboard.settings');
});
