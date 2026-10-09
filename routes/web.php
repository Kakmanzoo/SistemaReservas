<?php

use App\Livewire\PortalReservas;

Route::middleware('auth')->get('/portal', PortalReservas::class)->name('portal');
