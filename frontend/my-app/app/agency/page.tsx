'use client';

import { DollarSign, Package, Truck, Users } from 'lucide-react';
import { ResponsiveContainer, LineChart, Line, CartesianGrid, XAxis, YAxis, Tooltip, BarChart, Bar } from 'recharts';
import ChartCard from './components/ChartCard';
import DataTable from './components/DataTable';
import StatsCard from './components/StatsCard';
import { useDashboard } from '@/hooks/useDashboard';

export default function AgencyDashboardPage() {
  const { data, isLoading, isError } = useDashboard();

  if (isLoading) {
    return <p className="text-slate-300">Loading dashboard...</p>;
  }

  if (isError || !data) {
    return <p className="text-rose-400">Failed to load dashboard data.</p>;
  }

  return (
    <div className="space-y-6">
      <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <StatsCard title="Active Products" value={data.activeProducts} icon={Package} color="bg-indigo-500/60" />
        <StatsCard title="Staff Count" value={data.staffCount} icon={Users} color="bg-cyan-500/60" />
        <StatsCard title="Drivers Count" value={data.driversCount} icon={Truck} color="bg-violet-500/60" />
        <StatsCard
          title="Monthly Revenue"
          value={`$${data.monthlyRevenue.toLocaleString()}`}
          icon={DollarSign}
          color="bg-emerald-500/60"
        />
      </div>

      <div className="grid gap-4 xl:grid-cols-2">
        <ChartCard title="Revenue Over Time">
          <ResponsiveContainer width="100%" height="100%">
            <LineChart data={data.revenueSeries}>
              <CartesianGrid strokeDasharray="3 3" stroke="#334155" />
              <XAxis dataKey="name" stroke="#94a3b8" />
              <YAxis stroke="#94a3b8" />
              <Tooltip />
              <Line type="monotone" dataKey="revenue" stroke="#6366f1" strokeWidth={3} />
            </LineChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Orders Per Day">
          <ResponsiveContainer width="100%" height="100%">
            <BarChart data={data.ordersSeries}>
              <CartesianGrid strokeDasharray="3 3" stroke="#334155" />
              <XAxis dataKey="name" stroke="#94a3b8" />
              <YAxis stroke="#94a3b8" />
              <Tooltip />
              <Bar dataKey="orders" fill="#06b6d4" radius={[8, 8, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <section>
        <h2 className="mb-3 text-lg font-semibold text-white">Recent Activity</h2>
        <DataTable
          columns={[
            { key: 'type', label: 'Type' },
            { key: 'name', label: 'Name' },
            { key: 'status', label: 'Status' },
            { key: 'date', label: 'Date' },
          ]}
          data={data.recentActivity}
        />
      </section>
    </div>
  );
}
