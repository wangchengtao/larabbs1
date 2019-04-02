<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder(Builder $query, $order)
    {
        // 不同的排序, 使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }

        return $query->with('user', 'category');
    }

    public function scopeRecent($builder)
    {
        return $builder->orderBy('created_at', 'desc');
    }

    public function scopeRecentReplied(Builder $builder)
    {
        return $builder->orderBy('updated_at', 'desc');
    }
}
