export type UserRole = 'super_admin' | 'agency_admin' | 'office_staff' | 'driver';

export type UserSession = {
  id: number;
  name: string;
  email: string;
  role: UserRole;
  agencyId?: number | null;
};

export type LoginResponse = {
  accessToken: string;
  user: UserSession;
};
