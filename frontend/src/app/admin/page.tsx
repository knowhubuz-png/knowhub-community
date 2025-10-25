'use client';
import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/providers/AuthProvider';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/api';
import { 
  LayoutDashboard,
  Users,
  FileText,
  MessageSquare,
  Settings,
  BarChart3,
  Shield,
  Database,
  Bell,
  Image,
  Tag,
  TrendingUp,
  AlertTriangle,
  CheckCircle,
  Clock,
  Activity,
  Server,
  Globe,
  Mail,
  Ban,
  Edit,
  Trash2,
  Eye,
  Download,
  BookOpen,
  Code,
  Zap,
  ArrowLeft
} from 'lucide-react';
import LoadingSpinner from '@/components/LoadingSpinner';

interface AdminStats {
  users: { total: number; new_today: number; active: number };
  posts: { total: number; new_today: number; published: number; draft: number };
  comments: { total: number; new_today: number; pending: number };
  wiki: { articles: number; new_today: number; published: number };
  reports: { total: number; pending: number; resolved: number };
  system: { uptime: string; memory_usage: string; disk_usage: string };
}

interface RecentActivity {
  id: number;
  type: 'user' | 'post' | 'comment' | 'wiki';
  action: 'created' | 'updated' | 'deleted' | 'reported';
  user: { name: string; avatar_url?: string };
  target: string;
  created_at: string;
  ip_address?: string;
}

interface SystemLog {
  id: number;
  level: 'info' | 'warning' | 'error' | 'debug';
  message: string;
  context: string;
  created_at: string;
  ip_address: string;
}

async function getAdminStats() {
  try {
    const res = await api.get('/admin/dashboard');
    return res.data;
  } catch (error) {
    console.error('Error fetching admin stats:', error);
    return null;
  }
}

async function getRecentActivity() {
  try {
    const res = await api.get('/admin/activity');
    return res.data;
  } catch (error) {
    console.error('Error fetching recent activity:', error);
    return { data: [] };
  }
}

async function getSystemLogs() {
  try {
    const res = await api.get('/admin/logs');
    return res.data;
  } catch (error) {
    console.error('Error fetching system logs:', error);
    return { data: [] };
  }
}

export default function AdminPage() {
  const router = useRouter();
  const { user, loading: authLoading } = useAuth();
  const [activeTab, setActiveTab] = useState('dashboard');
  const [stats, setStats] = useState<AdminStats | null>(null);
  const [statsLoading, setStatsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const { data: activityData } = useQuery({
    queryKey: ['admin-activity'],
    queryFn: getRecentActivity,
    retry: 1,
    enabled: activeTab === 'activity',
  });

  const { data: logsData } = useQuery({
    queryKey: ['admin-logs'],
    queryFn: getSystemLogs,
    retry: 1,
    enabled: activeTab === 'logs',
  });

  useEffect(() => {
    if (!authLoading && !user) {
      router.push('/auth/login?redirect=/admin');
      return;
    }

    if (!authLoading && user && !user.is_admin) {
      router.push('/');
      return;
    }
  }, [user, authLoading, router]);

  useEffect(() => {
    const loadStats = async () => {
      if (!user?.is_admin) return;

      try {
        setStatsLoading(true);
        setError(null);
        const data = await getAdminStats();
        if (data) {
          setStats(data);
        } else {
          setError('Ma\'lumotlarni yuklashda xatolik yuz berdi');
        }
      } catch (error: any) {
        console.error('Error loading stats:', error);
        setError(error?.response?.data?.message || 'Xatolik yuz berdi');
      } finally {
        setStatsLoading(false);
      }
    };

    if (activeTab === 'dashboard' && user?.is_admin) {
      loadStats();
    }
  }, [activeTab, user]);

  if (authLoading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <LoadingSpinner />
      </div>
    );
  }

  if (!user || !user.is_admin) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center max-w-md">
          <div className="text-gray-400 text-6xl mb-4">🔒</div>
          <h1 className="text-2xl font-bold text-gray-900 mb-4">Ruxsat berilmagan</h1>
          <p className="text-gray-600 mb-6">Bu sahifaga faqat administratorlar kirishi mumkin</p>
          <button
            onClick={() => router.push('/')}
            className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            <ArrowLeft className="w-4 h-4 mr-2" />
            Bosh sahifaga qaytish
          </button>
        </div>
      </div>
    );
  }

  const tabs = [
    { id: 'dashboard', name: 'Dashboard', icon: LayoutDashboard },
    { id: 'users', name: 'Foydalanuvchilar', icon: Users },
    { id: 'posts', name: 'Postlar', icon: FileText },
    { id: 'comments', name: 'Kommentlar', icon: MessageSquare },
    { id: 'wiki', name: 'Wiki', icon: BookOpen },
    { id: 'reports', name: 'Hisobotlar', icon: AlertTriangle },
    { id: 'activity', name: 'Faoliyat', icon: Activity },
    { id: 'logs', name: 'Loglar', icon: Database },
    { id: 'settings', name: 'Sozlamalar', icon: Settings },
    { id: 'system', name: 'Tizim', icon: Server },
  ];

  const renderDashboard = () => (
    <div className="space-y-6">
      {/* Stats Overview */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-600">Jami Foydalanuvchilar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.users.total || 0}</p>
              <p className="text-xs text-green-600">+{stats?.users.new_this_week || 0} bu hafta</p>
              <p className="text-xs text-blue-600">{stats?.users.online_now || 0} online</p>
            </div>
            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <Users className="w-6 h-6 text-blue-600" />
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-600">Jami Postlar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.posts.total || 0}</p>
              <p className="text-xs text-green-600">+{stats?.posts.today || 0} bugun</p>
              <p className="text-xs text-orange-600">{stats?.posts.trending || 0} trend</p>
            </div>
            <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <FileText className="w-6 h-6 text-green-600" />
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-600">Kommentlar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.comments.total || 0}</p>
              <p className="text-xs text-green-600">+{stats?.comments.today || 0} bugun</p>
              <p className="text-xs text-red-600">{stats?.comments.pending_moderation || 0} kutilmoqda</p>
            </div>
            <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <MessageSquare className="w-6 h-6 text-purple-600" />
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-600">Wiki Maqolalar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.wiki.articles || 0}</p>
              <p className="text-xs text-green-600">{stats?.wiki.published || 0} nashr</p>
              <p className="text-xs text-blue-600">{stats?.wiki.proposals || 0} taklif</p>
            </div>
            <div className="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <BookOpen className="w-6 h-6 text-yellow-600" />
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-600">Kod Ishga Tushirish</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.code_runs.total || 0}</p>
              <p className="text-xs text-green-600">{stats?.code_runs.successful || 0} muvaffaq</p>
              <p className="text-xs text-gray-600">{stats?.code_runs.avg_runtime || 0}ms o'rtacha</p>
            </div>
            <div className="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
              <Code className="w-6 h-6 text-indigo-600" />
            </div>
          </div>
        </div>
      </div>

      {/* Performance Metrics */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">🚀 Performance</h3>
          <div className="space-y-3">
            <div className="flex justify-between">
              <span className="text-gray-600">O'rtacha javob vaqti:</span>
              <span className="font-medium">{stats?.performance?.avg_response_time || 'N/A'}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Cache hit rate:</span>
              <span className="font-medium text-green-600">{stats?.performance?.cache_hit_rate || 'N/A'}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Sekin so'rovlar:</span>
              <span className="font-medium">{stats?.performance?.slow_queries || 0}</span>
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">🔒 Xavfsizlik</h3>
          <div className="space-y-3">
            <div className="flex justify-between">
              <span className="text-gray-600">Muvaffaqiyatsiz kirishlar:</span>
              <span className="font-medium">{stats?.security?.failed_logins_today || 0}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Bloklangan IP:</span>
              <span className="font-medium">{stats?.security?.blocked_ips || 0}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Shubhali faoliyat:</span>
              <span className="font-medium">{stats?.security?.suspicious_activity || 0}</span>
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">⚙️ Tizim</h3>
          <div className="space-y-3">
            <div className="flex justify-between">
              <span className="text-gray-600">Queue jobs:</span>
              <span className="font-medium">{stats?.system?.queue_jobs || 0}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Failed jobs:</span>
              <span className="font-medium text-red-600">{stats?.system?.failed_jobs || 0}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Uptime:</span>
              <span className="font-medium text-green-600">{stats?.system?.uptime || 'N/A'}</span>
            </div>
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 className="text-lg font-semibold text-gray-900 mb-4">🛠️ Tezkor Harakatlar</h3>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          <button
            onClick={() => {
              fetch('/api/v1/admin/cache/clear', { method: 'POST' })
                .then(() => alert('Cache tozalandi'))
                .catch(() => alert('Xatolik yuz berdi'));
            }}
            className="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
          >
            <Database className="w-8 h-8 text-blue-600 mb-2" />
            <span className="text-sm font-medium text-blue-700">Cache Tozalash</span>
          </button>
          
          <button
            onClick={() => {
              fetch('/api/v1/admin/system/optimize', { method: 'POST' })
                .then(() => alert('Tizim optimallashtirildi'))
                .catch(() => alert('Xatolik yuz berdi'));
            }}
            className="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors"
          >
            <Zap className="w-8 h-8 text-green-600 mb-2" />
            <span className="text-sm font-medium text-green-700">Optimizatsiya</span>
          </button>
          
          <button
            onClick={() => {
              fetch('/api/v1/admin/database/backup', { method: 'POST' })
                .then(() => alert('Backup yaratildi'))
                .catch(() => alert('Xatolik yuz berdi'));
            }}
            className="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors"
          >
            <Server className="w-8 h-8 text-purple-600 mb-2" />
            <span className="text-sm font-medium text-purple-700">Database Backup</span>
          </button>
          
          <button
            onClick={() => window.location.reload()}
            className="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors"
          >
            <Activity className="w-8 h-8 text-orange-600 mb-2" />
            <span className="text-sm font-medium text-orange-700">Yangilash</span>
          </button>
        </div>
      </div>
    </div>
  );

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto py-10 px-4">
        <div className="mb-8 flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Admin Panel</h1>
            <p className="text-gray-600 mt-1">Xush kelibsiz, {user.name}</p>
          </div>
          <button
            onClick={() => router.push('/')}
            className="inline-flex items-center px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <ArrowLeft className="w-4 h-4 mr-2" />
            Bosh sahifa
          </button>
        </div>

        {error && (
          <div className="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div className="flex items-center">
              <AlertTriangle className="w-5 h-5 text-red-600 mr-2" />
              <p className="text-red-800">{error}</p>
            </div>
          </div>
        )}

        <div className="mb-8 flex space-x-4 overflow-x-auto pb-2">
          {tabs.map((tab) => (
            <button
              key={tab.id}
              className={`flex items-center px-4 py-2 rounded-lg font-medium transition-colors ${activeTab === tab.id ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-100'}`}
              onClick={() => setActiveTab(tab.id)}
            >
              <tab.icon className="w-5 h-5 mr-2" />
              {tab.name}
            </button>
          ))}
        </div>
        <div>
          {statsLoading && activeTab === 'dashboard' ? (
            <div className="flex items-center justify-center py-20">
              <LoadingSpinner />
            </div>
          ) : activeTab === 'dashboard' ? (
            renderDashboard()
          ) : activeTab === 'activity' ? (
            <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">So'nggi faoliyat</h2>
              {activityData?.data?.length > 0 ? (
                <div className="space-y-3">
                  {activityData.data.slice(0, 20).map((activity: any) => (
                    <div key={activity.id} className="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                      <div className="flex items-center space-x-3">
                        <div className="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                          {activity.type === 'post' && <FileText className="w-5 h-5 text-blue-600" />}
                          {activity.type === 'comment' && <MessageSquare className="w-5 h-5 text-green-600" />}
                          {activity.type === 'user' && <Users className="w-5 h-5 text-purple-600" />}
                          {activity.type === 'wiki' && <BookOpen className="w-5 h-5 text-yellow-600" />}
                        </div>
                        <div>
                          <p className="text-sm font-medium text-gray-900">{activity.target}</p>
                          <p className="text-xs text-gray-500">{activity.user?.name} - {activity.action}</p>
                        </div>
                      </div>
                      <span className="text-xs text-gray-400">{new Date(activity.created_at).toLocaleDateString('uz')}</span>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-gray-500 text-center py-8">Faoliyat topilmadi</p>
              )}
            </div>
          ) : activeTab === 'logs' ? (
            <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Tizim loglari</h2>
              {logsData?.data?.length > 0 ? (
                <div className="space-y-2">
                  {logsData.data.slice(0, 50).map((log: any, idx: number) => (
                    <div key={idx} className="flex items-start space-x-2 py-2 border-b border-gray-100 text-xs font-mono">
                      <span className={`px-2 py-1 rounded ${log.level === 'error' ? 'bg-red-100 text-red-700' : log.level === 'warning' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'}`}>
                        {log.level}
                      </span>
                      <span className="text-gray-500">{log.timestamp}</span>
                      <span className="flex-1 text-gray-700">{log.message}</span>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-gray-500 text-center py-8">Loglar topilmadi</p>
              )}
            </div>
          ) : (
            <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
              <Server className="w-16 h-16 text-gray-300 mx-auto mb-4" />
              <h3 className="text-lg font-medium text-gray-900 mb-2">Tez orada...</h3>
              <p className="text-gray-600">Bu bo'lim hali ishlab chiqilmoqda</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

