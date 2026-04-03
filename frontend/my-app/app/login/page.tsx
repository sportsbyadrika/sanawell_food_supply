"use client";

import { useState } from "react";

export default function LoginPage() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  return (
    <div
      className="min-h-screen flex items-center justify-center bg-cover bg-center relative"
      style={{
        backgroundImage:
          "url('https://images.unsplash.com/photo-1553413077-190dd305871c')",
      }}
    >
      {/* DARK OVERLAY */}
     <div className="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/80 backdrop-blur-sm"></div>

      {/* CONTENT */}
      <div className="relative z-10 w-full max-w-6xl grid md:grid-cols-2 gap-10 px-6 items-center">

        {/* LEFT SIDE - DESCRIPTION */}
        <div className="text-white hidden md:block">
         <h1 className="text-5xl font-bold mb-6 leading-tight">
  Dew Route <span className="text-yellow-400">Product Delivery</span>
</h1>

<p className="text-lg text-gray-300 leading-relaxed max-w-lg">
  A secure SaaS platform to manage agencies, deliveries, and rate cards —
  all in one place. Track routes, optimize logistics, and deliver faster.
</p>

          <p className="mt-10 text-sm text-gray-400">
            © 2026 Dew Route
          </p>
        </div>

        {/* RIGHT SIDE - LOGIN CARD */}
        <div className="backdrop-blur-2xl bg-white/10 border border-white/20 shadow-[0_20px_60px_rgba(0,0,0,0.6)] rounded-2xl p-8 w-full max-w-md mx-auto transition-all duration-300 hover:scale-[1.02]">

          <h2 className="text-2xl font-bold text-white text-center mb-2">
            Welcome back 👋
          </h2>

          <p className="text-gray-300 text-center text-sm mb-6">
            Sign in to continue
          </p>

          <div className="space-y-4">

            <input
              type="email"
              placeholder="Email address"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full px-4 py-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            />

            <input
              type="password"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full px-4 py-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            />

            <p className="text-red-400 text-sm text-center">
              Invalid email or password
            </p>

          <button className="w-full py-3 rounded-lg bg-yellow-400 text-black font-semibold hover:bg-yellow-300 hover:shadow-lg transition">Login</button>
          

            <p className="text-gray-400 text-sm text-center">
              Forgot password?
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}