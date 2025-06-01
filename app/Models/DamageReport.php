<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    public const SEVERITY_RINGAN = 'ringan';
    public const SEVERITY_SEDANG = 'sedang';
    public const SEVERITY_BERAT = 'berat';

    public static $allowedSeverities = [
        self::SEVERITY_RINGAN,
        self::SEVERITY_SEDANG,
        self::SEVERITY_BERAT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'reported_by_user_id',
        'description',
        'severity', // <-- TAMBAHKAN INI
        'status',
        'admin_notes',
        'image_damage',
        'reported_at',
        'resolved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }
}