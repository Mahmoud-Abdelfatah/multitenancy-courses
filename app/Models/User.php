<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Model;
use Filament\Panel;
use Illuminate\Support\Collection;
use Filament\Models\Contracts\HasDefaultTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements  HasTenants,HasDefaultTenant
{
    use HasApiTokens, HasFactory, Notifiable ,SoftDeletes,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'team_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function team():BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function courses():HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function getTenants(Panel $panel): array|Collection
    {

        return [];
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->team;
    }
 
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->team_id === $tenant->id ;
    }


    protected static function booted()
    {
        $user = auth()->user();
        
        static::addGlobalScope('teamScope', function (Builder $builder) use ($user){

                if ($user && !$user->hasRole(RoleEnum::SUPER_ADMIN)) {
                    $builder->where('team_id', $user->team_id);
                }
        });
    }

}
