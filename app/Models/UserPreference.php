<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPreference extends Model
{
    use HasFactory;

    /**
     * The UserPreference model represents a user's preference in the application.
     *
     * @var array $fillable The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'sources',
        'categories',
        'authors',
    ];

    /**
     * The UserPreference model.
     *
     * @property int $id
     * @property int $user_id
     * @property string $preference
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     */
    protected $casts = [
        'sources' => 'array',
        'categories' => 'array',
        'authors' => 'array',
    ];
}
