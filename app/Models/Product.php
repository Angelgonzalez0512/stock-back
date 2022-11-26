<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = "products";
    protected $fillable = [
        "name",
        "barcode",
        "price",
        "presentation_quantity",
        "presentation",
        "stock",
        "min_stock",
        "max_stock",
        "brand",
        "category_id",
        "unit",
        "created_by",
        "description",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function transfer_details()
    {
        return $this->hasMany(TransferDetail::class,"product_id");
    }

    /**
     * get products paginated or not.
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
     * filter products by name.
     *
     * @var array<string, string>
     */

    public function scopeByTerm($query, $term)
    {
        return $query->where('name', 'like', "%$term%");
    }

    /**
     * filter products by category.
     *
     * @var array<string, string>
     */

    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category_id', $category);
        }
    }
}
