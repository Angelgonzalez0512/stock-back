<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table="transfers";   
    protected $fillable = [
        "supplier",
        "code",
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
            return $query->where("operation", "like", "%$search%")
                ->orWhere("notes", "like", "%$search%")
                ->orWhere("created_by", "like", "%$search%");
        }
    }

    /**
     * filter transfers by user.
     *
     * @var array<string, string>
     */
    public function scopeByUser($query, $user=null){
        if($user){
            return $query->where("created_by", $user);
        }
        return $query;
    }
}
