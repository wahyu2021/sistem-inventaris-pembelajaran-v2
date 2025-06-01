<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'unique_code',
        'quantity',
        'condition',
        'image',
        'location',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }
}
