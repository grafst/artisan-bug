<?php

namespace Grafst\ArtisanBug;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Grafst\ArtisanBug\Commands\ArtisanBugCommand;

class ArtisanBugServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('artisan-bug')
            ->hasCommand(ArtisanBugCommand::class);
    }
}
