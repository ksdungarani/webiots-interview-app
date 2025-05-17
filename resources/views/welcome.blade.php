@extends('shopify-app::layouts.default')

@section('content')

  @include('partials.navigation-bar')
    <div id="product-table-section" class="p-6 bg-white rounded-lg shadow-md">
    <table class="min-w-full divide-y divide-gray-200 table-auto">
        <thead class="bg-gray-100">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-96">Description</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Variants</th>
        </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($products['edges'] as $productEdge)
            @php
            $product = $productEdge['node'];
            $image = $product['images']['edges'][0]['node']['originalSrc'] ?? null;
            $variants = $product['variants']['edges'] ?? [];
            @endphp
            <tr class="hover:bg-gray-50 transition-colors duration-150">
            <td class="px-6 py-4 whitespace-nowrap">
                @if ($image)
                <img src="{{ $image }}" alt="Product Image" class="w-20 h-20 object-contain rounded-md border border-gray-200" />
                @else
                <div class="w-20 h-20 bg-gray-100 rounded-md border border-gray-200 flex items-center justify-center text-gray-400 text-sm">
                    No Image
                </div>
                @endif
            </td>

            <td class="px-6 py-4 font-semibold text-gray-900 text-sm whitespace-normal max-w-xs">
                {{ $product['title'] }}
            </td>

            <td class="px-6 py-4 text-gray-700 text-sm max-w-96 break-words whitespace-normal">
                {!! $product['descriptionHtml'] !!}
            </td>

            <td class="px-6 py-4 space-y-3 max-w-xs">
                @foreach($variants as $variantEdge)
                @php
                    $variant = $variantEdge['node'];
                    $titleParts = explode(' / ', $variant['title']);
                    $size = $titleParts[0] ?? '—';
                    $color = $titleParts[1] ?? '—';
                @endphp
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-md shadow-sm">
                    <div class="text-xs text-gray-500"><strong>Size:</strong> {{ $size }}</div>
                    <div class="text-xs text-gray-500"><strong>Color:</strong> {{ $color }}</div>
                    <div class="text-xs font-semibold text-gray-900"><strong>Price:</strong> ${{ number_format($variant['price'], 2) }}</div>
                </div>
                @endforeach
            </td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        @if($products['pageInfo']['hasPreviousPage'])
                        <a href="{{ route('home', array_merge(request()->query(), ['before' => $firstCursor])) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            ← Previous
                        </a>
                        @else
                        <span class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-md text-sm font-medium text-gray-400 cursor-not-allowed select-none">
                            ← Previous
                        </span>
                        @endif

                        @if($products['pageInfo']['hasNextPage'])
                        <a href="{{ route('home', array_merge(request()->query(), ['after' => $lastCursor])) }}"
                        class="inline-flex items-center px-4 py-2 border border-indigo-600 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Next →
                        </a>
                        @else
                        <span class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-md text-sm font-medium text-gray-400 cursor-not-allowed select-none">
                            Next →
                        </span>
                        @endif
                    </div>
                </td>
            </tr>
        </tfoot>

    </table>
    </div>

    <div id="add-product-section" class="p-6 hidden">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-2xl p-6 border">add-product-form
            <h2 class="text-2xl font-bold mb-6">Add New Product</h2>

            <form id="add-product-form" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Title</label>
                        <input type="text" name="title" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:border-blue-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Description</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:border-blue-300" required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Quantity</label>
                        <input type="number" name="quantity" min="0" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:border-blue-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Image</label>
                        <input type="file" name="image" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:border-blue-300" required>
                    </div>
                </div>

                <hr class="my-6 border-gray-300" />

                <div>
                    <h3 class="text-xl font-semibold mb-4">Variants</h3>

                    <div id="variants-container" class="space-y-4">
                    </div>

                    <button type="button" id="add-variant-btn"
                        class="mt-2 inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        + Add Variant
                    </button>
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="w-full md:w-auto bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                        Create Product
                    </button>
                    <button type="reset"
                        class="w-full md:w-auto bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400">
                        Reset Form
                    </button>
                </div>

                <div id="form-message" class="mt-4 text-sm text-center"></div>
            </form>
        </div>
    </div>



@endsection

@section('scripts') @parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
  const variantsContainer = $('#variants-container');

  function createVariantRow() {
    const $div = $(`
      <div class="grid grid-cols-5 gap-4 items-end">
        <div>
          <label class="block text-sm font-medium mb-1">Size</label>
          <input type="text" name="variants[][size]" class="w-full border p-2 rounded" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Color</label>
          <input type="text" name="variants[][color]" class="w-full border p-2 rounded" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Price</label>
          <input type="number" name="variants[][price]" step="0.01" min="0" class="w-full border p-2 rounded" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Quantity</label>
          <input type="number" name="variants[][quantity]" min="0" class="w-full border p-2 rounded" required>
        </div>
        <div>
          <button type="button" class="remove-variant-btn text-red-600 font-bold text-xl px-2 py-1 hover:text-red-800" aria-label="Remove variant">&times;</button>
        </div>
      </div>
    `);

    $div.find('.remove-variant-btn').click(function() {
      $div.remove();
    });

    return $div;
  }

  variantsContainer.append(createVariantRow());

  $('#add-variant-btn').click(function() {
    variantsContainer.append(createVariantRow());
  });

  $('#add-product-form').submit(function(e) {
    e.preventDefault();

    const $form = $(this);
    const messageDiv = $('#form-message');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
      url: $form.attr('action') || "{{ route('products.store') }}",
      method: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
        'Accept': 'application/json',
      },
      success: function(data) {
        if(data.success) {
          messageDiv.text(data.message).removeClass('text-red-600').addClass('mt-4 text-green-600 font-semibold');
          $form.trigger('reset');
          variantsContainer.empty();
          variantsContainer.append(createVariantRow());
        } else {
          messageDiv.text('Failed to create product.').removeClass('text-green-600').addClass('mt-4 text-red-600 font-semibold');
        }
      },
      error: function() {
        messageDiv.text('Error submitting form.').removeClass('text-green-600').addClass('mt-4 text-red-600 font-semibold');
      }
    });
  });
});
</script>
@endsection
