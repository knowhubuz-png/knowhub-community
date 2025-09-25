'use client';
import { createContext, useContext, useEffect, useState } from 'react';
import { api } from '@/lib/api';

interface User {
  id: number;
  name: string;
  username: string;
  email?: string;
  avatar_url?: string;
  xp: number;
  bio?: string;
}

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, username: string, email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  checkUser: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  const checkUser = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      if (token) {
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const response = await api.get('/profile/me');
        setUser(response.data);
      } else {
        // Clear user data if no token
        setUser(null);
      }
    } catch (error) {
      localStorage.removeItem('auth_token');
      delete api.defaults.headers.common['Authorization'];
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    checkUser();
  }, []);

  const login = async (email: string, password: string) => {
    const response = await api.post('/auth/email/login', { email, password });
    const { token, user: userData } = response.data;
    
    localStorage.setItem('auth_token', token);
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    setUser(userData);
  };

  const register = async (name: string, username: string, email: string, password: string) => {
    const response = await api.post('/auth/email/register', { name, username, email, password });
    const { token, user: userData } = response.data;
    
    localStorage.setItem('auth_token', token);
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    setUser(userData);
  };

  const logout = async () => {
    try {
      await api.post('/auth/email/logout');
    } catch (error) {
      // Ignore logout errors
    } finally {
      localStorage.removeItem('auth_token');
      delete api.defaults.headers.common['Authorization'];
      setUser(null);
    }
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout, checkUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}