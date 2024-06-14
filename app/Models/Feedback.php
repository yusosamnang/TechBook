<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'review_id',
        'content',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    public function scopeApproved($query)
    {
        return $query->where('status', ['pending', 'approved']);
    }
}
