<?php

namespace App\Models;
use App\Models\Product;
use App\Models\Buyer;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    public $transformer = TransactionTransformer::class;

    protected $dates = ['deleted_at'];

    protected $fillable =[
        'quantity',
        'buyer_id',
        'product_id'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function buyer() {
        return $this->belongsTo(Buyer::class);
    }
}
