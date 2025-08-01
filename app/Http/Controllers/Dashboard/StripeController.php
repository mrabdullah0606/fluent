<?php 

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function create(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Example Product',
                    ],
                    'unit_amount' => 1999, // $19.99
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('home') . '?success=true',
            'cancel_url' => route('home') . '?canceled=true',
        ]);

        return redirect($session->url);
    }
}
