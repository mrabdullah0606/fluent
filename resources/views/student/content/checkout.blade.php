@extends('website.master.master')
@section('title', 'Secure Checkout - FluentAll')
@section('content')
    <form method="POST" action="{{ route('student.stripe.checkout') }}">
        @csrf

        <main class="flex-grow">
            <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 hero-pattern-custom">
                <div class="max-w-2xl mx-auto">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center text-sm text-primary hover:underline mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-1">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg>
                        Back to Booking
                    </a>

                    <div class="rounded-lg border bg-card text-card-foreground shadow-xl border-primary/30">
                        <div class="flex flex-col space-y-1.5 bg-primary/5 p-6">
                            <h3 class="tracking-tight text-3xl font-bold text-foreground flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="mr-3 text-primary">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                Secure Checkout
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                You're just a step away from starting your learning journey!
                            </p>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Order Summary -->
                            <div class="border border-input rounded-lg p-4 bg-background">
                                <h3 class="text-lg font-semibold text-foreground mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                    Order Summary
                                </h3>

                                <div class="space-y-3">
                                    <!-- Package/Service Line -->
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-medium text-foreground">{{ $summary }}</p>

                                            @if (isset($additionalData) && $type === 'package')
                                                <div class="text-xs text-muted-foreground mt-1 space-y-1">
                                                    @if (isset($additionalData['number_of_lessons']))
                                                        <p>• {{ $additionalData['number_of_lessons'] }} lessons included</p>
                                                    @endif
                                                    @if (isset($additionalData['duration_per_lesson']))
                                                        <p>• {{ $additionalData['duration_per_lesson'] }} minutes per lesson
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif

                                            @if (isset($additionalData) && $type === 'duration')
                                                <div class="text-xs text-muted-foreground mt-1">
                                                    @if (isset($additionalData['selected_date']))
                                                        <p>• Scheduled for:
                                                            {{ \Carbon\Carbon::parse($additionalData['selected_date'])->format('F j, Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-right ml-4">
                                            <span class="text-foreground font-bold text-lg">
                                                ${{ number_format($calculatedPrice, 2) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Processing Fee Line -->
                                    <div class="flex justify-between items-center text-muted-foreground">
                                        <div class="flex items-center">
                                            <p>Processing Fee (3%)</p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="ml-1 text-gray-400">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="M9 12l2 2 4-4"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600 font-medium">
                                            ${{ number_format($fee, 2) }}
                                        </span>
                                    </div>

                                    <!-- Divider -->
                                    <hr class="border-t border-gray-200">

                                    <!-- Total Line -->
                                    <div class="flex justify-between items-center font-bold text-foreground text-lg">
                                        <p>Total Amount</p>
                                        <span class="text-primary text-xl">
                                            ${{ number_format($total, 2) }}
                                        </span>
                                    </div>

                                    @if ($type === 'package' && isset($additionalData['number_of_lessons']) && $additionalData['number_of_lessons'] > 0)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-3">
                                            <p class="text-sm text-green-800 font-medium">
                                                💡 You're paying only <span
                                                    class="font-bold">${{ number_format($calculatedPrice / $additionalData['number_of_lessons'], 2) }}</span>
                                                per lesson!
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Hidden Fields for Form Submission -->
                                <input type="hidden" name="summary" value="{{ $summary }}">
                                <input type="hidden" name="calculated_price" value="{{ $calculatedPrice }}">
                                <input type="hidden" name="fee" value="{{ $fee }}">
                                <input type="hidden" name="total" value="{{ $total }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                @if (isset($teacherId))
                                    <input type="hidden" name="teacher_id" value="{{ $teacherId }}">
                                @endif
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label class="text-lg font-semibold text-foreground mb-3 block flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="M7 15h.01M11 15h4"></path>
                                    </svg>
                                    Payment Method
                                </label>
                                <div
                                    class="border border-blue-300 rounded-lg p-4 bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="h-5 w-5 rounded-full border-2 border-blue-500 flex items-center justify-center">
                                            <div class="h-2.5 w-2.5 rounded-full bg-blue-600"></div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-blue-900 font-medium">Pay with Stripe</span>
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none">
                                                    <rect x="2" y="4" width="20" height="16" rx="2"
                                                        stroke="currentColor" stroke-width="2" />
                                                    <path d="M7 15h.01M11 15h4" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
                                                </svg>
                                                <span class="text-xs text-blue-600 font-medium">256-bit SSL
                                                    Encrypted</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-blue-700 mt-2 ml-8">
                                        Secure payment processing with industry-standard encryption. We accept Visa,
                                        Mastercard, American Express, and more.
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" id="checkoutBtn"
                                    class="inline-flex items-center justify-center rounded-md font-bold bg-warning h-12 px-6 w-full text-lg transition-all duration-200 shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2">
                                        </rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    Pay ${{ number_format($total, 2) }} Securely
                                </button>
                            </div>

                            <!-- Trust Indicators -->
                            <div class="flex items-center justify-center space-x-4 pt-2">
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-1">
                                        <path d="M9 12l2 2 4-4"></path>
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    SSL Secured
                                </div>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-1">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2">
                                        </rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    PCI Compliant
                                </div>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-1">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                    Money Back Guarantee
                                </div>
                            </div>

                            <p class="text-xs text-muted-foreground text-center">
                                By completing this purchase, you agree to our
                                <a href="#" class="text-primary hover:underline font-medium">Terms of Service</a>
                                and
                                <a href="#" class="text-primary hover:underline font-medium">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </form>

    <script>
        // Enhanced checkout button functionality
        document.getElementById('checkoutBtn')?.addEventListener('click', function(e) {
            e.preventDefault();

            // Disable button and show loading state
            this.disabled = true;
            this.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing Payment...
            `;

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('student.stripe.checkout') }}";

            // Add CSRF token
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = "{{ csrf_token() }}";
            form.appendChild(token);

            // Create comprehensive order summary
            const orderSummary = {
                description: @json($summary),
                base_price: {{ $calculatedPrice }},
                processing_fee: {{ $fee }},
                total: {{ $total }},
                type: @json($type),
                teacher_id: {{ $teacherId ?? 'null' }},
                timestamp: new Date().toISOString()
            };

            // Add order summary as JSON
            const summaryInput = document.createElement('input');
            summaryInput.type = 'hidden';
            summaryInput.name = 'order_summary';
            summaryInput.value = JSON.stringify(orderSummary);
            form.appendChild(summaryInput);

            // Add individual fields
            const fields = [{
                    name: 'summary',
                    value: @json($summary)
                },
                {
                    name: 'calculated_price',
                    value: {{ $calculatedPrice }}
                },
                {
                    name: 'fee',
                    value: {{ $fee }}
                },
                {
                    name: 'total',
                    value: {{ $total }}
                },
                {
                    name: 'type',
                    value: @json($type)
                },
                {
                    name: 'amount',
                    value: {{ intval($total * 100) }}
                }
            ];

            @if (isset($teacherId))
                fields.push({
                    name: 'teacher_id',
                    value: {{ $teacherId }}
                });
            @endif

            // Create hidden inputs for all fields
            fields.forEach(field => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = field.name;
                input.value = field.value;
                form.appendChild(input);
            });

            // Submit form
            document.body.appendChild(form);
            form.submit();
        });

        // Optional: Add form validation or additional checkout logic here
        function validateCheckout() {
            // Add any pre-checkout validation if needed
            return true;
        }
    </script>
@endsection
