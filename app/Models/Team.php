<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Builder;

class Team extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable  = ['name','slug'];

    public function members():HasMany
    {
        
        return $this->hasMany(User::class, 'team_id');
    }

    public function courses():HasMany
    {
        return $this->hasMany(Course::class, 'team_id');
    }

}
