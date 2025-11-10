<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class InvoiceItem
 *
 * @version February 24, 2020, 5:57 am UTC
 *
 * @property int $id
 * @property int $charge_id
 * @property int $invoice_id
 * @property string $description
 * @property int $quantity
 * @property float $price
 * @property float $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|InvoiceItem newModelQuery()
 * @method static Builder|InvoiceItem newQuery()
 * @method static Builder|InvoiceItem query()
 * @method static Builder|InvoiceItem whereAccountId($value)
 * @method static Builder|InvoiceItem whereCreatedAt($value)
 * @method static Builder|InvoiceItem whereDescription($value)
 * @method static Builder|InvoiceItem whereId($value)
 * @method static Builder|InvoiceItem whereInvoiceId($value)
 * @method static Builder|InvoiceItem wherePrice($value)
 * @method static Builder|InvoiceItem whereQuantity($value)
 * @method static Builder|InvoiceItem whereTotal($value)
 * @method static Builder|InvoiceItem whereUpdatedAt($value)
 *
 * @mixin Model
 *
 * @property-read Account $account
 * @property int $is_default
 *
 * @method static Builder|InvoiceItem whereIsDefault($value)
 */
class InvoiceItem extends Model
{
    public static $rules = [
        'charge_id' => 'required|integer',
        'quantity' => 'required|integer',
        'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        'description' => 'nullable|string',
    ];

    public $table = 'invoice_items';

    public $fillable = [
        'charge_id',
        'description',
        'quantity',
        'price',
        'total',
    ];
    
    protected $casts = [
        'id' => 'integer',
        'charge_id' => 'integer',
        'description' => 'string',
        'quantity' => 'integer',
        'price' => 'double',
        'total' => 'double',
    ];

    // public function account(): BelongsTo
    // {
    //     return $this->belongsTo(Account::class);
    // }
    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class);
    }
}
