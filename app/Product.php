<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'price', 'available'];

    const PRICES = [
        'Less than 20',
        'From 20 to 40',
        'From 40 to 80',
        'More than 100',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeWithFilters($query, $prices, $categories)
    {
        return $query->when(count($categories), function ($query) use ($categories) {
               $query->whereIn('category_id', $categories);
            })
            ->when(count($prices), function ($query) use ($prices){
                $query->where(function ($query) use ($prices) {
                    $query->when(in_array(0, $prices), function ($query) {
                            $query->orWhere('price', '<', '20');
                        })
                        ->when(in_array(1, $prices), function ($query) {
                            $query->orWhereBetween('price', ['20', '40']);
                        })
                        ->when(in_array(2, $prices), function ($query) {
                            $query->orWhereBetween('price', ['40', '80']);
                        })
                        ->when(in_array(3, $prices), function ($query) {
                            $query->orWhere('price', '>', '100');
                        });
                });
            });
    }
}
