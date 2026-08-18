<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\PengaturanLab;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Share pengaturan lab dinamis ke seluruh view
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('pengaturan_labs')) {
                    $view->with('pengaturan', PengaturanLab::getPengaturan());
                }
            } catch (\Exception $e) {
                // Fallback jika database belum siap
            }
        });
    }
}
