<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'description'
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
     * get products associated.
     *
     * @var array<string, string>
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
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
}
