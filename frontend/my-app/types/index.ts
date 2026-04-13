export type Role = 'SUPER_ADMIN' | 'AGENCY_ADMIN';

export interface User {
  id: number;
  name: string;
  email: string;
  role_id: number;
  role: Role;
  agency_id: number | null;
  avatarUrl?: string | null;
}

export interface Agency {
  id: number;
  name: string;
  code?: string;
}

export interface Customer {
  id: number;
  name: string;
  email: string;
  phone: string;
  status: 'active' | 'inactive';
  createdAt: string;
}

export interface Bill {
  id: number;
  customer: string;
  amount: number;
  status: 'paid' | 'pending' | 'overdue';
  date: string;
}

export interface DashboardStats {
  activeProducts: number;
  staffCount: number;
  driversCount: number;
  monthlyRevenue: number;
  revenueSeries: Array<{ name: string; revenue: number }>;
  ordersSeries: Array<{ name: string; orders: number }>;
  recentActivity: Array<{
    id: number;
    type: 'customer' | 'order';
    name: string;
    status: string;
    date: string;
  }>;
}

export interface PaginatedResponse<T> {
  data: T[];
  total: number;
  page: number;
  pageSize: number;
}
