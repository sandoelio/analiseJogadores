<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Models\Aluno;
use App\Policies\AlunoPolicy;

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
        // força HTTPS em produção
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // mapeia o model Aluno para a sua policy
        Gate::policy(Aluno::class, AlunoPolicy::class);
    }
}

