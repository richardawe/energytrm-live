<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = [
        'name', 'long_name', 'business_unit_id', 'type',
        'currency_id', 'is_restricted', 'requires_strategy',
        'linked_portfolio_id', 'status', 'version',
    ];
    protected $casts = [
        'is_restricted'    => 'boolean',
        'requires_strategy'=> 'boolean',
    ];

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'business_unit_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function linkedPortfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class, 'linked_portfolio_id');
    }
}
