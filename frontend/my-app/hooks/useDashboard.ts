'use client';

import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import type { DashboardStats } from '@/types';

const mockDashboard: DashboardStats = {
  activeProducts: 128,
  staffCount: 22,
  driversCount: 14,
  monthlyRevenue: 82450,
  revenueSeries: [
    { name: 'Jan', revenue: 13200 },
    { name: 'Feb', revenue: 15800 },
    { name: 'Mar', revenue: 17900 },
    { name: 'Apr', revenue: 21100 },
    { name: 'May', revenue: 19450 },
  ],
  ordersSeries: [
    { name: 'Mon', orders: 45 },
    { name: 'Tue', orders: 50 },
    { name: 'Wed', orders: 63 },
    { name: 'Thu', orders: 56 },
    { name: 'Fri', orders: 67 },
    { name: 'Sat', orders: 39 },
    { name: 'Sun', orders: 30 },
  ],
  recentActivity: [
    { id: 1, type: 'customer', name: 'Acme Foods', status: 'active', date: '2026-04-12' },
    { id: 2, type: 'order', name: 'Order #1789', status: 'completed', date: '2026-04-12' },
    { id: 3, type: 'customer', name: 'Fresh Local', status: 'inactive', date: '2026-04-11' },
  ],
};

export function useDashboard() {
  return useQuery<DashboardStats>({
    queryKey: ['agency', 'dashboard'],
    queryFn: async () => {
      try {
        const response = await api.get('/agency/dashboard');
        return response.data;
      } catch {
        return mockDashboard;
      }
    },
    refetchInterval: 60 * 1000,
  });
}
