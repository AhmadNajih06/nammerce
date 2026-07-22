<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'order_date',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'integer',
            'order_date'   => 'datetime',
        ];
    }

    /** Order milik satu User. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order berisi banyak Product (M-N via order_items).
     * Akses pivot: $order->products->first()->pivot->quantity
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }

    /** Akses langsung ke rows order_items (untuk eager load & subtotal). */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Format total ke Rupiah. */
    public function formattedTotal(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /** Label status dalam Bahasa Indonesia. */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'    => 'Menunggu',
            'processing' => 'Diproses',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => ucfirst($this->status),
        };
    }

    /** Nama warna Tailwind untuk badge status. */
    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'    => 'yellow',
            'processing' => 'blue',
            'completed'  => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }
}
