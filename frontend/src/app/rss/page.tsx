'use client';
import { Rss, FileText, Calendar, Clock, ExternalLink, Copy, Check } from 'lucide-react';
import { useState } from 'react';
import Link from 'next/link';

export default function RssPage() {
  const [copied, setCopied] = useState(false);

  const rssFeeds = [
    {
      title: 'Barcha Postlar',
      description: 'Platformadagi barcha yangi postlar',
      url: '/rss/posts',
      color: 'blue'
    },
    {
      title: 'Trend Postlar',
      description: 'Eng ko\'p o\'qilayotgan postlar',
      url: '/rss/trending',
      color: 'green'
    },
    {
      title: 'Wiki Maqolalari',
      description: 'Yangi wiki maqolalari',
      url: '/rss/wiki',
      color: 'purple'
    },
    {
      title: 'Foydalanuvchi Postlari',
      description: 'Tanlangan foydalanuvchi postlari',
      url: '/rss/users/{username}',
      color: 'orange',
      dynamic: true
    },
    {
      title: 'Teg bo\'yicha Postlar',
      description: 'Mavzuga oid postlar',
      url: '/rss/tags/{tag}',
      color: 'red',
      dynamic: true
    }
  ];

  const copyToClipboard = (text: string) => {
    navigator.clipboard.writeText(text);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  const getColorClasses = (color: string) => {
    const colors = {
      blue: 'bg-blue-100 text-blue-800 border-blue-200',
      green: 'bg-green-100 text-green-800 border-green-200',
      purple: 'bg-purple-100 text-purple-800 border-purple-200',
      orange: 'bg-orange-100 text-orange-800 border-orange-200',
      red: 'bg-red-100 text-red-800 border-red-200'
    };
    return colors[color as keyof typeof colors] || colors.blue;
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-gradient-to-br from-orange-600 via-red-600 to-pink-700 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="text-center">
            <div className="flex items-center justify-center mb-4">
              <Rss className="w-12 h-12" />
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-4">RSS Feed</h1>
            <p className="text-xl text-orange-100 max-w-2xl mx-auto">
              KnowHub Community kontentini RSS orqali kuzatib boring. Yangi postlar, 
              wiki maqolalari va trendlardan xabardor bo'ling.
            </p>
          </div>
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* What is RSS */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">RSS nima?</h2>
          <div className="prose prose-gray max-w-none">
            <p>
              RSS (Really Simple Syndication) - veb-saytlarning yangi kontentini 
              kuzatib borish uchun mo'ljallangan format. RSS orqali siz:
            </p>
            <ul className="list-disc pl-6 space-y-1">
              <li>Yangi postlardan tezda xabardor bo'lasiz</li>
              <li>Har kuni saytni tekshirish shart emas</li>
              <li>Sevimli mavzular bo'yicha kontentni kuzatishingiz mumkin</li>
              <li>Mobil ilovalar orqali o'qish qulayligi</li>
            </ul>
          </div>
        </div>

        {/* How to Use */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">Qanday foydalaniladi?</h2>
          <div className="grid md:grid-cols-3 gap-6">
            <div className="text-center">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <Copy className="w-6 h-6 text-blue-600" />
              </div>
              <h3 className="font-semibold text-gray-900 mb-2">1. RSS URL ni nusxalang</h3>
              <p className="text-sm text-gray-600">Kerakli feed URL ni nusxalang</p>
            </div>
            <div className="text-center">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <ExternalLink className="w-6 h-6 text-green-600" />
              </div>
              <h3 className="font-semibold text-gray-900 mb-2">2. RSS reader oching</h3>
              <p className="text-sm text-gray-600">Feedly, Inoreader yoki boshqa RSS reader</p>
            </div>
            <div className="text-center">
              <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <Check className="w-6 h-6 text-purple-600" />
              </div>
              <h3 className="font-semibold text-gray-900 mb-2">3. Obuna bo'ling</h3>
              <p className="text-sm text-gray-600">URL ni qo'shib obuna bo'ling</p>
            </div>
          </div>
        </div>

        {/* Available Feeds */}
        <div className="mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Mavjud RSS Feedlar</h2>
          <div className="space-y-4">
            {rssFeeds.map((feed, index) => (
              <div key={index} className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div className="flex items-start justify-between">
                  <div className="flex-1">
                    <div className="flex items-center mb-2">
                      <h3 className="text-lg font-semibold text-gray-900 mr-3">
                        {feed.title}
                      </h3>
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${getColorClasses(feed.color)}`}>
                        RSS
                      </span>
                      {feed.dynamic && (
                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 ml-2">
                          Dinamik
                        </span>
                      )}
                    </div>
                    <p className="text-gray-600 mb-3">{feed.description}</p>
                    <div className="flex items-center space-x-4 text-sm text-gray-500">
                      <div className="flex items-center">
                        <FileText className="w-4 h-4 mr-1" />
                        <span>XML format</span>
                      </div>
                      <div className="flex items-center">
                        <Clock className="w-4 h-4 mr-1" />
                        <span>Real-time yangilanish</span>
                      </div>
                    </div>
                  </div>
                  <div className="flex items-center space-x-2 ml-4">
                    <code className="bg-gray-100 px-3 py-2 rounded text-sm font-mono">
                      https://knowhub.uz{feed.url}
                    </code>
                    <button
                      onClick={() => copyToClipboard(`https://knowhub.uz${feed.url}`)}
                      className="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                      title="Nusxalash"
                    >
                      {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
                    </button>
                  </div>
                </div>
                {feed.dynamic && (
                  <div className="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p className="text-sm text-blue-800">
                      <strong>Eslatma:</strong> Bu feed dinamik. URL da {feed.url.includes('{username}') ? '{username}' : '{tag}'} 
                      o'rniga haqiqiy foydalanuvchi nomi yoki teg nomini kiriting.
                    </p>
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>

        {/* RSS Readers */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">Mashhur RSS Readerlar</h2>
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a
              href="https://feedly.com"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <div className="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <span className="text-green-600 font-bold text-sm">F</span>
              </div>
              <div>
                <div className="font-medium text-gray-900">Feedly</div>
                <div className="text-sm text-gray-600">Web & Mobile</div>
              </div>
            </a>
            <a
              href="https://inoreader.com"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                <span className="text-blue-600 font-bold text-sm">I</span>
              </div>
              <div>
                <div className="font-medium text-gray-900">Inoreader</div>
                <div className="text-sm text-gray-600">Web & Mobile</div>
              </div>
            </a>
            <a
              href="https://theoldreader.com"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <div className="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                <span className="text-orange-600 font-bold text-sm">T</span>
              </div>
              <div>
                <div className="font-medium text-gray-900">The Old Reader</div>
                <div className="text-sm text-gray-600">Web</div>
              </div>
            </a>
          </div>
        </div>

        {/* API Integration */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">Dasturchilar uchun</h2>
          <div className="space-y-4">
            <div>
              <h3 className="font-semibold text-gray-900 mb-2">JavaScript misoli</h3>
              <div className="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto">
                <pre className="text-sm">
                  <code>{`// RSS feed ni olish
async function fetchRSSFeed(feedUrl) {
  try {
    const response = await fetch(feedUrl);
    const text = await response.text();
    // XML parsing uchun parser ishlatishingiz mumkin
    console.log(text);
  } catch (error) {
    console.error('Error fetching RSS feed:', error);
  }
}

// Misol uchun
fetchRSSFeed('https://knowhub.uz/rss/posts');`}</code>
                </pre>
              </div>
            </div>
            <div>
              <h3 className="font-semibold text-gray-900 mb-2">Python misoli</h3>
              <div className="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto">
                <pre className="text-sm">
                  <code>{`import requests
import xml.etree.ElementTree as ET

def fetch_rss_feed(feed_url):
    try:
        response = requests.get(feed_url)
        root = ET.fromstring(response.content)
        
        # XML parsing
        for item in root.findall('.//item'):
            title = item.find('title').text
            link = item.find('link').text
            print(f"Title: {title}")
            print(f"Link: {link}")
            print("---")
            
    except Exception as e:
        print(f"Error fetching RSS feed: {e}")

# Misol uchun
fetch_rss_feed('https://knowhub.uz/rss/posts')`}</code>
                </pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
