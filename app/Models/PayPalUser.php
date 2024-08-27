<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayPalUser extends Model
{
    use HasFactory;

    protected $table = 'paypal_user';
    protected $fillable = ['user_id', 'address', 'amount', 'payment_id', 'status', 'create_time'];

    public function getAddressAttribute($value)
    {
        $address = $this->attributes['address'] = json_decode($value);
        $strAddress = '';
        foreach ($address as $a) {
            $strAddress .=' '.$a;
        }
        return $strAddress;
    }

    public function getCreateTimeAttribute($value)
    {
        return $this->attributes['create_time'] = Carbon::parse($value)->addMonths(12)->format('Y-m-d H:i');
    }

    public function user()
    {
        return $this->belongTo(User::class);
    }
}
