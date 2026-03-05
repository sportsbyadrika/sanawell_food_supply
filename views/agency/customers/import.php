<div class="max-w-3xl mx-auto mt-12">

    <div class="bg-white shadow-lg rounded-xl p-8">

        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Import Customers
        </h1>

        <form action="index.php?route=agency_customers_import"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">

            <!-- Upload Box -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-3">
                    Upload CSV File
                </label>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">

                    <input type="file"
                           name="csv_file"
                           required
                           class="mx-auto block text-sm text-gray-600
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0
                           file:text-sm file:font-semibold
                           file:bg-blue-50 file:text-blue-700
                           hover:file:bg-blue-100">

                    <p class="text-xs text-gray-500 mt-3">
                        Only CSV files are allowed. Maximum file size: 5MB.
                    </p>

                </div>

            </div>

            <!-- CSV Example -->
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg text-sm text-gray-700">

                <p class="font-semibold mb-1">
                    CSV Format Example
                </p>

                <p>
                    Name, Mobile, WhatsApp, Customer Type, Route, Latitude, Longitude
                </p>

                <a href="assets/sample_customers.csv"
                   class="text-blue-600 text-sm hover:underline mt-2 inline-block">
                    Download Sample CSV
                </a>

            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-6 pt-2">

                <button type="submit"
                        class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    Upload Customers
                </button>

                <a href="index.php?route=customers"
                   class="text-gray-600 hover:underline">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>