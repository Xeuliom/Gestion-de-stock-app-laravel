<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'))->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

    // Produits
    Route::get('produits', \App\Livewire\Produits\Index::class)->name('produits.index');
    Route::get('produits/nouveau', \App\Livewire\Produits\Form::class)->name('produits.create')->middleware('role:admin');
    Route::get('produits/{id}/modifier', \App\Livewire\Produits\Form::class)->name('produits.edit')->middleware('role:admin');

    // Stock
    Route::get('stock/entree', \App\Livewire\Stock\Entree::class)->name('stock.entree');
    Route::get('stock/sortie', \App\Livewire\Stock\Sortie::class)->name('stock.sortie');
    Route::get('stock/consultation', \App\Livewire\Stock\Consultation::class)->name('stock.consultation');

    // Admin uniquement
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Utilisateurs
        Route::get('utilisateurs', \App\Livewire\Admin\Utilisateurs\Index::class)->name('admin.utilisateurs.index');
        Route::get('utilisateurs/nouveau', \App\Livewire\Admin\Utilisateurs\Form::class)->name('admin.utilisateurs.create');
        Route::get('utilisateurs/{id}/modifier', \App\Livewire\Admin\Utilisateurs\Form::class)->name('admin.utilisateurs.edit');

        // Catégories
        Route::get('categories', \App\Livewire\Admin\Categories\Index::class)->name('admin.categories.index');
        Route::get('categories/nouvelle', \App\Livewire\Admin\Categories\Form::class)->name('admin.categories.create');
        Route::get('categories/{id}/modifier', \App\Livewire\Admin\Categories\Form::class)->name('admin.categories.edit');

        // Fournisseurs
        Route::get('fournisseurs', \App\Livewire\Admin\Fournisseurs\Index::class)->name('admin.fournisseurs.index');
        Route::get('fournisseurs/nouveau', \App\Livewire\Admin\Fournisseurs\Form::class)->name('admin.fournisseurs.create');
        Route::get('fournisseurs/{id}/modifier', \App\Livewire\Admin\Fournisseurs\Form::class)->name('admin.fournisseurs.edit');

        // Rapports
        Route::get('rapports/entrees', \App\Livewire\Rapports\Entrees::class)->name('admin.rapports.entrees');
        Route::get('rapports/sorties', \App\Livewire\Rapports\Sorties::class)->name('admin.rapports.sorties');
        Route::get('rapports/stock-actuel', \App\Livewire\Rapports\StockActuel::class)->name('admin.rapports.stock-actuel');
        Route::get('rapports/rupture-stock', \App\Livewire\Rapports\RuptureStock::class)->name('admin.rapports.rupture-stock');
    });
});

require __DIR__.'/settings.php';
