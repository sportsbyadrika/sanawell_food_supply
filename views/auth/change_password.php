<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primaryBlue: '#2563eb',   // blue-600
                        softGray: '#f1f5f9'       // slate-100
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen flex items-center justify-center bg-softGray">
<div class="max-w-6xl mx-auto p-6">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">

        <!-- Header -->
         
        <div class="text-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Change Password
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Please set a new password to continue
            </p>
        </div>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-3 text-sm text-red-600">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="post" action="index.php?route=change_password" class="space-y-5">

            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <!-- New Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    placeholder="Enter new password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-primaryBlue focus:border-primaryBlue"
                >
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>
                <input
                    type="password"
                    name="confirm_password"
                    required
                    placeholder="Re-enter new password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-primaryBlue focus:border-primaryBlue"
                >
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full rounded-lg bg-gradient-to-br from-blue-500 to-slate-500 text-white py-2 font-medium
                       hover:bg-blue-700 transition duration-200"
            >
                Update Password
            </button>

        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-gray-400">
            SanaWell Product Delivery • Secure Access
        </div>

    </div>

</body>
</html>