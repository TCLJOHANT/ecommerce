<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Exito! - Ecommerce')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;
    public function render()
    {
        $latest_order = Order::with('address')
                            ->where('user_id',auth()->user()->id)
                            ->latest()
                            ->first();
        if($session_id){
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $session_info = Session::retrieve($this->session_id);
            // si es diferente de pagado, entonces se marca como fallido
            if($session_info->payment_status != 'paid'){
                $latest_order->status = 'failed';
                $latest_order->save();
                return redirect()->route('cancel');
            }elseif($session_info->payment_status == 'paid'){
                $latest_order->status = 'paid';
                $latest_order->save();
                return redirect()->route('my-orders.show',$latest_order);
            }
        }
        return view('livewire.success-page',['order'=>$latest_order]);
    }
}
