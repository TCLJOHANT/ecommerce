<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - Ecommerce')]
class ProductDetailPage extends Component
{ 
    use LivewireAlert;
    public $slug;
    public $cantidad = 1;

    public function mount($slug)
    {
        $this->slug = $slug;
    }
    public function incrementCantidad()
    {
        $this->cantidad++;
    }
    public function decrementCantidad()
    { 
        if($this->cantidad > 1){
            $this->cantidad--;
        }
    }
    public function agregarAlCarrito($product_id) {
        $total_count  = CartManagement::addItemToCartWhithQuantity($product_id, $this->cantidad);
        $this->dispatch('update-cart-count', total_count : $total_count)->to(Navbar::class);
        $this->alert('success', 'El producto fue agregado al carrito',[
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
    public function render()
    {
        return view('livewire.product-detail-page',[
            'product'=>Product::where('slug',$this->slug)->firstOrFail()
        ]);
    }
}
