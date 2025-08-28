'use client';
import Link from 'next/link';
import { useAuth } from '@/store/auth';

export default function Navbar() {
  const { user, logoutUser } = useAuth();

  return (
    <nav className="bg-slate-900 text-white px-4 py-3 flex justify-between">
      <Link href="/" className="font-bold text-sky-400">KnowHub</Link>
      <div className="space-x-4">
        {user ? (
          <>
            <span>Salom, {user.name}</span>
            <button onClick={logoutUser} className="bg-sky-500 px-3 py-1 rounded">Chiqish</button>
          </>
        ) : (
          <>
            <Link href="/auth/login">Kirish</Link>
            <Link href="/auth/register">Ro‘yxatdan o‘tish</Link>
          </>
        )}
      </div>
    </nav>
  );
}

