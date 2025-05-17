<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;


class ShopifyController extends Controller
{
    public function index(Request $request)
    {
        $shop = Auth::user();
        $accessToken = $shop->getAccessToken()->toNative();
        $shopDomain = $shop->name;

        $after = $request->query('after');
        $before = $request->query('before');
        $direction = $before ? 'last' : 'first';
        $cursor = $after ?? $before;
        $cursorStr = '';
        if ($cursor) {
            $cursorStr = $after ? "after: \"{$cursor}\"" : "before: \"{$cursor}\"";
        }

        $query = <<<GRAPHQL
        {
            products(first: 10, $cursorStr) {
                pageInfo {
                    hasNextPage
                    hasPreviousPage
                }
                edges {
                    cursor
                    node {
                        id
                        title
                        descriptionHtml
                        images(first: 1) {
                            edges {
                                node {
                                    originalSrc
                                }
                            }
                        }
                        variants(first: 10) {
                            edges {
                                node {
                                    title
                                    price
                                }
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;

        $client = new \GuzzleHttp\Client();

        $response = $client->post("https://{$shopDomain}/admin/api/2023-07/graphql.json", [
            'headers' => [
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => ['query' => $query],
        ]);

        $result = json_decode($response->getBody(), true);
        $products = $result['data']['products'];

        $firstCursor = $products['edges'][0]['cursor'] ?? null;
        $lastCursor = $products['edges'][count($products['edges']) - 1]['cursor'] ?? null;

        return view('welcome', compact('products', 'firstCursor', 'lastCursor'));
    }
}
