import axios from 'axios';

export const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/v1',
  withCredentials: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
  timeout: 30000,
});

// Request interceptor - token qo'shish
api.interceptors.request.use(
  (config) => {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('auth_token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    }
    return config;
  },
  (error) => {
    console.error('Request Error:', error);
    return Promise.reject(error);
  }
);

// Response interceptor - xatolarni boshqarish
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response) {
      const { status, data } = error.response;

      // 401 - Unauthorized
      if (status === 401) {
        if (typeof window !== 'undefined') {
          localStorage.removeItem('auth_token');
          if (!window.location.pathname.includes('/auth/login')) {
            window.location.href = '/auth/login?redirect=' + window.location.pathname;
          }
        }
      }

      // 403 - Forbidden
      if (status === 403) {
        console.error('Access Denied:', data);
      }

      // 404 - Not Found
      if (status === 404) {
        console.error('Not Found:', data);
      }

      // 422 - Validation Error
      if (status === 422) {
        console.error('Validation Error:', data);
      }

      // 500+ - Server Error
      if (status >= 500) {
        console.error('Server Error:', data);
      }

      console.error('API Error Response:', {
        status,
        url: error.config?.url,
        method: error.config?.method,
        data: data,
      });
    } else if (error.request) {
      console.error('Network Error - No response received:', {
        url: error.config?.url,
        message: 'Server javob bermadi. Internetga ulanishni tekshiring.',
      });
    } else {
      console.error('Request Setup Error:', error.message);
    }

    return Promise.reject(error);
  }
);


