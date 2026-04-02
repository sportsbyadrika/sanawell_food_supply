'use client';

import api from '@/lib/api';
import { saveSession } from '@/lib/auth';
import { LoginResponse } from '@/types/auth';
import { useRouter } from 'next/navigation';
import { FormEvent, useState } from 'react';

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);

  async function onSubmit(event: FormEvent) {
    event.preventDefault();
    try {
      const { data } = await api.post<LoginResponse>('/auth/login', { email, password });
      saveSession(data.accessToken, data.user);
      router.push('/dashboard');
    } catch {
      setError('Invalid email or password');
    }
  }

  return (
    <main className="min-h-screen flex items-center justify-center bg-slate-100 p-4">
      <form className="w-full max-w-md bg-white p-6 rounded-lg shadow space-y-4" onSubmit={onSubmit}>
        <h1 className="text-2xl font-bold">Dew Route Login</h1>
        <input className="w-full border rounded px-3 py-2" type="email" placeholder="Email" value={email} onChange={(e) => setEmail(e.target.value)} required />
        <input className="w-full border rounded px-3 py-2" type="password" placeholder="Password" value={password} onChange={(e) => setPassword(e.target.value)} required />
        {error ? <p className="text-sm text-rose-600">{error}</p> : null}
        <button className="w-full bg-slate-900 text-white rounded py-2">Login</button>
      </form>
    </main>
  );
}
