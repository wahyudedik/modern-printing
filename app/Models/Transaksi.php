<?php

namespace App\Models;

use App\Events\OrderProgressUpdated;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class Transaksi extends BaseModel
{
    protected $table = 'transaksis';

    protected $fillable = [
        'vendor_id',
        'kode',
        'user_id',
        'pelanggan_id',
        'total_harga',
        'status',
        'payment_method',
        'estimasi_selesai',
        'tanggal_dibuat',
        'progress_percentage',
        'current_stage'
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'status' => 'string',
        'tanggal_dibuat' => 'date'
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function transaksiItem()
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_id');
    }

    protected static function booted()
    {
        static::created(function ($transaksiItem) {
            $bahan = Bahan::find($transaksiItem->bahan_id);
            if ($bahan) {
                $bahan->decrement('stok', $transaksiItem->kuantitas);
            }
        });

        static::updated(function ($transaksi) {
            if ($transaksi->isDirty('status')) {
                Notification::make()
                    ->title('Order Status Updated')
                    ->body("Order #{$transaksi->kode} is now {$transaksi->status}")
                    ->icon('heroicon-o-shopping-bag')
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->url(route('pos.invoice', [
                                'tenant' => $transaksi->vendor->slug,
                                'transaksi' => $transaksi->id
                            ]))
                    ])
                    ->sendToDatabase($transaksi->pelanggan->user);
            }
        });
    }

    public function updateProgress($stage, $percentage)
    {
        $this->update([
            'current_stage' => $stage,
            'progress_percentage' => $percentage
        ]);

        // Trigger notification
        event(new OrderProgressUpdated($this));
    }

    public function getProgressStages()
    {
        return [
            'pending' => 'Order Received',
            'processing' => 'In Production',
            'quality_check' => 'Quality Check',
            'completed' => 'Ready for Pickup'
        ];
    }
}
