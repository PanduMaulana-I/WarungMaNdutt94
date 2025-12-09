<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi (mass assignable)
     */
    protected $fillable = [
    'queue_number',
    'order_date',
    'table_number',
    'menu_id',
    'user_id',
    'order_number',
    'customer_name',
    'customer_phone',
    'total_price',
    'payment_method',
    'payment_proof',   // <--- tambahin ini
    'status',
    'details',
];


    /**
     * Relasi ke user (pembeli)
     * Setiap order dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke order items (jika ada tabel order_items)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Helper — format harga otomatis
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Helper — ubah status jadi label friendly
     */
    public function getStatusLabelAttribute()
    {
        return match (strtolower($this->status)) {
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Helper — format tanggal otomatis
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at
            ? $this->created_at->format('d M Y, H:i')
            : '-';
    }

    /**
     * Helper — ambil metode pembayaran friendly
     */
    public function getPaymentLabelAttribute()
    {
        return match (strtolower($this->payment_method)) {
            'qris' => 'QRIS',
            'transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            default => ucfirst($this->payment_method ?? 'Tidak Diketahui'),
        };
    }
    public function menu()
{
    return $this->belongsTo(\App\Models\Menu::class, 'menu_id');
}

}
