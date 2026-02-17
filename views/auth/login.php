<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | SanaWell Product Delivery</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

   <script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primaryBlue: '#2563EB',    
          primaryBlueDark: '#1E40AF',
          secondaryTeal: '#0EA5A4',
          softGray: '#F8FAFC',
          mutedGray: '#64748B'
        }
      }
    }
  }
</script>
</head>

<body class="bg-slate-100 text-slate-800">
   
    <!-- CENTERED CONTENT -->
    <main class="bg-slate-50 pt-6 pb-10">

  <div class="max-w-6xl mx-auto p-6 bg-white rounded-2xl shadow-lg overflow-hidden md:flex">
            <!-- LEFT: Branding -->
         <div class="hidden md:flex flex-col justify-center
            bg-gradient-to-br from-blue-500 to-slate-500
            text-white p-10">
          
              <h1 class="text-3xl font-bold mb-3">
  SanaWell Product Delivery
</h1>

<p class="text-slate-100 text-sm leading-relaxed">
  A secure SaaS platform to manage agencies, deliveries,
  and rate cards — all in one place.
</p>

<div class="mt-8 text-xs text-slate-200">
  © 2026 SanaWell
</div>

            </div>

            <!-- RIGHT: Login Form -->
            <div class="p-8 md:p-12 flex items-center justify-center">
                <div class="w-full max-w-sm">

                    <h2 class="text-2xl font-semibold text-slate-900 mb-1">
  Welcome back 👋
</h2>

<p class="text-sm text-slate-500 mb-6">
  Securely sign in to manage deliveries and rate cards.
</p>



                    <?php if (!empty($error)): ?>
                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="index.php?route=login_post" class="space-y-5">
                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white 
       focus:outline-none focus:ring-2 focus:ring-blue-500
       focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required
                              class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white
       focus:outline-none focus:ring-2 focus:ring-blue-500
       focus:border-blue-500 transition">
                        </div>
                        <?php if ($this->config['roles']['SUPER_ADMIN']['id'] != ($_SESSION['user']['role_id'] ?? null)) : ?>
    <div class="text-right mt-2">
        <a href="index.php?route=forgot_password"
           class="text-sm text-blue-600 hover:underline">
            Forgot Password?
        </a>
    </div>
<?php endif; ?>
<button
  class="w-full rounded-xl bg-gradient-to-br from-blue-500 to-slate-500 text-white py-3 font-semibold
         hover:bg-blue- 500 to slate- 500
         shadow-md hover:shadow-lg
         transition-all duration-200
         active:scale-[0.98]">
  Login
</button>
                    </form>

                    <div class="mt-6 text-center text-xs text-gray-400">
                        Secure access • Role-based login
                    </div>

                </div>
            </div>

        </div>

    </main>

</body>
</html>
