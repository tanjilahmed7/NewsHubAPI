<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    /**
     * The Article model represents an article in the NewsHubAPI application.
     *
     * @var array $fillable The attributes that are mass assignable.
     */
    protected $fillable = [
        'source_id',
        'source_name',
        'author',
        'title',
        'description',
        'url',
        'url_to_image',
        'published_at',
        'content',

    ];

    /**
     * Scope a query to search for articles by keyword.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('content', 'LIKE', "%{$keyword}%");
            });
        }
        return $query;
    }


    /**
     * Scope a query to filter articles by source.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $source
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterBySource($query, $source)
    {
        if ($source) {
            return $query->where('source_name', $source);
        }
        return $query;
    }

    /**
     * Filter the query by a date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $from
     * @param  string  $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByDateRange($query, $from, $to)
    {
        if ($from) {
            $query->where('published_at', '>=', $from);
        }
        if ($to) {
            $query->where('published_at', '<=', $to);
        }
        return $query;
    }
}
