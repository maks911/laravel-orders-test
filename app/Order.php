<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{

    protected $fillable = [
        'client_email', 'status', 'partner'
    ];

    protected static $statuses = [
        0 => 'New',
        10 => 'Confirmed',
        20 => 'Completed'
    ];

    public $total;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'price');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * @param string $status
     * @return string
     */
    public function switchStatus(string $status)
    {
        return self::$statuses[$status] ?? '';
    }

    /**
     * @return string[]
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * @param $data
     * @return bool
     */
    public function updateOrder($data): bool
    {
        DB::beginTransaction();
        try {
            $order = $this->find($data['id']);
            $partner = Partner::find($data['partner']);
            $order->client_email = $data['client_email'];
            $order->partner()->associate($partner);
            $order->status = $data['status'];
            $order->save();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return false;
        }
        return true;
    }

    public function getEmails(): array
    {
        $emails = [$this->client_email];
        foreach ($this->products as $product) {
            $emails[] = $product->vendor->email;
        }
        return $emails;
    }

    public function calculateTotal(): void
    {
        foreach ($this->products as $product) {
            $this->total += $product->pivot->quantity * $product->pivot->price;
        }
    }
}
