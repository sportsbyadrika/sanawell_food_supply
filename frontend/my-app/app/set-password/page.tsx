"use client";

import { useSearchParams } from "next/navigation";
import { useState } from "react";

export default function SetPasswordPage() {
  const searchParams = useSearchParams();
  const userId = searchParams.get("userId");

  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");

  const handleSetPassword = async () => {
    const res = await fetch("http://localhost:4000/auth/set-password", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ userId: Number(userId), password }),
    });

    const data = await res.json();

    if (!res.ok) {
      setMessage(data.message || "Failed");
      return;
    }

    setMessage("Password updated. Please login again");

    setTimeout(() => {
      window.location.href = "/login";
    }, 1500);
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-black text-white">
      <div className="p-8 bg-white/10 rounded-xl w-96">
        <h2 className="text-xl mb-4">Set New Password</h2>

        <input
          type="password"
          placeholder="New password"
          className="w-full p-2 mb-4 text-black"
          onChange={(e) => setPassword(e.target.value)}
        />

        <button
          onClick={handleSetPassword}
          className="w-full bg-yellow-400 text-black p-2 rounded"
        >
          Update Password
        </button>

        {message && <p className="mt-3">{message}</p>}
      </div>
    </div>
  );
}