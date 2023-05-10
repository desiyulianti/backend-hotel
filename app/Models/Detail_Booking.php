<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Booking extends Model
{
    protected $table = 'detail_booking';
    public $timestamps = false;
    public $primaryKey = 'id_detail_booking';
    protected $fillable = ['id_booking', 'id_room','access_date','price'];

    public function booking() {
        return $this->belongsTo('App\Models\Booking','id_booking','id_booking');
    }

    public function room() {
        return $this->belongsTo('App\Models\Room','id_room','id_room');
    }
    use HasFactory;
}
