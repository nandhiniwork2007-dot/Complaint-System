<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ComplaintMessage extends Model
{
    /** @use HasFactory<\Database\Factories\ComplaintMessageFactory> */
=======
use Database\Factories\ComplaintMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintMessage extends Model
{
    /** @use HasFactory<ComplaintMessageFactory> */
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'is_internal',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
        ];
    }

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
