<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';
    public $timestamps = false;
    public $primaryKey = 'id_room';
    protected $fillable = ['number_room', 'id_type_room'];

    public function type_room() {
        return $this->belongsTo('App\Models\Type_Room','id_type_room','id_type_room');
    }

    use HasFactory;
}
