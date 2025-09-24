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
                        {{ __('welcome.key_530') }}
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
                                {{ __('welcome.key_531') }}
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                {{ __('welcome.key_152') }}
                            </p>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="border border-input rounded-lg p-4 bg-background">
                                <h3 class="text-lg font-semibold text-foreground mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                    {{ __('welcome.key_153') }}
                                </h3>

                                <div class="space-y-3">
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
                                    <div class="flex justify-between items-center text-muted-foreground">
                                        <div class="flex items-center">
                                            <p>{{ __('welcome.key_532') }}</p>
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
                                    <hr class="border-t border-gray-200">
                                    <div class="flex justify-between items-center font-bold text-foreground text-lg">
                                        <p>{{ __('welcome.key_533') }}</p>
                                        <span class="text-primary text-xl">
                                            ${{ number_format($total, 2) }}
                                        </span>
                                    </div>
                                    @if ($type === 'package' && isset($additionalData['number_of_lessons']) && $additionalData['number_of_lessons'] > 0)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-3">
                                            <p class="text-sm text-green-800 font-medium">
                                                {{ __('welcome.key_534') }} <span
                                                    class="font-bold">${{ number_format($calculatedPrice / $additionalData['number_of_lessons'], 2) }}</span>
                                                {{ __('welcome.key_535') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <input type="hidden" name="summary" value="{{ $summary }}">
                                <input type="hidden" name="calculated_price" value="{{ $calculatedPrice }}">
                                <input type="hidden" name="fee" value="{{ $fee }}">
                                <input type="hidden" name="total" value="{{ $total }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                @if (isset($teacherId))
                                    <input type="hidden" name="teacher_id" value="{{ $teacherId }}">
                                @endif
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-foreground mb-3 block flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-2">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="M7 15h.01M11 15h4"></path>
                                    </svg>
                                    {{ __('welcome.key_155') }}
                                </label>
                                <div
                                    class="border border-blue-300 rounded-lg p-4 bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="h-5 w-5 rounded-full border-2 border-blue-500 flex items-center justify-center">
                                            <div class="h-2.5 w-2.5 rounded-full bg-blue-600"></div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-blue-900 font-medium">{{ __('welcome.key_156') }}</span>
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none">
                                                    <rect x="2" y="4" width="20" height="16" rx="2"
                                                        stroke="currentColor" stroke-width="2" />
                                                    <path d="M7 15h.01M11 15h4" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
                                                </svg>
                                                <span class="text-xs text-blue-600 font-medium">{{ __('welcome.key_536') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-blue-700 mt-2 ml-8">
                                        {{ __('welcome.key_537') }}
                                    </p>
                                </div>
                            </div>
                            {{-- <div class="pt-4">
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
                            <p class="text-xs text-muted-foreground text-center">
                                By completing this purchase, you agree to our
                                <a href="#" class="text-primary hover:underline font-medium">Terms of Service</a>
                                and
                                <a href="#" class="text-primary hover:underline font-medium">Privacy Policy</a>.
                            </p> --}}
                            <div class="pt-4">
                                <!-- Checkbox + Label -->
                                <div class="flex items-start mb-4">
                                    <input id="agreeTerms" type="checkbox"
                                        class="mt-1 h-4 w-4 text-primary border-gray-300 rounded cursor-pointer"
                                        onclick="togglePayButton()" />

                                    <label for="agreeTerms" class="ml-2 text-sm text-gray-700">
                                        {{ __('welcome.key_538') }}
                                        <button type="button"
                                            onclick="openPdfModal('{{ asset('assets/website/pdf/refundPolicy.pdf') }}')"
                                            class="text-primary hover:underline font-medium">{{ __('welcome.key_539') }}</button>
                                        {{ __('welcome.key_540') }}
                                        <button type="button"
                                            onclick="openPdfModal('{{ asset('assets/website/pdf/refundPolicy.pdf') }}')"
                                            class="text-primary hover:underline font-medium">{{ __('welcome.key_541') }}</button>.
                                    </label>
                                </div>

                                <!-- Pay Button -->
                                <button type="submit" id="checkoutBtn"
                                    class="inline-flex items-center justify-center rounded-md font-bold bg-warning h-12 px-6 w-full text-lg transition-all duration-200 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
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
                            <!-- Modal -->
                            <div id="pdfModal"
                                class="fixed inset-0 hidden bg-black bg-opacity-70 z-50 flex items-center justify-center">
                                <div class="bg-white rounded-lg shadow-lg w-full h-full relative">
                                    <!-- Close Button -->
                                    <button onclick="closePdfModal(event)"
                                        class="absolute top-4 right-6 text-gray-600 hover:text-black text-3xl font-bold">{{ __('welcome.key_542') }}</button>

                                    <!-- PDF Viewer Fullscreen -->
                                    <iframe id="pdfFrame" src="" class="w-full h-full"></iframe>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </main>
    </form>

    <script>
        document.getElementById('checkoutBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            this.disabled = true;
            this.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing Payment...
            `;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('student.stripe.checkout') }}";
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = "{{ csrf_token() }}";
            form.appendChild(token);
            const orderSummary = {
                description: @json($summary),
                base_price: {{ $calculatedPrice }},
                processing_fee: {{ $fee }},
                total: {{ $total }},
                type: @json($type),
                teacher_id: {{ $teacherId ?? 'null' }},
                timestamp: new Date().toISOString()
            };
            const summaryInput = document.createElement('input');
            summaryInput.type = 'hidden';
            summaryInput.name = 'order_summary';
            summaryInput.value = JSON.stringify(orderSummary);
            form.appendChild(summaryInput);
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
            fields.forEach(field => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = field.name;
                input.value = field.value;
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
        });

        function validateCheckout() {
            return true;
        }

        function togglePayButton() {
            const checkbox = document.getElementById('agreeTerms');
            const btn = document.getElementById('checkoutBtn');
            btn.disabled = !checkbox.checked;
        }

        function openPdfModal(pdfUrl) {
            document.getElementById('pdfModal').classList.remove('hidden');
            document.getElementById('pdfFrame').src = pdfUrl;
        }

        function closePdfModal(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfFrame').src = "";

            // Remove focus to prevent accidental form submission
            if (document.activeElement) {
                document.activeElement.blur();
            }

            return false;
        }
    </script>
@endsection
