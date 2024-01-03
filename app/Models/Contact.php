<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $table = "contact";
    protected $primaryKey = 'id';
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    /**
     * Get the user that owns the Contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'user_id', 'id');
    }

    /**
     * Get all of the adresses for the Contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adresses(): HasMany
    {
        return $this->hasMany(Address::class, 'contact_id', 'id');
    }
}
