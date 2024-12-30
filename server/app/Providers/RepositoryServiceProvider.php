<?php

namespace App\Providers;

use App\Repositories\MessagesRepository;
use App\Repositories\RepositoryInterface;
use App\Repositories\UsersRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    /** @var string[] */
    private const REPOSITORIES = [
        UsersRepository::class,
        MessagesRepository::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        /** @var RepositoryInterface $repository */
        foreach (self::REPOSITORIES as $repository) {
            $this->app->singleton($repository, static fn (): RepositoryInterface => $repository::make());
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
