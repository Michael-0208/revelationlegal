<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table="permissions";
    protected $fillable = [
        'id','name','user_id','value','updated_at','created_at'
    ];
    use HasFactory;
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
