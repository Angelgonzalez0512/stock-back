<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    public $table="transfers";
    
    protected $fillable = [
        "supplier",
        "code",
        "total",
        "tax",
        "discount",
        "discount_type",
        "operation",
        "notes",
        "created_by",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function transfer_details()
    {
        return $this->hasMany(TransferDetail::class, "transfer_id");
    }

    /**
     * get transfers paginated or not.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $paginate
     * @param  int  $per_page
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopePaginateOrNot($query, $paginate, $per_page)
    {
        if($paginate){
            return $query->paginate($per_page);
        }else{
            return $query->get();
        }
    }

    /**
     * search transfers by term.
     *
     * @var array<string, string>
     */
    public function scopeSearch($query, $search)
    {
        if($search){
            return $query->where("total", "like", "%$search%")
                ->orWhere("tax", "like", "%$search%")
                ->orWhere("discount", "like", "%$search%")
                ->orWhere("discount_type", "like", "%$search%")
                ->orWhere("operation", "like", "%$search%")
                ->orWhere("notes", "like", "%$search%")
                ->orWhere("created_by", "like", "%$search%");
        }
    }
}
