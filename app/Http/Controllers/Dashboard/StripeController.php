<?php 

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\Payment;
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
    //dd($request->all());
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
        'success_url' => route('student.dashboard'),
        'cancel_url' => route('student.public.profile'),
    ]);
    $this->recordPayment($request); // Record demo payment
    return redirect($checkoutSession->url);
}

private function recordPayment(Request $request, $status = 'successful')
{
    Payment::create([
        'student_id'     => auth()->id(),
        'course_id'      => 1, // replace with dynamic if needed
        'summary'        => $request->input('summary'),
        'base_price'     => $request->input('calculated_price'),
        'fee'            => $request->input('fee'),
        'total'          => $request->input('total'),
        'payment_method' => $request->input('payment'),
        'status'         => $status,
    ]);
}

public function handleStripePayment(Request $request)
{
    // Example values, update according to your logic
    $studentId = auth()->id(); // or get from session
    //$courseId = $request->input('course_id'); // pass from form or route

    Payment::create([
        'student_id'     => $studentId,
        'course_id'      => 1,
        'summary'        => $request->input('summary'),
        'base_price'     => $request->input('calculated_price'),
        'fee'            => $request->input('fee'),
        'total'          => $request->input('total'),
        'payment_method' => $request->input('payment'), // stripe or demo
        'status'         => 'successful', // update accordingly
    ]);

    return redirect()->route('student.dashboard')->with('success', 'Payment recorded successfully.');
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
