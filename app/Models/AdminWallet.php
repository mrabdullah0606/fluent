<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminWallet extends Model
{
    protected $fillable = [
        'admin_id',
        'balance',
        'total_earned',
        'total_withdrawn',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function transactions()
    {
        return $this->hasMany(AdminWalletTransaction::class, 'admin_id', 'admin_id');
    }

    /**
     * Get the main admin wallet (assumes admin_id = 1 or first admin)
     */
    public static function getMainAdminWallet()
    {
        // You can modify this logic based on how you identify the main admin
        // For now, assuming the first admin user or admin_id = 1
        $adminUser = User::where('role', 'admin')->first();

        if (!$adminUser) {
            return null;
        }

        return self::firstOrCreate(
            ['admin_id' => $adminUser->id],
            [
                'balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
            ]
        );
    }
}
