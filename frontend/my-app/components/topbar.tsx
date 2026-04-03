'use client';

import { clearSession } from '@/lib/auth';
import { UserSession } from '@/types/auth';
import { useRouter } from 'next/navigation';

export function Topbar({ user }: { user: UserSession }) {
  const router = useRouter();

  return (
    <header className="bg-white border-b px-6 py-3 flex justify-between items-center">
      <div>
        <h2 className="font-semibold">Welcome, {user.name}</h2>
        <p className="text-sm text-slate-500">Role: {user.role} {user.agencyId ? `• Agency #${user.agencyId}` : ''}</p>
      </div>
      <button
        className="rounded bg-slate-900 text-white px-3 py-2 text-sm"
        onClick={() => {
          clearSession();
          router.push('/login');
        }}
      >
        Logout
      </button>
    </header>
  );
}
