import api from '@/lib/api';
import type { User } from '@/types';

export async function login(email: string, password: string) {
  const response = await api.post('/auth/login', { email, password });
  return response.data as { user: User; accessToken?: string };
}

export async function getMe() {
  const response = await api.get('/auth/me');
  return response.data as User;
}

export async function logout() {
  try {
    await api.post('/auth/logout');
  } catch {
    // no-op: fallback to client redirect
  }
}
