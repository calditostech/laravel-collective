<?php

namespace App\Services;

use App\Product;

class PriceService
{
    private $prices;
    private $categories;

    public function getPrices($prices, $categories)
    {
        $this->prices = $prices;
        $this->categories = $categories;
        $formattedPrices = [];

        foreach(Product::PRICES as $index => $name) {
            $formattedPrices[] = [
                'name' => $name,
                'products_count' => $this->getProductCount($index)
            ];
        }

        return $formattedPrices;
    }

    private function getProductCount($index)
    {
        return Product::withFilters($this->prices, $this->categories)
            ->when($index == 0, function ($query) {
                $query->where('price', '<', '20,00');
            })
            ->when($index == 1, function ($query) {
                $query->whereBetween('price', ['20,00', '40,00']);
            })
            ->when($index == 2, function ($query) {
                $query->whereBetween('price', ['40,00', '80,00']);
            })
            ->when($index == 3, function ($query) {
                $query->where('price', '>', '100,00');
            })
            ->count();
    }
}
