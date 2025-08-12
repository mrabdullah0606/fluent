@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <form method="POST" action="{{ route('student.stripe.checkout') }}">
        @csrf

        <main class="flex-grow">
            <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 hero-pattern-custom">
                <div class="max-w-2xl mx-auto">
                    <a href="{{ url()->previous() }}" class="text-sm text-primary hover:underline">
                        <!-- Back Button -->
                    </a>

                    <div class="rounded-lg border bg-card text-card-foreground shadow-xl border-primary/30">
                        <div class="flex flex-col space-y-1.5 bg-primary/5 p-6">
                            <h3 class="tracking-tight text-3xl font-bold text-foreground flex items-center">
                                <!-- Secure Checkout Title -->
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                You're just a step away from starting your learning journey!
                            </p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Order Summary -->
                            <div class="border border-input rounded-lg p-4 bg-background">
                                <h3 class="text-lg font-semibold text-foreground mb-4">Order Summary</h3>
                                <div class="space-y-3">
                                    <!-- Package/Service Line -->
                                    <div class="flex justify-between items-center text-muted-foreground">
                                        <p>{{ $summary }}</p>
                                        <div class="text-right">
                                            @if ($discountPercent > 0)
                                                <span class="line-through text-red-500 font-semibold">
                                                    ${{ number_format($originalPrice, 2) }}
                                                </span>
                                                <span class="text-green-600 font-bold ml-1">
                                                    ${{ number_format($calculatedPrice, 2) }}
                                                </span>
                                                <small class="text-gray-500">(-{{ $discountPercent }}%)</small>
                                            @else
                                                <span class="text-green-600 font-bold">
                                                    ${{ number_format($calculatedPrice, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Processing Fee Line -->
                                    <div class="flex justify-between items-center text-muted-foreground">
                                        <p>Processing Fee (3%)</p>
                                        <span class="text-gray-600 font-medium">
                                            ${{ number_format($fee, 2) }}
                                        </span>
                                    </div>

                                    <!-- Divider -->
                                    <hr class="border-t border-gray-200">

                                    <!-- Total Line -->
                                    <div class="flex justify-between items-center font-semibold text-foreground">
                                        <p class="text-lg">Total</p>
                                        <span class="text-green-600 font-bold text-lg">
                                            ${{ number_format($total, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Hidden Fields -->
                                <input type="hidden" name="summary" value="{{ $summary }}">
                                <input type="hidden" name="calculated_price" value="{{ $calculatedPrice }}">
                                <input type="hidden" name="fee" value="{{ $fee }}">
                                <input type="hidden" name="total" value="{{ $total }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                @if (isset($originalPrice))
                                    <input type="hidden" name="original_price" value="{{ $originalPrice }}">
                                @endif
                                @if (isset($discountPercent))
                                    <input type="hidden" name="discount_percent" value="{{ $discountPercent }}">
                                @endif
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label class="text-lg font-semibold text-foreground mb-3 block">Payment Method</label>
                                <div
                                    class="border border-gray-300 rounded-lg p-4 bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="h-5 w-5 rounded-full border-2 border-blue-500 flex items-center justify-center">
                                            <div class="h-2.5 w-2.5 rounded-full bg-blue-600"></div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-blue-900 font-medium">Pay with Stripe</span>
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none">
                                                    <rect x="2" y="4" width="20" height="16" rx="2"
                                                        stroke="currentColor" stroke-width="2" />
                                                    <path d="M7 15h.01M11 15h4" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
                                                </svg>
                                                <span class="text-xs text-blue-600">Secure Payment</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-blue-700 mt-2 ml-8">
                                        Secure payment processing with SSL encryption. We accept all major credit cards.
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" id="checkoutBtn"
                                    class="inline-flex items-center justify-center rounded-md font-medium bg-primary text-white hover:bg-primary/90 h-12 px-4 w-full text-lg transition-colors">
                                    Pay ${{ number_format($total, 2) }} with Stripe
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="ml-2 h-5 w-5">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2">
                                        </rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </button>
                            </div>

                            <p class="text-xs text-muted-foreground text-center">
                                By clicking "Pay Securely", you agree to our
                                <a href="#" class="text-primary hover:underline">Terms of Service</a> and
                                <a href="#" class="text-primary hover:underline">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </form>

    <script>
        function onPaymentMethodChange(selectedValue) {
            console.log("Selected payment method:", selectedValue);
            document.getElementById("selected-method-display").textContent = selectedValue;
        }
        document.querySelectorAll('input[name="payment"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    onPaymentMethodChange(this.value);
                }
            });
        });
    </script>
    <script>
        document.getElementById('checkoutBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('student.stripe.checkout') }}";
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = "{{ csrf_token() }}";
            form.appendChild(token);
            const paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'payment';
            paymentInput.value = 'stripe';
            form.appendChild(paymentInput);
            const orderSummary = {
                description: @json($summary),
                base_price: {{ $calculatedPrice }},
                @if (isset($originalPrice) && $originalPrice > 0)
                    original_price: {{ $originalPrice }},
                @endif
                @if (isset($discountPercent) && $discountPercent > 0)
                    discount_percent: {{ $discountPercent }},
                    discount_amount: {{ isset($originalPrice) ? $originalPrice - $calculatedPrice : 0 }},
                @endif
                processing_fee: {{ $fee }},
                total: {{ $total }},
                type: @json($type)
            };

            const summaryInput = document.createElement('input');
            summaryInput.type = 'hidden';
            summaryInput.name = 'order_summary';
            summaryInput.value = JSON.stringify(orderSummary);
            form.appendChild(summaryInput);
            const summaryField = document.createElement('input');
            summaryField.type = 'hidden';
            summaryField.name = 'summary';
            summaryField.value = @json($summary);
            form.appendChild(summaryField);

            const calculatedPriceField = document.createElement('input');
            calculatedPriceField.type = 'hidden';
            calculatedPriceField.name = 'calculated_price';
            calculatedPriceField.value = {{ $calculatedPrice }};
            form.appendChild(calculatedPriceField);

            const feeField = document.createElement('input');
            feeField.type = 'hidden';
            feeField.name = 'fee';
            feeField.value = {{ $fee }};
            form.appendChild(feeField);

            const totalField = document.createElement('input');
            totalField.type = 'hidden';
            totalField.name = 'total';
            totalField.value = {{ $total }};
            form.appendChild(totalField);

            const typeField = document.createElement('input');
            typeField.type = 'hidden';
            typeField.name = 'type';
            typeField.value = @json($type);
            form.appendChild(typeField);
            const amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = {{ intval($total * 100) }};
            form.appendChild(amountInput);
            @if (isset($originalPrice))
                const originalPriceField = document.createElement('input');
                originalPriceField.type = 'hidden';
                originalPriceField.name = 'original_price';
                originalPriceField.value = {{ $originalPrice }};
                form.appendChild(originalPriceField);
            @endif

            @if (isset($discountPercent))
                const discountPercentField = document.createElement('input');
                discountPercentField.type = 'hidden';
                discountPercentField.name = 'discount_percent';
                discountPercentField.value = {{ $discountPercent }};
                form.appendChild(discountPercentField);
            @endif
            this.disabled = true;
            this.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing Payment...
          `;
            document.body.appendChild(form);
            form.submit();
        });
    </script>
@endsection
