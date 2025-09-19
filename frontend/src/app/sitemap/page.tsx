'use client';
import { Map, Home, FileText, Users, BookOpen, Settings, HelpCircle, Mail, Phone } from 'lucide-react';
import Link from 'next/link';

export default function SitemapPage() {
  const sitemapSections = [
    {
      title: 'Asosiy Sahifalar',
      icon: Home,
      links: [
        { name: 'Bosh Sahifa', href: '/', description: 'Platformaning asosiy sahifasi' },
        { name: 'Postlar', href: '/posts', description: 'Barcha postlar ro\'yxati' },
        { name: 'Foydalanuvchilar', href: '/users', description: 'Hamjamiyat a\'zolari' },
        { name: 'Wiki', href: '/wiki', description: 'Bilim bazasi' },
      ]
    },
    {
      title: 'Foydalanuvchi',
      icon: Users,
      links: [
        { name: 'Kirish', href: '/auth/login', description: 'Tizimga kirish' },
        { name: 'Ro\'yxatdan o\'tish', href: '/auth/register', description: 'Yangi hisob yaratish' },
        { name: 'Profillar', href: '/profile', description: 'Foydalanuvchi profillari' },
        { name: 'Dashboard', href: '/dashboard', description: 'Shaxsiy dashboard' },
        { name: 'Saqlanganlar', href: '/bookmarks', description: 'Saqlangan postlar' },
        { name: 'Bildirishnomalar', href: '/notifications', description: 'Bildirishnomalar' },
        { name: 'Reyting jadvali', href: '/leaderboard', description: 'Eng faol foydalanuvchilar' },
      ]
    },
    {
      title: 'Kontent',
      icon: FileText,
      links: [
        { name: 'Postlar', href: '/posts', description: 'Barcha postlar' },
        { name: 'Wiki Maqolalar', href: '/wiki', description: 'Hamjamiyat wiki\'si' },
        { name: 'Yordam Markazi', href: '/help', description: 'Yordam va qo\'llanmalar' },
      ]
    },
    {
      title: 'Ma\'lumot',
      icon: BookOpen,
      links: [
        { name: 'Biz Haqimizda', href: '/about', description: 'Platforma haqida' },
        { name: 'Aloqa', href: '/contact', description: 'Biz bilan bog\'lanish' },
        { name: 'Maxfiylik Siyosati', href: '/privacy', description: 'Maxfiylik qoidalari' },
        { name: 'Foydalanish Shartlari', href: '/terms', description: 'Foydalanish shartlari' },
        { name: 'API Hujjatlari', href: '/api-docs', description: 'API qo\'llanmasi' },
      ]
    },
    {
      title: 'Tizim',
      icon: Settings,
      links: [
        { name: 'Tizim Holati', href: '/status', description: 'Server holati' },
        { name: 'RSS Feed', href: '/rss', description: 'Yangiliklar RSS' },
        { name: 'Sayt Xaritasi', href: '/sitemap', description: 'Sayt xaritasi' },
      ]
    },
    {
      title: 'Yordam',
      icon: HelpCircle,
      links: [
        { name: 'Yordam Markazi', href: '/help', description: 'Ko\'p so\'ralgan savollar' },
        { name: 'Aloqa', href: '/contact', description: 'Qo\'llab-quvvatlash xizmati' },
        { name: 'Email', href: 'mailto:info@knowhub.uz', description: 'info@knowhub.uz', external: true },
        { name: 'Telegram Support', href: 'https://t.me/knowhub_support', description: '@knowhub_support', external: true },
      ]
    }
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="text-center">
            <div className="flex items-center justify-center mb-4">
              <Map className="w-12 h-12" />
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-4">Sayt Xaritasi</h1>
            <p className="text-xl text-blue-100 max-w-2xl mx-auto">
              KnowHub Community platformasining barcha sahifalari va bo'limlari
            </p>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Quick Stats */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div className="text-3xl font-bold text-blue-600 mb-2">20+</div>
            <div className="text-gray-600">Sahifalar</div>
          </div>
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div className="text-3xl font-bold text-green-600 mb-2">6</div>
            <div className="text-gray-600">Asosiy Bo\'limlar</div>
          </div>
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div className="text-3xl font-bold text-purple-600 mb-2">24/7</div>
            <div className="text-gray-600">Online</div>
          </div>
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div className="text-3xl font-bold text-orange-600 mb-2">1000+</div>
            <div className="text-gray-600">Foydalanuvchilar</div>
          </div>
        </div>

        {/* Sitemap Sections */}
        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
          {sitemapSections.map((section, index) => (
            <div key={index} className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div className="flex items-center mb-4">
                <section.icon className="w-6 h-6 text-blue-600 mr-3" />
                <h3 className="text-lg font-bold text-gray-900">{section.title}</h3>
              </div>
              <ul className="space-y-3">
                {section.links.map((link, linkIndex) => (
                  <li key={linkIndex}>
                    {link.external ? (
                      <a
                        href={link.href}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="block group"
                      >
                        <div className="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                          {link.name}
                        </div>
                        <div className="text-sm text-gray-600">{link.description}</div>
                      </a>
                    ) : (
                      <Link href={link.href} className="block group">
                        <div className="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                          {link.name}
                        </div>
                        <div className="text-sm text-gray-600">{link.description}</div>
                      </Link>
                    )}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        {/* Additional Info */}
        <div className="mt-12 bg-blue-50 rounded-lg p-8">
          <h3 className="text-xl font-bold text-gray-900 mb-4">Qo'shimcha Ma'lumotlar</h3>
          <div className="grid md:grid-cols-2 gap-6">
            <div>
              <h4 className="font-semibold text-gray-900 mb-2 flex items-center">
                <Mail className="w-5 h-5 mr-2" />
                Bog'lanish
              </h4>
              <ul className="space-y-1 text-gray-600">
                <li>Email: info@knowhub.uz</li>
                <li>Telegram: @knowhub_support</li>
                <li>Manzil: Toshkent, O'zbekiston</li>
              </ul>
            </div>
            <div>
              <h4 className="font-semibold text-gray-900 mb-2 flex items-center">
                <Phone className="w-5 h-5 mr-2" />
                Ijtimoiy Tarmoqlar
              </h4>
              <ul className="space-y-1 text-gray-600">
                <li>GitHub: knowhub-dev</li>
                <li>Telegram: @knowhub_community</li>
                <li>YouTube: @knowhub_uz</li>
                <li>Instagram: knowhub_uz</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
