<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\User\Providers;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\View\Compilers\BladeCompiler;
use Laravel\Passport\Passport;
use Livewire\Livewire;
use MicroweberPackages\Billing\Providers\BillingFilamentPluginServiceProvider;
use MicroweberPackages\User\Http\Livewire\Admin\CreateProfileInformationForm;
use MicroweberPackages\User\Http\Livewire\Admin\DeleteUserForm;
use MicroweberPackages\User\Http\Livewire\Admin\EditUserModal;
use MicroweberPackages\User\Http\Livewire\Admin\UpdatePasswordForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdatePasswordWithoutConfirmForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdateProfileInformationForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdateStatusAndRoleForm;
use MicroweberPackages\User\Http\Livewire\Admin\UserLoginAttemptsModal;
use MicroweberPackages\User\Http\Livewire\Admin\UsersList;
use MicroweberPackages\User\Http\Livewire\Admin\UserTosLogModal;
use MicroweberPackages\User\Http\Livewire\LogoutOtherBrowserSessionsForm;
use MicroweberPackages\User\Http\Livewire\TwoFactorAuthenticationForm;
use MicroweberPackages\User\Services\RSAKeys;
use MicroweberPackages\User\UserManager;



class UserServiceProvider extends AuthServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__. '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__. '/../migrations/');
        $this->loadViewsFrom( __DIR__ . '/../resources/views/components', 'user');

        View::addNamespace('user', __DIR__ . '/../resources/views');
        View::addNamespace('admin', __DIR__ . '/../resources/views/admin');

        Livewire::component('admin::users-list', UsersList::class);
        Livewire::component('admin::users.create-profile-information-form', CreateProfileInformationForm::class);
        Livewire::component('admin::edit-user.update-profile-information-form', UpdateProfileInformationForm::class);
        Livewire::component('admin::edit-user.update-status-and-role-form', UpdateStatusAndRoleForm::class);
        Livewire::component('admin::edit-user.update-password-form', UpdatePasswordForm::class);
        Livewire::component('admin::edit-user.update-password-without-confirm-form', UpdatePasswordWithoutConfirmForm::class);
        Livewire::component('admin::edit-user.two-factor-authentication-form', \MicroweberPackages\User\Http\Livewire\Admin\TwoFactorAuthenticationForm::class);
        Livewire::component('admin::edit-user.logout-other-browser-sessions-form', \MicroweberPackages\User\Http\Livewire\Admin\LogoutOtherBrowserSessionsForm::class);
        Livewire::component('admin::edit-user.delete-user-form', DeleteUserForm::class);

        Livewire::component('admin::user-tos-log', UserTosLogModal::class);
        Livewire::component('admin::user-login-attempts', UserLoginAttemptsModal::class);

        Livewire::component('user::profile.two-factor-authentication-form', TwoFactorAuthenticationForm::class);
        Livewire::component('user::profile.logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);

    }

    public function boot()
    {

        /**
         * @property \MicroweberPackages\User\UserManager $user_manager
         */

        [$publicKey, $privateKey] = [
            storage_path('oauth-public.key'),
            storage_path('oauth-private.key'),
        ];

        $need_to_generate_keys = false;
        if (!is_file($publicKey) or !is_file($privateKey)) {
            $need_to_generate_keys = true;
        }
        if ($need_to_generate_keys) {
            $keys = RSAKeys::createKey( 4096);
            file_put_contents($publicKey, \Arr::get($keys, 'publickey'));
            file_put_contents($privateKey, \Arr::get($keys, 'privatekey'));
        }

        $this->app->register(\Laravel\Passport\PassportServiceProvider::class);
        $this->app->register(\Laravel\Sanctum\SanctumServiceProvider::class);

        Passport::ignoreMigrations();

        $this->app->singleton('user_manager', function ($app) {
            return new UserManager();
        });

        // Register Validators
        Validator::extendImplicit(
            'terms',
            'MicroweberPackages\User\Validators\TermsValidator@validate',
            'Terms are not accepted');
        Validator::extendImplicit(
            'temporary_email_check',
            'MicroweberPackages\User\Validators\TemporaryEmailCheckValidator@validate',
            'You cannot register with email from this domain.');

    }
}
