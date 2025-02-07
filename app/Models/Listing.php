<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    /** @use HasFactory<\Database\Factories\ListingFactory> */
    use HasFactory;

    protected $fillable = [
        "title",
        "desc",
        "tags",
        "email",
        "link",
        "image",
        "approved"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    // Scope function to apply query filters dynamically
    // Naming convention is important: it should start with 'scope' (e.g., scopeFilter)
    public function scopeFilter($query, array $filters) {

        // Filter by search keyword (applies to 'title' and 'desc' columns)
        if ($filters['search'] ?? false) {
            $query->where(function ($q) { // Use a closure to group conditions correctly
                $q->where('title', 'like', '%' . request('search') . '%') // Search in title
                ->orWhere('desc', 'like', '%' . request('search') . '%'); // Search in description
            });
        }

        // Filter by user (only return records that belong to the specified user)
        if ($filters['user_id'] ?? false) {
            $query->where('user_id', request('user_id'));
        }
    }

}
