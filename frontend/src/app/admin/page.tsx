'use client';
import { useState, useEffect } from 'react';
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
  BookOpen
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
  const { user } = useAuth();
  const [activeTab, setActiveTab] = useState('dashboard');
  const [stats, setStats] = useState<AdminStats | null>(null);
  const [loading, setLoading] = useState(true);

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
    const loadStats = async () => {
      try {
        const data = await getAdminStats();
        setStats(data);
      } catch (error) {
        console.error('Error loading stats:', error);
      } finally {
        setLoading(false);
      }
    };

    if (activeTab === 'dashboard') {
      loadStats();
    }
  }, [activeTab]);

  if (!user || !(user as any).is_admin) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="text-gray-400 text-6xl mb-4">🔒</div>
          <h1 className="text-2xl font-bold text-gray-900 mb-4">Ruxsat berilmagan</h1>
          <p className="text-gray-600 mb-6">Bu sahifaga faqat administratorlar kirishi mumkin</p>
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
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-600">Jami Foydalanuvchilar</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.users.total || 0}</p>
              <p className="text-xs text-green-600">+{stats?.users.new_today || 0} bugun</p>
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
              <p className="text-xs text-green-600">+{stats?.posts.new_today || 0} bugun</p>
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
              <p className="text-xs text-green-600">+{stats?.comments.new_today || 0} bugun</p>
            </div>
            <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <MessageSquare className="w-6 h-6 text-purple-600" />
            </div>
          </div>
        </div>
      </div>
    </div>
  );

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto py-10 px-4">
        <div className="mb-8 flex items-center justify-between">
          <h1 className="text-3xl font-bold text-gray-900">Admin Panel</h1>
        </div>
        <div className="mb-8 flex space-x-4 overflow-x-auto">
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
          {loading && activeTab === 'dashboard' ? (
            <LoadingSpinner />
          ) : activeTab === 'dashboard' ? (
            renderDashboard()
          ) : (
            <div className="text-gray-500">Boshqa bo'limlar hali ishlab chiqilmoqda.</div>
          )}
        </div>
      </div>
    </div>
  );
}

