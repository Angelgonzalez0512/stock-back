<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'
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
    ];

    /**
     * get categories paginated or not.
     *
     * @var array<string, string>
     */
    public function scopePaginateOrNot($query, $paginate = true, $per_page = 10)
    {
        if ($paginate) {
            return $query->paginate($per_page);
        } else {
            return $query->get();
        }
    }

    /**
     * filter categories by name.
     *
     * @var array<string, string>
     */

    public function scopeByTerm($query, $term)
    {
        return $query->where('name', 'like', "%$term%");
    }

    /**
     * get products associated.
     *
     * @var array<string, string>
     */

    public function products()
    {
        return $this->hasMany(Product::class, "created_by");
    }

    /**
     * get categories associated.
     *
     * @var array<string, string>
     */

    public function categories()
    {
        return $this->hasMany(Category::class, "created_by");
    }

    /**
     * get transfers associated.
     *
     * @var array<string, string>
     */

    public function transfers()
    {
        return $this->hasMany(Transfer::class, "created_by");
    }

    /**
     * get transfers details associated.
     *
     * @var array<string, string>
     */

    public function transfer_details()
    {
        return $this->hasMany(TransferDetail::class, "created_by");
    }
}
