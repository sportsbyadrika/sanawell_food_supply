"use client";

import { ReactNode } from "react";
import Link from "next/link";
import { Toaster } from "react-hot-toast";

export default function Layout({ children }: { children: ReactNode }) {
  const handleLogout = async () => {
    try {
      // optional backend call
      await fetch("http://localhost:4000/auth/logout", {
        method: "POST",
      });

      // 🔥 remove token
      localStorage.removeItem("token");

      // 🔥 redirect
      window.location.href = "/login";

    } catch (error) {
      console.error("Logout error:", error);
    }
  };
  return (
    <div className="min-h-screen bg-[#0f172a] text-white">

      {/* 🔥 Top Navbar */}
      <div className="bg-[#111827]/80 backdrop-blur-md border-b border-gray-700 px-6 py-4 flex justify-between items-center shadow-md">
        
        {/* Logo */}
        <h1 className="text-xl font-bold text-yellow-400 tracking-wide">
          Dew Route
        </h1>

        {/* Navigation */}
        <div className="flex gap-6 items-center text-gray-300">
          <Link href="/super-admin" className="hover:text-yellow-400 transition">
            Dashboard
          </Link>

          <Link href="/super-admin/agencies" className="hover:text-yellow-400 transition">
            Agencies
          </Link>
<button
  onClick={handleLogout}
  className="text-red-400 hover:text-red-500 transition"
>
  Logout
</button>
        </div>
      </div>

      {/* 🔥 Page Content */}
      <div className="p-6">{children}</div>

      {/* 🔥 Toast */}
      <Toaster position="top-right" />
    </div>
  );
}