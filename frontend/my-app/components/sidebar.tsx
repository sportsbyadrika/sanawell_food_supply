'use client';

import Link from 'next/link';
import { Building2, LayoutDashboard, Package, Route, Truck, Users } from 'lucide-react';
import { UserRole } from '@/types/auth';

type Props = { role: UserRole };

const menuByRole: Record<UserRole, { href: string; label: string; icon: React.ReactNode }[]> = {
  super_admin: [{ href: '/dashboard', label: 'Agencies', icon: <Building2 size={18} /> }],
  agency_admin: [
    { href: '/dashboard', label: 'Overview', icon: <LayoutDashboard size={18} /> },
    { href: '/dashboard?tab=users', label: 'Staff', icon: <Users size={18} /> },
    { href: '/dashboard?tab=products', label: 'Products', icon: <Package size={18} /> },
    { href: '/dashboard?tab=routes', label: 'Routes', icon: <Route size={18} /> },
  ],
  office_staff: [
    { href: '/dashboard?tab=customers', label: 'Customers', icon: <Users size={18} /> },
    { href: '/dashboard?tab=deliveries', label: 'Deliveries', icon: <Truck size={18} /> },
    { href: '/dashboard?tab=billing', label: 'Billing', icon: <Package size={18} /> },
  ],
  driver: [{ href: '/dashboard?tab=deliveries', label: 'My Deliveries', icon: <Truck size={18} /> }],
};

export function Sidebar({ role }: Props) {
  return (
    <aside className="w-64 bg-slate-900 text-slate-100 min-h-screen p-4">
      <h1 className="text-lg font-bold mb-6">Dew Route</h1>
      <nav className="space-y-1">
        {menuByRole[role].map((item) => (
          <Link key={item.label} href={item.href} className="flex items-center gap-2 rounded-md px-3 py-2 hover:bg-slate-800">
            {item.icon}
            <span>{item.label}</span>
          </Link>
        ))}
      </nav>
    </aside>
  );
}
