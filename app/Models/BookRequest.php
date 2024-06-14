<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_title', 'author_name', 'publish_date', 'url', 'reason', 'status', 'review_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}