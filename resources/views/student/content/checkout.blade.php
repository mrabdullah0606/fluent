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
                            <h3 class="text-lg font-semibold text-foreground mb-2">Order Summary</h3>
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-muted-foreground">
                                    <p>{{ $summary }}</p>
                                    <p>${{ number_format($calculatedPrice, 2) }}</p>
                                </div>
                                <div class="flex justify-between items-center text-muted-foreground">
                                    <p>Processing Fee (3%)</p>
                                    <p>${{ number_format($fee, 2) }}</p>
                                </div>
                                <div class="flex justify-between items-center text-xl font-bold text-primary pt-2 border-t mt-2">
                                    <p>Total</p>
                                    <p>${{ number_format($total, 2) }}</p>
                                </div>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="summary" value="{{ $summary }}">
                            <input type="hidden" name="calculated_price" value="{{ $calculatedPrice }}">
                            <input type="hidden" name="fee" value="{{ $fee }}">
                            <input type="hidden" name="total" value="{{ $total }}">
                        </div>

                        <!-- Payment Options -->
                        <div>
                            <label class="text-lg font-semibold text-foreground mb-3 block">Payment Method</label>
                            <div role="radiogroup" class="grid gap-4" id="payment-options">
                                <label class="flex items-center space-x-3 p-4 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="radio" name="payment" value="stripe" id="stripe-option" />
                                    <div class="h-5 w-5 rounded-full border-2 border-blue-500 flex items-center justify-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-blue-600 scale-0 peer-checked:scale-100 transition-transform duration-200"></div>
                                    </div>
                                    <span class="text-blue-900 font-medium">Pay with Stripe</span>
                                </label>

                                <label class="flex items-center space-x-3 p-4 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="radio" name="payment" value="demo" id="demo-option" checked />
                                    <div class="h-5 w-5 rounded-full border-2 border-green-500 flex items-center justify-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-600 scale-0 peer-checked:scale-100 transition-transform duration-200"></div>
                                    </div>
                                    <span class="text-green-900 font-medium">Demo Payment</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-md font-medium bg-primary text-white hover:bg-primary/90 h-10 px-4 w-full text-lg py-3">
                                Pay ${{ number_format($total, 2) }} Securely
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="ml-2 h-5 w-5">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </button>
                        </div>

                        <p class="text-xs text-muted-foreground text-center">
                            By clicking "Pay Securely", you agree to our Terms of Service and Privacy Policy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</form>

<script>
  // Callback function
  function onPaymentMethodChange(selectedValue) {
    console.log("Selected payment method:", selectedValue);
    
    // Example: update a hidden input or perform UI changes
    document.getElementById("selected-method-display").textContent = selectedValue;
  }

  // Attach event listeners
  document.querySelectorAll('input[name="payment"]').forEach(input => {
    input.addEventListener('change', function () {
      if (this.checked) {
        onPaymentMethodChange(this.value);
      }
    });
  });
  
</script>

<script>
  document.getElementById('stripeCheckoutBtn')?.addEventListener('click', function (e) {
    e.preventDefault();

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('student.stripe.checkout') }}";

    // CSRF Token
    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = "{{ csrf_token() }}";
    form.appendChild(token);

    // Order Summary from Blade variables
    const orderSummary = {
      description: @json($summary),
      base_price: {{ $calculatedPrice }},
      processing_fee: {{ $fee }},
      total: {{ $total }}
    };

    const summaryInput = document.createElement('input');
    summaryInput.type = 'hidden';
    summaryInput.name = 'order_summary';
    summaryInput.value = JSON.stringify(orderSummary);
    form.appendChild(summaryInput);

    // Amount in cents for Stripe
    const amountInput = document.createElement('input');
    amountInput.type = 'hidden';
    amountInput.name = 'amount';
    amountInput.value = {{ intval($total * 100) }}; // Convert to cents
    form.appendChild(amountInput);

    document.body.appendChild(form);
    form.submit();
  });
</script>



@endsection
