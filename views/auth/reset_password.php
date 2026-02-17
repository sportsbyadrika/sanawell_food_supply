<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

        <h2 class="text-2xl font-semibold text-center mb-6">
            Reset Password
        </h2>

        <?php if (!empty($success)): ?>
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg mb-4 text-sm">
                <?= htmlspecialchars($success) ?>
            </div>

            <div class="text-center text-sm text-gray-500">
                You will be redirected shortly...
            </div>

            <?php if (!empty($redirect)): ?>
                <script>
                    setTimeout(function() {
                        window.location.href = "index.php?route=login";
                    }, 3000); // 3 seconds
                </script>
            <?php endif; ?>

        <?php else: ?>

            <?php if (!empty($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?route=reset_password_submit">

                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">New Password</label>
                    <input type="password"
                           name="password"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input type="password"
                           name="confirm_password"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                           required>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-2 rounded-lg hover:opacity-90 transition">
                    Update Password
                </button>

            </form>

        <?php endif; ?>

    </div>
</div>