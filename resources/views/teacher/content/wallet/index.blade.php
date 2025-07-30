@extends('teacher.master.master')
@section('title', 'Wallet - FluentAll')
@section('content')
    <main class="flex-grow">
        <div class="bg-gray-50 flex-grow p-6 md:p-10">
            <div class="container mx-auto">
                <div style="opacity: 1; transform: none;">
                    <h1 class="text-3xl font-bold text-foreground mb-8 flex items-center"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-3 h-8 w-8 text-primary">
                            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
                            <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
                            <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
                        </svg> My Wallet</h1>
                </div>
                <div class="mb-8" style="opacity: 1; transform: none;">
                    <div class="rounded-lg border bg-card bg-gradient-to-r from-primary to-yellow-500 text-white shadow-lg">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="tracking-tight text-lg font-medium">Available Balance</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <p class="text-5xl font-bold">$1900.00</p>
                            <p class="opacity-80 mt-1">Ready for withdrawal</p>
                        </div>
                    </div>
                </div>
                <div style="opacity: 1; transform: none;">
                    <div dir="ltr" data-orientation="horizontal">
                        <div role="tablist" aria-orientation="horizontal"
                            class="h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground grid w-full grid-cols-3"
                            tabindex="0" data-orientation="horizontal" style="outline: none;"><button type="button"
                                role="tab" aria-selected="true" aria-controls="radix-:r78:-content-withdraw"
                                data-state="active" id="radix-:r78:-trigger-withdraw"
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm"
                                tabindex="0" data-orientation="horizontal" data-radix-collection-item=""><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" x2="12" y1="15" y2="3"></line>
                                </svg>Withdraw</button><button type="button" role="tab" aria-selected="false"
                                aria-controls="radix-:r78:-content-history" data-state="inactive"
                                id="radix-:r78:-trigger-history"
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm"
                                tabindex="-1" data-orientation="horizontal" data-radix-collection-item=""><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <line x1="12" x2="12" y1="2" y2="22"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>Transaction History</button><button type="button" role="tab"
                                aria-selected="false" aria-controls="radix-:r78:-content-settings" data-state="inactive"
                                id="radix-:r78:-trigger-settings"
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm"
                                tabindex="-1" data-orientation="horizontal" data-radix-collection-item=""><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                                    </path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>Payment Settings</button></div>
                        <div data-state="active" data-orientation="horizontal" role="tabpanel"
                            aria-labelledby="radix-:r78:-trigger-withdraw" id="radix-:r78:-content-withdraw"
                            tabindex="0"
                            class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-6"
                            style="">
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-6">
                                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Withdraw Funds</h3>
                                    <p class="text-sm text-muted-foreground">Choose your preferred method to withdraw your
                                        earnings.</p>
                                </div>
                                <div class="p-6 pt-0 space-y-4">
                                    <p class="text-sm text-muted-foreground">You can only withdraw using your connected
                                        payment method.</p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4"><button
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 w-full bg-primary/10 text-primary hover:bg-primary/20 border border-primary"
                                            type="button" aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="radix-:r8p:" data-state="closed">Withdraw via
                                            PayPal</button><button
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 w-full bg-primary/10 text-primary hover:bg-primary/20 border border-primary"
                                            disabled="" type="button" aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="radix-:r8s:" data-state="closed">Withdraw via
                                            Payoneer</button><button
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 w-full bg-primary/10 text-primary hover:bg-primary/20 border border-primary"
                                            disabled="" type="button" aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="radix-:r8v:" data-state="closed">Withdraw via Wise</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-state="inactive" data-orientation="horizontal" role="tabpanel"
                            aria-labelledby="radix-:r78:-trigger-history" id="radix-:r78:-content-history" tabindex="0"
                            class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-6"
                            hidden=""></div>
                        <div data-state="inactive" data-orientation="horizontal" role="tabpanel"
                            aria-labelledby="radix-:r78:-trigger-settings" id="radix-:r78:-content-settings"
                            tabindex="0"
                            class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-6"
                            hidden=""></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
