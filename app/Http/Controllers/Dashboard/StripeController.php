<?php

namespace App\Http\Controllers\Dashboard;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\Payment;
use App\Models\UserLessonTracking;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function create(Request $request)
    {
        $tutorId = session('tutor_id');
        \Stripe\Stripe::setApiKey('sk_test_51R1qO3PJIQTNOZWvvlzloOaq4XLLUKuv2lFl0xrjJ7I2lBeKrBFmEXA6T5uXoRGD1cDS6e1BsVTaNPycW9wfxlHl00fcCmlT4H');

        $summary = $request->summary;
        $basePrice = $request->calculated_price;
        $fee = $request->fee;
        $total = $request->total;
        $paymentMethod = $request->payment;
        $type = $request->type;

        // Handle demo payment separately
        if ($paymentMethod === 'demo') {
            $this->recordPayment($request, 'successful');
            return redirect()->route('student.dashboard')->with('success', 'Payment successful! Your lesson package has been activated.');
        }

        // Store payment data in session temporarily
        session([
            'pending_payment' => [
                'student_id' => auth()->id(),
                'teacher_id' => $tutorId,
                'summary' => $summary,
                'base_price' => $basePrice,
                'fee' => $fee,
                'total' => $total,
                'type' => $type,
                'payment_method' => 'stripe'
            ]
        ]);

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

        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'metadata' => [
                    'student_id' => auth()->id(),
                    'teacher_id' => $tutorId,
                    'type' => $type
                ]
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            Log::error('Stripe session creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('student.dashboard')->with('error', 'Invalid payment session.');
        }

        try {
            \Stripe\Stripe::setApiKey('sk_test_51R1qO3PJIQTNOZWvvlzloOaq4XLLUKuv2lFl0xrjJ7I2lBeKrBFmEXA6T5uXoRGD1cDS6e1BsVTaNPycW9wfxlHl00fcCmlT4H');

            // Retrieve the session from Stripe
            $session = Session::retrieve($sessionId);

            // Check if payment was actually successful
            if ($session->payment_status === 'paid') {
                $pendingPayment = session('pending_payment');

                if (!$pendingPayment) {
                    return redirect()->route('student.dashboard')->with('error', 'Payment data not found.');
                }

                // Create mock request object with pending payment data
                $mockRequest = new Request($pendingPayment);

                $this->recordPayment($mockRequest, 'successful', $session->payment_intent);

                // Clear the pending payment from session
                session()->forget('pending_payment');

                return redirect()->route('student.dashboard')->with('success', 'Payment successful! Your lesson package has been activated.');
            } else {
                return redirect()->route('student.dashboard')->with('error', 'Payment was not completed successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return redirect()->route('student.dashboard')->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function cancel()
    {
        // Clear pending payment data
        session()->forget('pending_payment');

        return redirect()->route('student.dashboard')->with('info', 'Payment was cancelled. You can try again anytime.');
    }

    private function recordPayment(Request $request, $status = 'successful', $stripePaymentIntentId = null)
    {
        try {
            // Create payment record
            $payment = Payment::create([
                'student_id'     => $request->input('student_id') ?? auth()->id(),
                'teacher_id'     => $request->input('teacher_id'),
                'summary'        => $request->input('summary'),
                'base_price'     => $request->input('base_price'),
                'fee'            => $request->input('fee'),
                'total'          => $request->input('total'),
                'type'           => $request->input('type'),
                'payment_method' => $request->input('payment_method', 'stripe'),
                'status'         => $status,
                'stripe_payment_intent_id' => $stripePaymentIntentId, // Store Stripe payment intent ID
            ]);

            // Create lesson tracking record for packages and duration lessons
            $this->createLessonTracking($payment, $request);

            Log::info('Payment and lesson tracking recorded successfully', [
                'payment_id' => $payment->id,
                'student_id' => $request->input('student_id') ?? auth()->id(),
                'type' => $request->input('type'),
                'stripe_payment_intent_id' => $stripePaymentIntentId
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording payment: ' . $e->getMessage(), [
                'student_id' => $request->input('student_id') ?? auth()->id(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }
    }

    private function createLessonTracking($payment, $request)
    {
        $type = $request->input('type');
        $studentId = $request->input('student_id') ?? auth()->id();
        $teacherId = $request->input('teacher_id');

        if ($type === 'package') {
            // Extract lesson count from summary (e.g., "10-Lesson Package" -> 10)
            $summary = $payment->summary;
            preg_match('/(\d+)-Lesson/', $summary, $matches);
            $totalLessons = isset($matches[1]) ? intval($matches[1]) : 1;

            $pricePerLesson = $totalLessons > 0 ? $payment->base_price / $totalLessons : $payment->base_price;

            UserLessonTracking::create([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'payment_id' => $payment->id,
                'payment_type' => 'package',
                'package_summary' => $payment->summary,
                'total_lessons_purchased' => $totalLessons,
                'lessons_taken' => 0,
                'lessons_remaining' => $totalLessons,
                'price_per_lesson' => $pricePerLesson,
                'status' => 'active',
                'purchase_date' => now(),
                'expiry_date' => now()->addMonths(6) // 6 months validity
            ]);
        } elseif ($type === 'duration') {
            UserLessonTracking::create([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'payment_id' => $payment->id,
                'payment_type' => 'duration',
                'package_summary' => $payment->summary,
                'total_lessons_purchased' => 1,
                'lessons_taken' => 0,
                'lessons_remaining' => 1,
                'price_per_lesson' => $payment->base_price,
                'status' => 'active',
                'purchase_date' => now(),
                'expiry_date' => now()->addMonths(1)
            ]);
        }
    }

    // Webhook handler for additional security (recommended)
    public function webhook(Request $request)
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload in Stripe webhook');
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature in Stripe webhook');
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'checkout.session.completed':
                $session = $event['data']['object'];
                Log::info('Checkout session completed', ['session_id' => $session['id']]);
                // Additional webhook processing can be done here
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                Log::info('Payment succeeded', ['payment_intent_id' => $paymentIntent['id']]);
                break;
            default:
                Log::info('Received unknown event type', ['type' => $event['type']]);
        }

        return response('Success', 200);
    }
}
