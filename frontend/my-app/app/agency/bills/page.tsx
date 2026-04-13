'use client';

import { useMemo, useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import type { Bill } from '@/types';
import DataTable from '../components/DataTable';

const mockBills: Bill[] = [
  { id: 1102, customer: 'Acme Foods', amount: 890.22, status: 'paid', date: '2026-04-10' },
  { id: 1103, customer: 'Farm Fresh', amount: 420.1, status: 'pending', date: '2026-04-11' },
  { id: 1104, customer: 'Urban Market', amount: 1270.4, status: 'overdue', date: '2026-04-12' },
];

export default function BillsPage() {
  const [status, setStatus] = useState('all');
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');

  const { data, isLoading } = useQuery<Bill[]>({
    queryKey: ['bills', status, startDate, endDate],
    queryFn: async () => {
      try {
        const response = await api.get('/bills', {
          params: { status: status === 'all' ? undefined : status, startDate, endDate },
        });
        return response.data;
      } catch {
        return mockBills;
      }
    },
  });

  const filtered = useMemo(() => {
    return (data ?? []).filter((bill) => {
      const statusMatch = status === 'all' || bill.status === status;
      const fromMatch = !startDate || bill.date >= startDate;
      const toMatch = !endDate || bill.date <= endDate;
      return statusMatch && fromMatch && toMatch;
    });
  }, [data, endDate, startDate, status]);

  return (
    <div className="space-y-4">
      <h2 className="text-xl font-semibold text-white">Bills & Invoices</h2>

      <div className="grid gap-3 rounded-2xl border border-white/10 bg-white/5 p-4 md:grid-cols-4">
        <select
          value={status}
          onChange={(e) => setStatus(e.target.value)}
          className="rounded-lg border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100"
        >
          <option value="all">All status</option>
          <option value="paid">Paid</option>
          <option value="pending">Pending</option>
          <option value="overdue">Overdue</option>
        </select>
        <input
          type="date"
          value={startDate}
          onChange={(e) => setStartDate(e.target.value)}
          className="rounded-lg border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100"
        />
        <input
          type="date"
          value={endDate}
          onChange={(e) => setEndDate(e.target.value)}
          className="rounded-lg border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100"
        />
      </div>

      {isLoading ? (
        <p className="text-slate-300">Loading bills...</p>
      ) : (
        <DataTable
          columns={[
            { key: 'id', label: 'Invoice #' },
            { key: 'customer', label: 'Customer' },
            {
              key: 'amount',
              label: 'Amount',
              render: (value) => `$${Number(value).toFixed(2)}`,
            },
            {
              key: 'status',
              label: 'Status',
              render: (value) => (
                <span className="rounded-full bg-indigo-500/20 px-2 py-1 text-xs text-indigo-200">{String(value)}</span>
              ),
            },
            { key: 'date', label: 'Date' },
          ]}
          data={filtered}
        />
      )}
    </div>
  );
}
