<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table="transfer_details";
    protected $fillable = [
        "transfer_id",
        "product_id",
        "quantity",
        "price",
        "total",
        "created_by",
    ];

    /**
     * get transfer   
     */

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    /**
     * get transfer details with product relation.
     */
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * get transfer details with user relation.
     */

    public function user()
    {
        return $this->belongsTo(User::class, "created_by");
    }


}
