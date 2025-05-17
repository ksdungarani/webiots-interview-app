@extends('shopify-app::layouts.default')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-8 text-center">Choose a Subscription Plan</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-2xl transition duration-300">
                    <div>
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">{{ $plan->name }}</h2>
                        <p class="text-3xl font-bold text-indigo-600">${{ number_format($plan->price, 2) }}</p>
                        <p class="mt-2 text-sm text-gray-500">{{ $plan->terms }}</p>
                        <p class="mt-1 text-xs text-gray-400">Billing every: {{ str_replace('EVERY_', '', $plan->interval) }}</p>
                        <p class="mt-1 text-xs text-gray-400">Trial days: {{ $plan->trial_days }}</p>
                    </div>

                    <form method="POST" action="{{ route('subscribe.plan', $plan->id) }}" class="mt-6">
                        @csrf
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-xl transition">
                            Choose {{ $plan->name }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
