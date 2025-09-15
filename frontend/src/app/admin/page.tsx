'use client';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/api';
import LoadingSpinner from '@/components/LoadingSpinner';
import { useAuth } from '@/providers/AuthProvider';
import { 
  Users, 
  FileText, 
  MessageCircle, 
  BookOpen, 
  Code, 
  BarChart3,
  Settings,
  Shield,
  TrendingUp,
  Activity,
  Bell,
  ThumbsUp,
  Database,
  Server,
  AlertTriangle,
  CheckCircle
} from 'lucide-react';
import Link from 'next/link';

async function getAdminDashboard() {
  const res = await api.get('/admin/dashboard');
  return res.data;
}

export default function AdminPage() {
  const { user } = useAuth();

  const { data: stats, isLoading, error } = useQuery({
    queryKey: ['admin', 'dashboard'],
    queryFn: getAdminDashboard,
    enabled: !!user,
    refetchInterval: 30000, // Har 30 soniyada yangilanadi
  });

  if (!user) {
    return (
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="text-center py-12">
          <Shield className="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h2 className="text-2xl font-bold text-gray-900 mb-2">Admin Panel</h2>
          <p className="text-gray-600 mb-6">
            Admin paneliga kirish uchun tizimga kiring
          </p>
          <Link
            href="/auth/login"
            className="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
          >
            Tizimga kirish
          </Link>
        </div>
      </div>
    );
  }

  if (isLoading) return <LoadingSpinner />;

  if (error) {
    return (
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="text-center py-12">
          <Shield className="w-16 h-16 text-red-300 mx-auto mb-4" />
          <h2 className="text-2xl font-bold text-gray-900 mb-2">Ruxsat yo'q</h2>
          <p className="text-gray-600">
            Sizda admin paneliga kirish huquqi yo'q
          </p>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      {/* Header */}
      <div className="mb-8">
        <div className="flex items-center mb-4">
          <Shield className="w-8 h-8 text-indigo-600 mr-3" />
          <h1 className="text-3xl font-bold text-gray-900">Admin Panel</h1>
        </div>
        <p className="text-gray-600">KnowHub Community boshqaruv paneli</p>
          <div className="flex items-center space-x-4">
            <div className="flex items-center text-green-600">
              <CheckCircle className="w-5 h-5 mr-2" />
              <span className="text-sm font-medium">Tizim Ishlayapti</span>
            </div>
            <div className="text-sm text-gray-500">
              Son yangilanish: {new Date().toLocaleTimeString('uz-UZ')}
            </div>
          </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div className="flex items-center">
            <Users className="w-8 h-8 text-blue-600" />
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600">Jami foydalanuvchilar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.users?.total || 0}</p>
              <p className="text-xs text-green-600">+{stats?.users?.new_this_week || 0} bu hafta</p>
              <p className="text-xs text-red-600">{stats?.users?.banned || 0} bloklangan</p>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div className="flex items-center">
            <FileText className="w-8 h-8 text-green-600" />
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600">Jami postlar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.posts?.total || 0}</p>
              <p className="text-xs text-green-600">+{stats?.posts?.this_week || 0} bu hafta</p>
              <p className="text-xs text-blue-600">{stats?.posts?.with_ai || 0} AI bilan</p>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div className="flex items-center">
            <MessageCircle className="w-8 h-8 text-purple-600" />
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600">Jami kommentlar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.comments?.total || 0}</p>
              <p className="text-xs text-green-600">+{stats?.comments?.this_week || 0} bu hafta</p>
              <p className="text-xs text-yellow-600">{stats?.comments?.high_score || 0} yuqori ball</p>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div className="flex items-center">
            <Code className="w-8 h-8 text-orange-600" />
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600">Kod ishga tushirish</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.code_runs?.total || 0}</p>
              <p className="text-xs text-green-600">{stats?.code_runs?.successful || 0} muvaffaqiyatli</p>
              <p className="text-xs text-red-600">{stats?.code_runs?.failed || 0} xato</p>
            </div>
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <Link
          href="/admin/users"
          className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
          <div className="flex items-center">
            <Users className="w-8 h-8 text-blue-600 mr-4" />
            <div>
              <h3 className="font-semibold text-gray-900">Foydalanuvchilar</h3>
              <p className="text-sm text-gray-600">Foydalanuvchilarni boshqarish</p>
            </div>
          </div>
        </Link>

        <Link
          href="/admin/posts"
          className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
          <div className="flex items-center">
            <FileText className="w-8 h-8 text-green-600 mr-4" />
            <div>
              <h3 className="font-semibold text-gray-900">Postlar</h3>
              <p className="text-sm text-gray-600">Postlarni boshqarish</p>
            </div>
          </div>
        </Link>

        <Link
          href="/admin/comments"
          className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
          <div className="flex items-center">
            <MessageCircle className="w-8 h-8 text-purple-600 mr-4" />
            <div>
              <h3 className="font-semibold text-gray-900">Kommentlar</h3>
              <p className="text-sm text-gray-600">Kommentlarni boshqarish</p>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          </div>
        </Link>

        <Link
          href="/admin/analytics"
          className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
          <div className="flex items-center">
            <BarChart3 className="w-8 h-8 text-indigo-600 mr-4" />
              {stats?.users?.banned > 0 && (
                <p className="text-xs text-red-600 mt-1">{stats.users.banned} bloklangan</p>
              )}
            <div>
              <h3 className="font-semibold text-gray-900">Analitika</h3>
              <p className="text-sm text-gray-600">Batafsil statistika</p>
            </div>
          </div>
        </Link>

        <Link
          href="/admin/settings"
          className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
          <div className="flex items-center">
            <Settings className="w-8 h-8 text-gray-600 mr-4" />
              <p className="text-xs text-gray-500 mt-1">{stats?.posts?.draft || 0} qoralama</p>
            <div>
              <h3 className="font-semibold text-gray-900">Sozlamalar</h3>
              <p className="text-sm text-gray-600">Tizim sozlamalari</p>
            </div>
          </div>
        </Link>

        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center">
            <Activity className="w-8 h-8 text-yellow-600 mr-4" />
            <div>
              <h3 className="font-semibold text-gray-900">Tizim holati</h3>
              <p className="text-sm text-green-600">Barcha tizimlar ishlayapti</p>
            </div>
          </div>
        </div>
      </div>
        <Link
          href="/admin/notifications"
          className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
          <div className="flex items-center">
            <Bell className="w-8 h-8 text-yellow-600 mr-4" />
            <div>
              <h3 className="font-semibold text-gray-900">Bildirishnomalar</h3>
              <p className="text-sm text-gray-600">Bildirishnomalarni boshqarish</p>
              {stats?.notifications?.unread > 0 && (
                <p className="text-xs text-yellow-600 mt-1">{stats.notifications.unread} o'qilmagan</p>
              )}
            </div>
          </div>
        </Link>

      {/* Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Bugungi statistika</h2>
          <div className="space-y-4">
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Yangi foydalanuvchilar</span>
              <span className="font-semibold text-gray-900">{stats?.users?.active_today || 0}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Yangi postlar</span>
              <span className="font-semibold text-gray-900">{stats?.posts?.today || 0}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Yangi kommentlar</span>
              <span className="font-semibold text-gray-900">{stats?.comments?.today || 0}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Kod ishga tushirish</span>
              <span className="font-semibold text-gray-900">{stats?.code_runs?.today || 0}</span>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div className="flex items-center">
        <Link
          href="/admin/maintenance"
          className="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
            <div className="ml-4">
            <Database className="w-8 h-8 text-red-600 mr-4" />
              <p className="text-2xl font-bold text-gray-900">{stats?.votes?.total || 0}</p>
              <h3 className="font-semibold text-gray-900">Texnik Xizmat</h3>
              <p className="text-sm text-gray-600">Cache, backup, logs</p>
            </div>
          </div>
        </Link>
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center">
            <Activity className="w-8 h-8 text-green-600 mr-4" />
            <div>
              <h3 className="font-semibold text-gray-900">Tizim holati</h3>
              <p className="text-sm text-green-600">Barcha tizimlar ishlayapti</p>
              <p className="text-xs text-gray-500 mt-1">Uptime: 99.9%</p>
            </div>
          </div>
        </div>
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Kontent holati</h2>
          <div className="space-y-4">
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Nashr etilgan postlar</span>
              <span className="font-semibold text-green-600">{stats?.posts?.published || 0}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Qoralama postlar</span>
              <span className="font-semibold text-yellow-600">{stats?.posts?.draft || 0}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Wiki maqolalar</span>
              <span className="font-semibold text-blue-600">{stats?.wiki?.articles || 0}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Kategoriyalar</span>
              <span className="font-semibold text-gray-900">{stats?.categories || 0}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
      {/* System Status */}
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Yangi ovozlar</span>
              <span className="font-semibold text-gray-900">{stats?.votes?.today || 0}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Bildirishnomalar</span>
              <span className="font-semibold text-gray-900">{stats?.notifications?.today || 0}</span>
            </div>
      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h2 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
          <Server className="w-5 h-5 mr-2" />
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Mashhur Dasturlash Tillari</h2>
          <div className="space-y-3">
            {stats?.code_runs?.by_language?.map((lang: any, index: number) => (
              <div key={lang.language} className="flex items-center justify-between">
                <div className="flex items-center">
                  <div className={`w-3 h-3 rounded-full mr-3 ${
                    index === 0 ? 'bg-yellow-500' :
                    index === 1 ? 'bg-gray-400' :
                    index === 2 ? 'bg-amber-600' : 'bg-gray-300'
                  }`}></div>
                  <span className="text-gray-700 capitalize">{lang.language}</span>
                </div>
                <span className="font-semibold text-gray-900">{lang.count}</span>
              </div>
            ))}
          </div>
        </div>
        </div>
      </div>
  );
}