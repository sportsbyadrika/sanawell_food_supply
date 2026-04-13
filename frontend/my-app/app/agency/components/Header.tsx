'use client';

import { LogOut } from 'lucide-react';
import { logout } from '@/lib/auth';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/hooks/useAuth';

export default function Header() {
  const { data: user } = useAuth();
  const router = useRouter();

  const handleLogout = async () => {
    await logout();
    router.replace('/login');
  };

  return (
    <header className="mb-6 flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-xl">
      <div>
        <p className="text-xs uppercase tracking-wide text-slate-400">Agency</p>
        <h1 className="text-lg font-semibold text-white">{user?.agency_id ? `Agency #${user.agency_id}` : 'Agency Workspace'}</h1>
      </div>

      <div className="flex items-center gap-3">
        <div className="h-10 w-10 rounded-full bg-indigo-500/30 grid place-items-center text-sm font-semibold text-white">
          {user?.name?.slice(0, 1) ?? 'A'}
        </div>
        <button
          onClick={handleLogout}
          className="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-200 transition hover:bg-white/10"
        >
          <LogOut size={16} /> Logout
        </button>
      </div>
    </header>
  );
}
