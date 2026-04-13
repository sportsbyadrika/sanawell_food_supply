'use client';

import { useEffect, useState } from 'react';
import api from '@/lib/api';
import DataTable, { Column } from '../components/DataTable';
import { useQuery } from '@tanstack/react-query';
import type { Customer, PaginatedResponse } from '@/types';

const mockCustomers: Customer[] = [
  { id: 1, name: 'Acme Foods', email: 'acme@food.com', phone: '555-0192', status: 'active', createdAt: '2026-04-01' },
  { id: 2, name: 'Farm Fresh', email: 'team@farmfresh.com', phone: '555-0122', status: 'inactive', createdAt: '2026-03-28' },
  { id: 3, name: 'Urban Market', email: 'hello@urban.com', phone: '555-0184', status: 'active', createdAt: '2026-03-20' },
];

function useDebouncedValue<T>(value: T, delay = 400) {
  const [debounced, setDebounced] = useState(value);

  useEffect(() => {
    const timer = setTimeout(() => setDebounced(value), delay);
    return () => clearTimeout(timer);
  }, [value, delay]);

  return debounced;
}

export default function CustomersPage() {
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [sortBy, setSortBy] = useState<keyof Customer>('name');
  const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('asc');
  const debouncedSearch = useDebouncedValue(search);

  const { data, isLoading } = useQuery<PaginatedResponse<Customer>>({
    queryKey: ['customers', page, debouncedSearch, sortBy, sortDirection],
    queryFn: async () => {
      try {
        const response = await api.get('/customers', {
          params: { page, search: debouncedSearch, sortBy, sortDirection },
        });
        return response.data;
      } catch {
        const filtered = mockCustomers.filter((customer) =>
          customer.name.toLowerCase().includes(debouncedSearch.toLowerCase()),
        );
        return { data: filtered, total: filtered.length, page, pageSize: 10 };
      }
    },
  });

  const columns: Column<Customer>[] = [
    { key: 'name', label: 'Name', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'phone', label: 'Phone' },
    {
      key: 'status',
      label: 'Status',
      sortable: true,
      render: (value) => (
        <span
          className={`rounded-full px-2 py-1 text-xs ${
            value === 'active' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-500/30 text-slate-300'
          }`}
        >
          {String(value)}
        </span>
      ),
    },
    { key: 'createdAt', label: 'Created', sortable: true },
    {
      key: 'id',
      label: 'Actions',
      render: (_, row) => (
        <div className="flex gap-2">
          <button className="rounded-md bg-indigo-500/20 px-2 py-1 text-xs text-indigo-200">Edit</button>
          <button className="rounded-md bg-rose-500/20 px-2 py-1 text-xs text-rose-200">Delete</button>
        </div>
      ),
    },
  ];

  const handleSort = (key: keyof Customer) => {
    if (sortBy === key) {
      setSortDirection((prev) => (prev === 'asc' ? 'desc' : 'asc'));
    } else {
      setSortBy(key);
      setSortDirection('asc');
    }
  };

  return (
    <div className="space-y-4">
      <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h2 className="text-xl font-semibold text-white">Customers</h2>
        <input
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Search customer"
          className="w-full md:w-80 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100"
        />
      </div>

      {isLoading ? (
        <p className="text-slate-300">Loading customers...</p>
      ) : (
        <>
          <DataTable
            columns={columns}
            data={data?.data ?? []}
            sortBy={sortBy}
            sortDirection={sortDirection}
            onSort={handleSort}
          />

          <div className="flex items-center justify-between text-sm text-slate-300">
            <p>Total: {data?.total ?? 0}</p>
            <div className="space-x-2">
              <button
                onClick={() => setPage((prev) => Math.max(prev - 1, 1))}
                className="rounded-lg border border-white/10 px-3 py-1.5 hover:bg-white/5"
              >
                Previous
              </button>
              <button onClick={() => setPage((prev) => prev + 1)} className="rounded-lg border border-white/10 px-3 py-1.5 hover:bg-white/5">
                Next
              </button>
            </div>
          </div>
        </>
      )}
    </div>
  );
}
