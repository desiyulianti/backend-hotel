<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_Room extends Model
{

    protected $table = 'type_room';
    public $timestamps = false;
    public $primaryKey = 'id_type_room';

    protected $fillable = ['type_room_name', 'price', 'desc', 'image'];
    use HasFactory;
}
