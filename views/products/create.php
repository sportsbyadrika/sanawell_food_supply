<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-semibold mb-6">Add New Product</h2>

    <form method="POST" action="index.php?route=products_store" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">

        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="cropped_image" id="croppedImage">
          
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Product Name</label>
            <input type="text" name="name"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        

        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Description</label>
            <textarea name="description"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
          <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Product Type
                </label>

                <select name="variant" class="w-full border rounded-lg px-4 py-2">
                    <option value="">select type</option>
    <option value="packed_500ml" <?= ($variant ?? '')=='packed_500ml'?'selected':'' ?>>
          🥛 Packed (500 ml)
    </option>

    <option value="loose_500ml" <?= ($variant ?? '')=='loose_500ml'?'selected':'' ?>>
         🧴 Loose (500 ml)
    </option>

    <option value="loose_1l" <?= ($variant ?? '')=='loose_1l'?'selected':'' ?>>
         🧴 Loose (1 L)
    </option>
</select>
            </div>
        <div class="mb-4">
    <label class="block text-sm font-medium mb-2">Product Image</label>

<input type="file"
       name="image"
       accept="image/*"
       class="border p-2 rounded w-full">

    <div class="mt-4">
        <img id="imagePreview" class="max-w-xs hidden rounded shadow">
    </div>

    <input type="hidden" name="cropped_image" id="croppedImage">
</div>

        <div class="flex justify-between">
            <a href="index.php?route=products"
               class="text-gray-600 hover:text-black">
               ← Back to Products
            </a>

            <button type="submit"
                class="bg-gradient-to-r from-blue-600 to-slate-600 text-white px-4 py-2 rounded">
                Add Product
            </button>
        </div>
    </form>

<script>
let cropper;
const input = document.getElementById('imageInput');
const preview = document.getElementById('imagePreview');

input.addEventListener('change', function (e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = function (event) {
        preview.src = event.target.result;
        preview.classList.remove('hidden');

        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(preview, {
            aspectRatio: 1,
            viewMode: 1,
        });
    };

    reader.readAsDataURL(file);
});
</script>
</div>