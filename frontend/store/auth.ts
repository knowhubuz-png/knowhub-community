import { create } from 'zustand';
import { login, register, logout } from '@/lib/auth';

type User = { id: number; name: string; email: string } | null;

interface AuthState {
  user: User;
  setUser: (u: User) => void;
  loginUser: (email: string, password: string) => Promise<void>;
  registerUser: (name: string, email: string, password: string) => Promise<void>;
  logoutUser: () => Promise<void>;
}

export const useAuth = create<AuthState>((set) => ({
  user: null,
  setUser: (u) => set({ user: u }),
  loginUser: async (email, password) => {
    const data = await login(email, password);
    set({ user: data.user });
  },
  registerUser: async (name, email, password) => {
    const data = await register(name, email, password);
    set({ user: data.user });
  },
  logoutUser: async () => {
    await logout();
    set({ user: null });
  },
}));

