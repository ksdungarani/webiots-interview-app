<nav class="bg-blue-700 shadow-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between">
            <div id="navbar-title" class="flex-shrink-0 text-white font-bold text-lg tracking-wide">
                Products List
            </div>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex space-x-4">
                <button onclick="showSection('products'); setNavbarTitle('Products List')"
                    class="px-4 py-2 rounded-md bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 text-white font-semibold transition">
                    All Products
                </button>
                <button onclick="showSection('add-product'); setNavbarTitle('Add Product')"
                    class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-300 text-white font-semibold transition">
                    Add Product
                </button>
            </div>
        </div>
    </div>

    <script>
        function showSection(section) {
            const productTable = document.getElementById('product-table-section');
            const addProductForm = document.getElementById('add-product-section');

            if (section === 'products') {
                productTable.classList.remove('hidden');
                addProductForm.classList.add('hidden');
            } else if (section === 'add-product') {
                productTable.classList.add('hidden');
                addProductForm.classList.remove('hidden');
            }
            }

        function setNavbarTitle(title) {
            document.getElementById('navbar-title').textContent = title;
        }
    </script>
</nav>
