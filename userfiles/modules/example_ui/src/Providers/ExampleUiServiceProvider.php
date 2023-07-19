<?php

namespace MicroweberPackages\Modules\ExampleUi\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Modules\ExampleUi\Http\Livewire\ExampleUiSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class ExampleUiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-example-ui');
        $package->hasViews('microweber-module-example-ui');
    }


    public function register(): void
    {
        parent::register();

       // View::addNamespace('microweber-module-example-ui', __DIR__.'/../resources/views');
        Livewire::component('microweber-module-example-ui::settings', ExampleUiSettingsComponent::class);

    }
}