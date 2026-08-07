<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';

    protected $guarded = [];
    
    public function transaction()
    {
        return $this->belongsTo(
            Transaction::class
        );
    }
}