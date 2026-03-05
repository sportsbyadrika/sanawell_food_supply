<div class="max-w-3xl mx-auto mt-10">

    <div class="bg-white shadow-lg rounded-xl p-8">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                Add New Agency
            </h2>

            <a href="index.php?route=agencies"
               class="text-gray-500 hover:text-gray-700">
                ← Back
            </a>
        </div>

        <form method="POST" action="index.php?route=agencies_store" class="space-y-6">

            <input type="hidden" name="_csrf_token"
                   value="<?= htmlspecialchars($csrf_token) ?>">

            <!-- Agency Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Agency Name
                </label>
                <input type="text" name="name"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       required>
            </div>

            <!-- Contact Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Contact Number
                </label>
                <input type="text" name="contact_number"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       required>
            </div>

            <!-- Contact Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Contact Email
                </label>
                <input type="email" name="contact_email"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       required>
            </div>

            <!-- WhatsApp -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Whatsapp Number
                </label>
                <input type="text" name="whatsapp_number"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-slate-600 to-indigo-600  hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">
                    Create Agency
                </button>
            </div>

        </form>

    </div>
</div>