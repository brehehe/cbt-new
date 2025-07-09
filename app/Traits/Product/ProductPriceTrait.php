<?php

namespace App\Traits\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Traits\Branch\BranchTrait;
use Illuminate\Support\Facades\Auth;

trait ProductPriceTrait
{
    use BranchTrait;
    //
    public function getProductPrices()
    {
        return ProductPrice::search(trim($this->search))->with('product:id,name,sku_number,product_type_id', 'branch:id,name', 'company:id,name', 'product.productType:id,name')
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $this->getBranchOne()->id)
            ->orderBy('order', 'desc')
            ->where('is_updated', false);
    }

    public function getProductPriceUpdates()
    {
        return ProductPrice::search(trim($this->search))->with('product:id,name,sku_number,product_type_id', 'branch:id,name', 'company:id,name', 'product.productType:id,name')
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $this->getBranchOne()->id)
            ->orderBy('order', 'desc')
            ->where('is_updated', true);
    }

    public function generatePrice()
    {
        $productPrices = $this->getProductPrices()->get();

        foreach ($productPrices as $productPrice) {
            $productPrice->update([
                'is_updated' => false,
                'price_generate' => $this->calculatePrice($productPrice),
                'recipe_generate' => $this->calculateRecipe($productPrice),
            ]);
        }
    }

    public function generateFixedPrice()
    {
        $productPrices = $this->getProductPrices()->get();

        foreach ($productPrices as $productPrice) {
            $productPrice->update([
                'is_updated' => true,
                'price' => $productPrice->price_generate,
                'recipe' => $productPrice->recipe_generate,
            ]);
        }
    }

    public function calculatePrice($productPrice)
    {
        $product = Product::find($productPrice->product_id);

        $normal = $product ? ($product->normal > 0 ? $product->normal : ($product->productCategory?->normal ?? 0)) : 0;
        return $productPrice->hpp_average + ($productPrice->hpp_average * $normal / 100);
    }

    public function calculateRecipe($productPrice)
    {
        $product = Product::find($productPrice->product_id);

        $recipe = $product ? ($product->recipe > 0 ? $product->recipe : ($product->productCategory?->recipe ?? 0)) : 0;

        // Jika recipe adalah persentase, misal 10 berarti 10%
        // Maka harga akhir = price + (price * recipe / 100)
        return $productPrice->hpp_average + ($productPrice->hpp_average * $recipe / 100);
    }
}
