import { UserSession } from '@/types/auth';

const USER_KEY = 'user';

export function saveSession(token: string, user: UserSession) {
  localStorage.setItem('accessToken', token);
  localStorage.setItem(USER_KEY, JSON.stringify(user));
  document.cookie = `accessToken=${token}; path=/; SameSite=Lax`;
}

export function getSession(): UserSession | null {
  const raw = localStorage.getItem(USER_KEY);
  return raw ? (JSON.parse(raw) as UserSession) : null;
}

export function clearSession() {
  localStorage.removeItem('accessToken');
  localStorage.removeItem(USER_KEY);
  document.cookie = 'accessToken=; Max-Age=0; path=/';
}
