'use client';

import { DataTable } from '@/components/data-table';
import { Sidebar } from '@/components/sidebar';
import { StatCards } from '@/components/stat-cards';
import { Topbar } from '@/components/topbar';
import { getSession } from '@/lib/auth';
import { UserSession } from '@/types/auth';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';

export default function DashboardPage() {
  const [user, setUser] = useState<UserSession | null>(null);
  const router = useRouter();

  useEffect(() => {
    const session = getSession();
    if (!session) {
      router.push('/login');
      return;
    }
    setUser(session);
  }, [router]);

  if (!user) return null;

  return (
    <div className="flex">
      <Sidebar role={user.role} />
      <div className="flex-1 min-h-screen">
        <Topbar user={user} />
        <main className="p-6 space-y-6">
          <StatCards />
          <div className="bg-white p-4 border rounded-lg">
            <h3 className="font-semibold mb-2">Monthly Billing Summary</h3>
            <p className="text-sm text-slate-600">Collected: $15,200 • Outstanding: $4,380 • Success Rate: 91%</p>
          </div>
          <DataTable />
        </main>
      </div>
    </div>
  );
}
