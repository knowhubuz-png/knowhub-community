'use client';
import { Calendar, User, ArrowRight, BookOpen } from 'lucide-react';
import Link from 'next/link';

interface BlogPost {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  author: string;
  date: string;
  category: string;
  readTime: string;
}

export default function BlogPage() {
  // Mock blog posts - kelajakda API dan olinadi
  const blogPosts: BlogPost[] = [
    {
      id: 1,
      title: "KnowHub Community Platformasi Rasmiy Ishga Tushdi",
      slug: "knowhub-community-launch",
      excerpt: "O'zbekiston dasturchilar hamjamiyati uchun yangi platforma taqdim etildi. Bilim almashish, kod ishga tushirish va professional rivojlanish imkoniyatlari.",
      content: "",
      author: "KnowHub Team",
      date: "2025-01-15",
      category: "Yangiliklar",
      readTime: "3 daqiqa"
    },
    {
      id: 2,
      title: "AI Assistant - SolVer Tez Orada",
      slug: "solver-ai-coming-soon",
      excerpt: "Vijdonli sun'iy intellekt yordamchisi tez orada platformaga qo'shiladi. Kod yozish va muammolarni yechishda yordam beradi.",
      content: "",
      author: "AI Team",
      date: "2025-01-10",
      category: "AI",
      readTime: "5 daqiqa"
    },
    {
      id: 3,
      title: "Kod Ishga Tushirish Xususiyati Yangilandi",
      slug: "code-runner-update",
      excerpt: "JavaScript, Python va PHP kodlarini ishga tushirish tizimi yangilandi. Yangi xavfsizlik choralari va tezlik optimizatsiyasi.",
      content: "",
      author: "Tech Team",
      date: "2025-01-05",
      category: "Texnologiya",
      readTime: "4 daqiqa"
    }
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="text-center">
            <div className="flex items-center justify-center mb-4">
              <BookOpen className="w-12 h-12" />
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-4">Blog</h1>
            <p className="text-xl text-blue-100 max-w-2xl mx-auto">
              KnowHub Community yangiliklari, texnologiya maqolalari va hamjamiyat voqealari
            </p>
          </div>
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Blog Posts */}
        <div className="space-y-8">
          {blogPosts.map((post) => (
            <article key={post.id} className="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
              <div className="flex items-center space-x-4 mb-4">
                <span className="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 text-sm rounded-full">
                  {post.category}
                </span>
                <div className="flex items-center text-sm text-gray-500">
                  <Calendar className="w-4 h-4 mr-1" />
                  {new Date(post.date).toLocaleDateString('uz-UZ')}
                </div>
                <div className="flex items-center text-sm text-gray-500">
                  <User className="w-4 h-4 mr-1" />
                  {post.author}
                </div>
                <span className="text-sm text-gray-500">{post.readTime}</span>
              </div>
              
              <h2 className="text-2xl font-bold text-gray-900 mb-4 hover:text-indigo-600 transition-colors">
                <Link href={`/blog/${post.slug}`}>
                  {post.title}
                </Link>
              </h2>
              
              <p className="text-gray-600 mb-6 leading-relaxed">
                {post.excerpt}
              </p>
              
              <Link
                href={`/blog/${post.slug}`}
                className="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium"
              >
                To'liq o'qish
                <ArrowRight className="w-4 h-4 ml-1" />
              </Link>
            </article>
          ))}
        </div>

        {/* Coming Soon */}
        <div className="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
          <BookOpen className="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 mb-2">Ko'proq maqolalar tez orada</h3>
          <p className="text-gray-600">
            Yangi blog maqolalari va yangiliklar uchun bizni kuzatib boring
          </p>
        </div>
      </div>
    </div>
  );
}