import axios from 'axios';

export const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/v1',
  withCredentials: true,
  headers: {
    Accept: 'application/json',
  },
});

// Xatolarni global tutish
api.interceptors.response.use(
  response => response,
  error => {
    console.error('API error:', error.response?.data || error.message);
    throw error;
  }
);


