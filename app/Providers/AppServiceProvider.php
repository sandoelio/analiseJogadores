<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

use App\Models\Aluno;
use App\Policies\AlunoPolicy;
use App\Models\User; 

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

        // define o Gate "tecnico-admin"
        Gate::define('tecnico-admin', function (User $user) {
            // ajuste 'role' pro nome do campo que vc usa
            return in_array($user->role, ['admin', 'tecnico']);
        });
    }
}
