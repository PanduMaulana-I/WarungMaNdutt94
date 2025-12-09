<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['user_id','name','description','price','stock','image','is_available'];
    // relasi
    public function user() { return $this->belongsTo(User::class); }
}

