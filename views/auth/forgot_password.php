
<div class="min-h-screen flex items-start justify-center bg-gray-100 pt-28">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        
        <h2 class="text-xl font-semibold mb-6 text-center">
            Forgot Password
        </h2>

        <form method="POST" action="index.php?route=forgot_password_send">
            
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Email Address
                </label>
                <input type="email"
                       name="email"
                       required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-slate-700  hover:bg-blue-700 text-white py-2 rounded-lg">
                Send Reset Link
            </button>
        </form>

       <?php if (!empty($success)): ?>

    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
        ✔ Reset link generated successfully.<br>
        Redirecting to login in 3 seconds...
    </div>

    <?php if (!empty($dev_link)): ?>
        <div class="mt-3 text-sm">
            <strong>DEV Reset Link:</strong><br>
            <a href="<?= $dev_link ?>" class="text-blue-600 underline break-all">
                <?= $dev_link ?>
            </a>
        </div>
    <?php endif; ?>

    <script>
        setTimeout(function () {
            window.location.href = "index.php?route=login";
        }, 3000);
    </script>

<?php endif; ?>

        <div class="mt-4 text-center">
            <a href="index.php?route=login"
               class="text-sm text-blue-600 hover:underline">
                Back to Login
            </a>
        </div>

    </div>
</div>
