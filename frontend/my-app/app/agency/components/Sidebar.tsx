'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { LayoutDashboard, Users, Receipt, PanelLeftClose, PanelLeftOpen } from 'lucide-react';
import { useState } from 'react';

const links = [
  { href: '/agency', label: 'Dashboard', icon: LayoutDashboard },
  { href: '/agency/customers', label: 'Customers', icon: Users },
  { href: '/agency/bills', label: 'Bills', icon: Receipt },
];

export default function Sidebar() {
  const pathname = usePathname();
  const [collapsed, setCollapsed] = useState(false);

  return (
    <aside
      className={`sticky top-0 h-screen border-r border-white/10 bg-gradient-to-b from-slate-900 via-slate-950 to-indigo-950 p-4 transition-all ${
        collapsed ? 'w-20' : 'w-72'
      }`}
    >
      <div className="mb-8 flex items-center justify-between">
        {!collapsed && <h2 className="text-lg font-semibold text-white">Agency Panel</h2>}
        <button
          onClick={() => setCollapsed((prev) => !prev)}
          className="rounded-lg border border-white/10 bg-white/5 p-2 text-slate-200 hover:bg-white/10"
        >
          {collapsed ? <PanelLeftOpen size={18} /> : <PanelLeftClose size={18} />}
        </button>
      </div>

      <nav className="space-y-2">
        {links.map((link) => {
          const Icon = link.icon;
          const active = pathname === link.href;

          return (
            <Link
              href={link.href}
              key={link.href}
              className={`flex items-center gap-3 rounded-xl px-3 py-2.5 transition ${
                active
                  ? 'bg-indigo-500/25 text-white shadow-lg shadow-indigo-500/20'
                  : 'text-slate-300 hover:bg-white/10 hover:text-white'
              }`}
            >
              <Icon size={18} />
              {!collapsed && <span className="text-sm font-medium">{link.label}</span>}
            </Link>
          );
        })}
      </nav>
    </aside>
  );
}
