<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\RoleEnum;

class Course extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable  = ['name','description','user_id','team_id'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team():BelongsTo
    {
        return $this->belongsTo(Team::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('teamAndUserFilter', function (Builder $builder) {
            $user = auth()->user();

            if (!$user->hasRole(RoleEnum::SUPER_ADMIN)) {
                $builder->where('team_id', $user->team_id);

                if (!$user->hasRole(RoleEnum::TENANT_ADMIN)) {
                    $builder->where('user_id', $user->id);
                }
            }
        });
    }
}
