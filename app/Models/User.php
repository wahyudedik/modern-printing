<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_image',
        'name',
        'email',
        'password',
        'email_verified_at',
        'usertype',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->usertype === 'admin';
        }

        if ($panel->getId() === 'app') {
            return $this->usertype === 'user' || $this->usertype === 'admin' || $this->usertype === 'staff';
        }

        return false;
    }

    public function vendor(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->vendor;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->vendor()->whereKey($tenant)->exists();
    }

    public function vendorActivities()
    {
        return $this->hasMany(VendorActivity::class, 'user_id');
    }
}
