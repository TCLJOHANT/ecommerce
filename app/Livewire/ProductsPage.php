<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Productos - Ecommerce')]
class ProductsPage extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured;
    
    #[Url]
    public $on_sale;

    #[Url]
    public $price_range = 1000000;

    #[Url]
    public $sort = 'latest';
    public function addToCart($product_id) {
        $total_count  = CartManagement::addItemToCart($product_id);
        $this->dispatch('update-cart-count', total_count : $total_count)->to(Navbar::class);
        $this->alert('success', 'El producto fue agregado al carrito',[
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
    public function render()
    {
        $productsQuery = Product::query()->where('is_active', true);
        if (count($this->selected_categories) > 0) {
            $productsQuery->whereIn('category_id', $this->selected_categories);
        }
        if (count($this->selected_brands) > 0) {
            $productsQuery->whereIn('brand_id', $this->selected_brands);
        }
        if ($this->featured) {
            $productsQuery->where('is_featured', true);
        }
        if ($this->on_sale) {
            $productsQuery->where('on_sale', true);
        }
        if ($this->price_range) {
            $productsQuery->whereBetween('price', [0,$this->price_range]);
        }
        if ($this->sort == 'latest') {
            $productsQuery->latest();
        }
        if ($this->sort == 'price') {
            $productsQuery->orderBy('price');
        }
        return view('livewire.products-page', [
            'products' => $productsQuery->paginate(6),
            'categories' => Category::where('is_active', true)->get(['id', 'name', 'slug']),
            'brands' => Brand::where('is_active', true)->get(['id', 'name', 'slug']),
        ]);
    }
}
