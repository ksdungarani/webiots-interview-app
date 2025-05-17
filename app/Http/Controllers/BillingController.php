<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * Show all available plans to choose from.
     */
    public function showPlans()
    {
        $plans = DB::table('plans')->get();

        return view('choose-plan', compact('plans'));
    }

    public function subscribe(Request $request, $planId)
    {
        $plan = DB::table('plans')->find($planId);

        if (!$plan) {
            return redirect()->back()->with('error', 'Plan not found.');
        }

        $shop = Auth::user(); // Make sure this gives you the shop with API access

        $query = <<<'GRAPHQL'
        mutation appSubscriptionCreate($name: String!, $returnUrl: URL!, $trialDays: Int, $test: Boolean!, $lineItems: [AppSubscriptionLineItemInput!]!) {
        appSubscriptionCreate(
            name: $name,
            returnUrl: $returnUrl,
            trialDays: $trialDays,
            test: $test,
            lineItems: $lineItems
        ) {
            userErrors {
            field
            message
            }
            confirmationUrl
            appSubscription {
            id
            }
        }
        }
        GRAPHQL;

        $variables = [
            "name" => $plan->name,
            "returnUrl" => route('billing.callback'),
            "trialDays" => (int) $plan->trial_days,
            "test" => (bool) $plan->test,
            "lineItems" => [
                [
                    "plan" => [
                        "appRecurringPricingDetails" => [
                            "price" => [
                                "amount" => number_format($plan->price, 2, '.', ''),
                                "currencyCode" => "USD"
                            ],
                            "interval" => (match ($plan->interval) {
                                'EVERY_30_DAYS' => "EVERY_30_DAYS",
                                'EVERY_90_DAYS' => "EVERY_90_DAYS",
                                'EVERY_180_DAYS' => "EVERY_180_DAYS",
                                default => "EVERY_30_DAYS"
                            })
                        ]
                    ]
                ]
            ]
        ];

        $response = $shop->api()->graph($query, $variables);

        if (!empty($response['body']['data']['appSubscriptionCreate']['userErrors'])) {
            return redirect()->back()->withErrors($response['body']['data']['appSubscriptionCreate']['userErrors']);
        }

        $confirmationUrl = $response['body']['data']['appSubscriptionCreate']['confirmationUrl'];

        return redirect($confirmationUrl);
    }

    /**
     * Handle post-approval from Shopify.
     */
    public function callback(Request $request)
    {
        return redirect('/')->with('success', 'Subscription activated successfully.');
    }
}
