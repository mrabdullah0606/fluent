@extends('website.master.master')
@section('title', 'Find-Tutor - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 hero-pattern-custom">
            <div class="max-w-2xl mx-auto" style="opacity: 1; transform: none;">
                <button
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background hover:text-accent-foreground h-10 px-4 py-2 mb-6 border-primary text-primary hover:bg-primary/10"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-2 h-4 w-4">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    <a href="#">Back</a>
                </button>
                <div class="rounded-lg border bg-card text-card-foreground shadow-xl border-primary/30">
                    <div class="flex flex-col space-y-1.5 bg-primary/5 p-6">
                        <h3 class="tracking-tight text-3xl font-bold text-foreground flex items-center"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-8 w-8 mr-3 text-primary">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12">
                                </path>
                            </svg> Secure Checkout</h3>
                        <p class="text-sm text-muted-foreground">You're just a step away from starting your learning
                            journey!</p>
                    </div>
                    <div class="p-6 space-y-6">
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
                                <div
                                    class="flex justify-between items-center text-xl font-bold text-primary pt-2 border-t mt-2">
                                    <p>Total</p>
                                    <p>${{ number_format($total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div><label
                                class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-lg font-semibold text-foreground mb-3 block">Payment
                                Method</label>
                            <div role="radiogroup" aria-required="false" dir="ltr" class="grid gap-2 space-y-3"
                                tabindex="0" style="outline: none;"><label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex items-center space-x-3 p-4 border border-input rounded-lg cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-all"
                                    for="stripe-payment"><button type="button" role="radio" aria-checked="false"
                                        data-state="unchecked" value="stripe"
                                        class="aspect-square h-4 w-4 rounded-full border border-primary text-primary ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                        id="stripe-payment" tabindex="-1" data-radix-collection-item=""></button><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-6 w-6 text-primary">
                                        <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                        <line x1="2" x2="22" y1="10" y2="10"></line>
                                    </svg><span class="font-medium text-foreground">Pay with Stripe (Credit/Debit
                                        Card)</span></label><label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex items-center space-x-3 p-4 border border-input rounded-lg cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-all"
                                    for="demo-payment"><button type="button" role="radio" aria-checked="true"
                                        data-state="checked" value="demo"
                                        class="aspect-square h-4 w-4 rounded-full border border-primary text-primary ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                        id="demo-payment" tabindex="0" data-radix-collection-item=""><span
                                            data-state="checked" class="flex items-center justify-center"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="h-2.5 w-2.5 fill-current text-current">
                                                <circle cx="12" cy="12" r="10"></circle>
                                            </svg></span></button><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="h-6 w-6 text-green-500">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg><span class="font-medium text-foreground">Demo Payment (Simulated)</span></label>
                            </div>
                        </div>
                        <div class="pt-4"><button
                                class="inline-flex items-center justify-center rounded-md font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 w-full btn-red text-lg py-3">Pay
                                {{ number_format($total, 2) }} Securely<svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="ml-2 h-5 w-5">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2">
                                    </rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg></button></div>
                        <p class="text-xs text-muted-foreground text-center">By clicking "Pay Securely", you agree to our
                            Terms of Service and Privacy Policy. All transactions are secure and encrypted.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
