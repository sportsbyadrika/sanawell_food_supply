import axios from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_BASE_URL ?? 'http://localhost:3001',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
  },
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401 && typeof window !== 'undefined') {
      window.location.href = '/login';
    }

    return Promise.reject({
      status: error.response?.status,
      message: error.response?.data?.message ?? error.message ?? 'Unexpected error',
      data: error.response?.data,
    });
  },
);

export default api;
