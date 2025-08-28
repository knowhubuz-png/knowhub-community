import axios from 'axios';

export const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/v1',
  withCredentials: true, // Sanctum cookies uchun
  headers: {
    Accept: 'application/json',
  },
});

