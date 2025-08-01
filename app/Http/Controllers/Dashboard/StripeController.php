<?php 

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{       
    public function create(Request $request)
{
    \Stripe\Stripe::setApiKey('sk_test_51R1qO3PJIQTNOZWvvlzloOaq4XLLUKuv2lFl0xrjJ7I2lBeKrBFmEXA6T5uXoRGD1cDS6e1BsVTaNPycW9wfxlHl00fcCmlT4H');

    $summary = $request->summary;
    $basePrice = $request->calculated_price;
    $fee = $request->fee;
    $total = $request->total;
    $paymentMethod = $request->payment;

    if ($paymentMethod === 'demo') {
        return redirect()->route('find.tutor')->with('success', 'Simulated payment successful.');
    }

    // Stripe expects amounts in cents
    $lineItems = [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => $summary,
            ],
            'unit_amount' => $total * 100,
        ],
        'quantity' => 1,
    ]];

    $checkoutSession = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route('find.tutor'),
        'cancel_url' => route('student.public.profile'),
    ]);

    return redirect($checkoutSession->url);
}


//     public function create(Request $request)
//     {
//         \Stripe\Stripe::setApiKey('sk_test_51R1qO3PJIQTNOZWvvlzloOaq4XLLUKuv2lFl0xrjJ7I2lBeKrBFmEXA6T5uXoRGD1cDS6e1BsVTaNPycW9wfxlHl00fcCmlT4H');

//         $orderItems = json_decode($request->order_items, true);
//     $amount = $request->amount; // Should already be in cents

//     $lineItems = array_map(function ($item) {
//         return [
//             'price_data' => [
//                 'currency' => 'usd',
//                 'product_data' => [
//                     'name' => $item['name'],
//                 ],
//             'unit_amount' => $item['price'], // Must be in cents
//         ],
//         'quantity' => $item['quantity'],
//     ];
// }, $orderItems);

//     $checkoutSession = \Stripe\Checkout\Session::create([
//         'payment_method_types' => ['card'],
//         'line_items' => $lineItems,
//         'mode' => 'payment',
//         'success_url' => route('find.tutor'),
//         'cancel_url' => route('student.public.profile'),
//     ]);

//     return redirect($checkoutSession->url);
// }

}
