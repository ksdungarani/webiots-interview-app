<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $shop = auth()->user();
        $accessToken = $shop->access_token;

        $title = $request->input('title');
        $description = $request->input('description');
        $image = $request->file('image');
        $variants = $request->input('variants');

        $base64Image = null;
        if ($image) {
            $base64Image = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image));
        }

        $variantInputs = [];
        foreach ($variants as $variant) {
            $variantInputs[] = [
                'price' => (string) $variant['price'],
                'inventoryQuantity' => (int) $variant['quantity'],
                'option1' => $variant['size'],
                'option2' => $variant['color'],
            ];
        }

        $mutation = <<<'GRAPHQL'
        mutation productCreate($input: ProductInput!) {
        productCreate(input: $input) {
            product {
            id
            title
            }
            userErrors {
            field
            message
            }
        }
        }
        GRAPHQL;

        $variables = [
            'input' => [
                'title' => $title,
                'bodyHtml' => $description,
                'variants' => $variantInputs,
                'images' => $base64Image ? [['src' => $base64Image]] : [],
                'options' => ['Size', 'Color']
            ],
        ];

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post("https://{$shop->name}.myshopify.com/admin/api/2024-01/graphql.json", [
            'query' => $mutation,
            'variables' => $variables,
        ]);

        $data = $response->json();

        if (isset($data['data']['productCreate']['product'])) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $data['data']['productCreate']['product'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error creating product',
            'errors' => $data['data']['productCreate']['userErrors'] ?? [],
        ]);
    }

}
