<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Vendor;
use Filament\PanelProvider;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->brandName('Modern Printing')
            // ->brandLogo(asset('home/assets/img/1-removebg.png'),)
            ->brandLogoHeight('2rem')
            ->favicon(asset('img/favicon.png'))
            ->profile()
            ->colors([
                'primary' => Color::Red,
            ])
            ->login()
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 1,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                \Hasnayeen\Themes\ThemesPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenant(Vendor::class, ownershipRelationship: 'vendor', slugAttribute: 'slug')
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->tenantMenuItems([
                'register' => MenuItem::make()
                    ->label('Register new vendor')
                    ->visible(fn() => Auth::user()->usertype === 'user' ||  Auth::user()->usertype === 'admin'),
                'profile' => MenuItem::make()
                    ->label('Edit vendor profile')
                    ->visible(fn() => Auth::user()->usertype === 'user' ||  Auth::user()->usertype === 'admin'),
                // MenuItem::make()
                //     ->label('Point of Sale')
                //     ->icon('heroicon-o-shopping-cart')
                //     ->url(fn() => '/app/' . Filament::getTenant()->slug . '/pos')
                //     ->openUrlInNewTab(),
                MenuItem::make()
                    ->label('Point of Sale')
                    ->icon('heroicon-o-shopping-cart')
                    ->url(fn() => route('pos.index', ['tenant' => Filament::getTenant()?->slug]))
                    ->visible(fn() => Filament::getTenant() !== null)
                    ->openUrlInNewTab()
            ])
            ->tenantMiddleware([
                // Resource::scopeToTenant(true),
                ApplyTenantScopes::class,
                \BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ], isPersistent: true)
            ->navigationGroups([
                'Pengguna',
                'Bahan dan Alat',
                'Produk',
                'Pelanggan',
                'Transaksi',
                'Laporan',
            ]);
    }
}
