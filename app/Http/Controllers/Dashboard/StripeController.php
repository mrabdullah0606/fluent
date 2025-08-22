<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\Payment;
use App\Models\UserLessonTracking;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;

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

        if ($paymentMethod === 'demo') {
            $this->recordPayment($request, 'successful');
            return redirect()->route('student.dashboard')->with('success', 'Payment successful! Your lesson package has been activated.');
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

        $this->recordPayment($request, 'successful');
        return redirect($checkoutSession->url);
    }

    private function recordPayment(Request $request, $status = 'successful')
    {
        try {
            // Create payment record
            $payment = Payment::create([
                'student_id'     => auth()->id(),
                'teacher_id'     => session('tutor_id'),
                'summary'        => $request->input('summary'),
                'base_price'     => $request->input('calculated_price'),
                'fee'            => $request->input('fee'),
                'total'          => $request->input('total'),
                'type'           => $request->input('type'),
                'payment_method' => 'stripe',
                'status'         => $status,
            ]);

            // Create lesson tracking record for packages and duration lessons
            $this->createLessonTracking($payment, $request);

            Log::info('Payment and lesson tracking recorded successfully', [
                'payment_id' => $payment->id,
                'student_id' => auth()->id(),
                'type' => $request->input('type')
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording payment: ' . $e->getMessage(), [
                'student_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }
    }

    private function createLessonTracking($payment, $request)
    {
        $type = $request->input('type');
        $studentId = auth()->id();
        $teacherId = session('tutor_id');

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
                'expiry_date' => now()->addMonths(1) // 1 month validity for individual lessons
            ]);
        }
    }

    public function handleStripePayment(Request $request)
    {
        $this->recordPayment($request, 'successful');
        return redirect()->route('student.dashboard')->with('success', 'Payment recorded successfully.');
    }
}
