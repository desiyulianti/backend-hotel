<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $table = 'booking';
    public $timestamps = false;
    public $timestamp = true;
    public $primaryKey = 'id_booking';

    protected $fillable = ['booking_number', 'name_customer', 'email_customer', 'booking_date', 'check_in_date', 'check_out_date', 'guest_name', 'room_quantity', 'id_type_room', 'status_booking', 'id_user'];

    public function type_room()
    {
        return $this->belongsTo('App\Models\Type_Room', 'id_type_room', 'id_type_room');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'is_user');
    }

     // filtering data booking berdasarkan nama  

    public static function searchByName($name)
    {
        return self::where('guest_name', 'like', '%' . $name . '%')->get();
    }


    use HasFactory;
}
