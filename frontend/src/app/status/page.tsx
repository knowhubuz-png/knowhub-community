'use client';
import { useState, useEffect } from 'react';
import { Activity, CheckCircle, XCircle, Clock, Zap, Database, Server, Wifi, AlertTriangle } from 'lucide-react';

interface ServiceStatus {
  name: string;
  status: 'operational' | 'degraded' | 'outage';
  description: string;
  responseTime?: number;
  lastChecked?: string;
}

interface SystemMetrics {
  uptime: string;
  cpu: number;
  memory: number;
  disk: number;
  activeUsers: number;
}

export default function StatusPage() {
  const [services, setServices] = useState<ServiceStatus[]>([
    {
      name: 'Web Server',
      status: 'operational',
      description: 'Frontend application',
      responseTime: 145,
      lastChecked: new Date().toISOString()
    },
    {
      name: 'API Server',
      status: 'operational',
      description: 'Backend REST API',
      responseTime: 89,
      lastChecked: new Date().toISOString()
    },
    {
      name: 'Database',
      status: 'operational',
      description: 'MySQL Database',
      responseTime: 12,
      lastChecked: new Date().toISOString()
    },
    {
      name: 'Redis Cache',
      status: 'operational',
      description: 'In-memory cache',
      responseTime: 3,
      lastChecked: new Date().toISOString()
    },
    {
      name: 'Queue System',
      status: 'operational',
      description: 'Background jobs',
      responseTime: 25,
      lastChecked: new Date().toISOString()
    },
    {
      name: 'File Storage',
      status: 'operational',
      description: 'Media and file uploads',
      responseTime: 67,
      lastChecked: new Date().toISOString()
    }
  ]);

  const [metrics, setMetrics] = useState<SystemMetrics>({
    uptime: '99.9%',
    cpu: 23,
    memory: 67,
    disk: 45,
    activeUsers: 1247
  });

  const [lastUpdated, setLastUpdated] = useState(new Date());

  // Simulate real-time updates
  useEffect(() => {
    const interval = setInterval(() => {
      setLastUpdated(new Date());
      
      // Simulate small changes in metrics
      setMetrics(prev => ({
        ...prev,
        cpu: Math.max(10, Math.min(80, prev.cpu + (Math.random() - 0.5) * 5)),
        memory: Math.max(40, Math.min(85, prev.memory + (Math.random() - 0.5) * 3)),
        activeUsers: Math.max(1000, Math.min(2000, prev.activeUsers + Math.floor((Math.random() - 0.5) * 50)))
      }));

      // Update service response times
      setServices(prev => prev.map(service => ({
        ...service,
        responseTime: Math.max(1, Math.min(200, (service.responseTime || 50) + (Math.random() - 0.5) * 20)),
        lastChecked: new Date().toISOString()
      })));
    }, 30000); // Update every 30 seconds

    return () => clearInterval(interval);
  }, []);

  const getStatusColor = (status: ServiceStatus['status']) => {
    switch (status) {
      case 'operational':
        return 'text-green-600 bg-green-100 border-green-200';
      case 'degraded':
        return 'text-yellow-600 bg-yellow-100 border-yellow-200';
      case 'outage':
        return 'text-red-600 bg-red-100 border-red-200';
      default:
        return 'text-gray-600 bg-gray-100 border-gray-200';
    }
  };

  const getStatusIcon = (status: ServiceStatus['status']) => {
    switch (status) {
      case 'operational':
        return <CheckCircle className="w-5 h-5" />;
      case 'degraded':
        return <Clock className="w-5 h-5" />;
      case 'outage':
        return <XCircle className="w-5 h-5" />;
      default:
        return <Activity className="w-5 h-5" />;
    }
  };

  const getStatusText = (status: ServiceStatus['status']) => {
    switch (status) {
      case 'operational':
        return 'Ishlayapti';
      case 'degraded':
        return 'Cheklangan';
      case 'outage':
        return 'O\'chirilgan';
      default:
        return 'Noma\'lum';
    }
  };

  const operationalCount = services.filter(s => s.status === 'operational').length;
  const overallStatus = operationalCount === services.length ? 'operational' : 
                       operationalCount >= services.length * 0.8 ? 'degraded' : 'outage';

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className={`bg-gradient-to-br ${
        overallStatus === 'operational' ? 'from-green-600 via-emerald-600 to-teal-700' :
        overallStatus === 'degraded' ? 'from-yellow-600 via-orange-600 to-amber-700' :
        'from-red-600 via-rose-600 to-pink-700'
      } text-white`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="text-center">
            <div className="flex items-center justify-center mb-4">
              <Activity className="w-12 h-12" />
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-4">Tizim Holati</h1>
            <p className="text-xl text-white/90 max-w-2xl mx-auto">
              KnowHub Community xizmatlarining joriy holati va ishlashi
            </p>
            <div className="mt-6 inline-flex items-center px-6 py-3 bg-white/20 rounded-full">
              {getStatusIcon(overallStatus)}
              <span className="ml-2 font-semibold">
                {overallStatus === 'operational' ? 'Barcha xizmatlar ishlayapti' :
                 overallStatus === 'degraded' ? 'Ba\'zi xizmatlar cheklangan' :
                 'Jiddiy muammolar mavjud'}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Summary Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <CheckCircle className="w-6 h-6 text-green-600" />
              </div>
              <div>
                <div className="text-2xl font-bold text-gray-900">{operationalCount}/{services.length}</div>
                <div className="text-sm text-gray-600">Xizmatlar ishlayapti</div>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <Zap className="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <div className="text-2xl font-bold text-gray-900">{metrics.uptime}</div>
                <div className="text-sm text-gray-600">Uptime</div>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center">
              <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                <Wifi className="w-6 h-6 text-purple-600" />
              </div>
              <div>
                <div className="text-2xl font-bold text-gray-900">{metrics.activeUsers}</div>
                <div className="text-sm text-gray-600">Faol foydalanuvchilar</div>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center">
              <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                <Clock className="w-6 h-6 text-orange-600" />
              </div>
              <div>
                <div className="text-2xl font-bold text-gray-900">
                  {Math.round(services.reduce((acc, s) => acc + (s.responseTime || 0), 0) / services.length)}ms
                </div>
                <div className="text-sm text-gray-600">O\'rtacha javob vaqti</div>
              </div>
            </div>
          </div>
        </div>

        {/* System Metrics */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Tizim Ko'rsatkichlari</h2>
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
              <div className="flex items-center justify-between mb-2">
                <span className="text-sm font-medium text-gray-600">CPU</span>
                <span className="text-sm font-semibold text-gray-900">{metrics.cpu.toFixed(1)}%</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div 
                  className={`h-2 rounded-full ${
                    metrics.cpu < 70 ? 'bg-green-500' : 
                    metrics.cpu < 90 ? 'bg-yellow-500' : 'bg-red-500'
                  }`}
                  style={{ width: `${metrics.cpu}%` }}
                />
              </div>
            </div>

            <div>
              <div className="flex items-center justify-between mb-2">
                <span className="text-sm font-medium text-gray-600">Xotira</span>
                <span className="text-sm font-semibold text-gray-900">{metrics.memory.toFixed(1)}%</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div 
                  className={`h-2 rounded-full ${
                    metrics.memory < 80 ? 'bg-green-500' : 
                    metrics.memory < 95 ? 'bg-yellow-500' : 'bg-red-500'
                  }`}
                  style={{ width: `${metrics.memory}%` }}
                />
              </div>
            </div>

            <div>
              <div className="flex items-center justify-between mb-2">
                <span className="text-sm font-medium text-gray-600">Disk</span>
                <span className="text-sm font-semibold text-gray-900">{metrics.disk.toFixed(1)}%</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div 
                  className={`h-2 rounded-full ${
                    metrics.disk < 80 ? 'bg-green-500' : 
                    metrics.disk < 95 ? 'bg-yellow-500' : 'bg-red-500'
                  }`}
                  style={{ width: `${metrics.disk}%` }}
                />
              </div>
            </div>

            <div>
              <div className="flex items-center justify-between mb-2">
                <span className="text-sm font-medium text-gray-600">Faollik</span>
                <span className="text-sm font-semibold text-gray-900">{metrics.activeUsers}</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div 
                  className="h-2 rounded-full bg-purple-500"
                  style={{ width: `${Math.min(100, (metrics.activeUsers / 2000) * 100)}%` }}
                />
              </div>
            </div>
          </div>
        </div>

        {/* Service Status */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Xizmatlar Holati</h2>
          <div className="space-y-4">
            {services.map((service, index) => (
              <div key={index} className="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div className="flex items-center flex-1">
                  <div className={`p-2 rounded-lg mr-4 ${getStatusColor(service.status)}`}>
                    {getStatusIcon(service.status)}
                  </div>
                  <div>
                    <h3 className="font-semibold text-gray-900">{service.name}</h3>
                    <p className="text-sm text-gray-600">{service.description}</p>
                  </div>
                </div>
                <div className="flex items-center space-x-6 text-sm">
                  <div className="text-right">
                    <div className="font-medium text-gray-900">{getStatusText(service.status)}</div>
                    {service.responseTime && (
                      <div className="text-gray-500">{service.responseTime}ms</div>
                    )}
                  </div>
                  <div className="text-right text-gray-500">
                    <div>Oxirgi tekshiruv</div>
                    <div>{new Date(service.lastChecked!).toLocaleTimeString('uz-UZ')}</div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Incidents */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">So'nggi Hodisalar</h2>
          <div className="text-center py-12">
            <CheckCircle className="w-12 h-12 text-green-500 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 mb-2">Hodisalar yo'q</h3>
            <p className="text-gray-600">So'nggi 30 kun ichida hech qanday muammo kuzatilmadi</p>
          </div>
        </div>

        {/* Last Updated */}
        <div className="text-center text-sm text-gray-500">
          Oxirgi yangilanish: {lastUpdated.toLocaleString('uz-UZ')}
          <br />
          Ma'lumotlar har 30 daqiqada avtomatik yangilanadi
        </div>
      </div>
    </div>
  );
}
