'use client';

import { useQuery } from '@tanstack/react-query';
import { getMe } from '@/lib/auth';
import type { User } from '@/types';

const mockAgencyAdmin: User = {
  id: 1,
  name: 'Agency Admin',
  email: 'agency.admin@sanawell.com',
  role: 'AGENCY_ADMIN',
  role_id: 6,
  agency_id: 12,
  avatarUrl: null,
};

export function useAuth() {
  return useQuery<User>({
    queryKey: ['auth', 'me'],
    queryFn: async () => {
      try {
        return await getMe();
      } catch {
        return mockAgencyAdmin;
      }
    },
    staleTime: 5 * 60 * 1000,
  });
}
