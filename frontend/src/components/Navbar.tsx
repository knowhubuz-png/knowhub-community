'use client';
import Link from 'next/link';
import { useState } from 'react';
import { useAuth } from '@/providers/AuthProvider';
import { Menu, X, User, LogOut, Plus, Search } from 'lucide-react';
import SearchBar from './SearchBar';
import NotificationDropdown from './NotificationDropdown';

export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);
  const { user, logout } = useAuth();

  return (
    <nav className="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          {/* Logo */}
          <Link href="/" className="flex items-center space-x-2">
            <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
              <span className="text-white font-bold text-sm">KH</span>
            </div>
            <span className="font-bold text-xl text-gray-900 hidden sm:block">
              KnowHub
            </span>
          </Link>

          {/* Desktop Navigation */}
          <div className="hidden md:flex items-center space-x-8">
            <Link 
              href="/posts" 
              className="text-gray-700 hover:text-indigo-600 font-medium transition-colors"
            >
              Postlar
            </Link>
            <Link 
              href="/wiki" 
              className="text-gray-700 hover:text-indigo-600 font-medium transition-colors"
            >
              Wiki
            </Link>
            <Link 
              href="/tags" 
              className="text-gray-700 hover:text-indigo-600 font-medium transition-colors"
            >
              Teglar
            </Link>
          </div>

          {/* Search Bar */}
          <SearchBar className="hidden md:flex flex-1 max-w-md mx-8" />

          {/* Desktop Auth */}
          <div className="hidden md:flex items-center space-x-4">
            {user ? (
              <>
                <NotificationDropdown />
                <Link
                  href="/posts/create"
                  className="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                  <Plus className="w-4 h-4 mr-2" />
                  Post yozish
                </Link>
                <div className="relative group">
                  <button className="flex items-center space-x-2 text-gray-700 hover:text-indigo-600">
                    <img
                      src={user.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}`}
                      alt={user.name}
                      className="w-8 h-8 rounded-full"
                    />
                    <span className="font-medium">{user.name}</span>
                  </button>
                  <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <Link
                      href={`/profile/${user.username}`}
                      className="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50"
                    >
                      <User className="w-4 h-4 mr-2" />
                      Profil
                    </Link>
                    <button
                      onClick={logout}
                      className="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-50"
                    >
                      <LogOut className="w-4 h-4 mr-2" />
                      Chiqish
                    </button>
                  </div>
                </div>
              </>
            ) : (
              <div className="flex items-center space-x-4">
                <Link
                  href="/auth/login"
                  className="text-gray-700 hover:text-indigo-600 font-medium"
                >
                  Kirish
                </Link>
                <Link
                  href="/auth/register"
                  className="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                  Ro'yxatdan o'tish
                </Link>
              </div>
            )}
          </div>

          {/* Mobile menu button */}
          <button
            onClick={() => setIsOpen(!isOpen)}
            className="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100"
          >
            {isOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>

        {/* Mobile Navigation */}
        {isOpen && (
          <div className="md:hidden py-4 border-t border-gray-200">
            <div className="space-y-4">
              {/* Mobile Search */}
              <SearchBar onClose={() => setIsOpen(false)} />

              {/* Mobile Links */}
              <div className="space-y-2">
                <Link
                  href="/posts"
                  className="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg"
                  onClick={() => setIsOpen(false)}
                >
                  Postlar
                </Link>
                <Link
                  href="/wiki"
                  className="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg"
                  onClick={() => setIsOpen(false)}
                >
                  Wiki
                </Link>
                <Link
                  href="/tags"
                  className="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg"
                  onClick={() => setIsOpen(false)}
                >
                  Teglar
                </Link>
              </div>

              {/* Mobile Auth */}
              {user ? (
                <div className="space-y-2 pt-4 border-t border-gray-200">
                  <Link
                    href="/posts/create"
                    className="flex items-center px-3 py-2 bg-indigo-600 text-white rounded-lg"
                    onClick={() => setIsOpen(false)}
                  >
                    <Plus className="w-4 h-4 mr-2" />
                    Post yozish
                  </Link>
                  <Link
                    href={`/profile/${user.username}`}
                    className="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg"
                    onClick={() => setIsOpen(false)}
                  >
                    <img
                      src={user.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}`}
                      alt={user.name}
                      className="w-6 h-6 rounded-full mr-2"
                    />
                    {user.name}
                  </Link>
                  <button
                    onClick={() => {
                      logout();
                      setIsOpen(false);
                    }}
                    className="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg"
                  >
                    <LogOut className="w-4 h-4 mr-2" />
                    Chiqish
                  </button>
                </div>
              ) : (
                <div className="space-y-2 pt-4 border-t border-gray-200">
                  <Link
                    href="/auth/login"
                    className="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg"
                    onClick={() => setIsOpen(false)}
                  >
                    Kirish
                  </Link>
                  <Link
                    href="/auth/register"
                    className="block px-3 py-2 bg-indigo-600 text-white rounded-lg text-center"
                    onClick={() => setIsOpen(false)}
                  >
                    Ro'yxatdan o'tish
                  </Link>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </nav>
  );
}