'use client';
import { useQuery } from '@tanstack/react-query';
import { useParams } from 'next/navigation';
import { api } from '@/lib/api';
import LoadingSpinner from '@/components/LoadingSpinner';
import { BookOpen, Calendar, User, ArrowLeft, Edit } from 'lucide-react';
import Link from 'next/link';
import { useAuth } from '@/providers/AuthProvider';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import rehypePrismPlus from 'rehype-prism-plus';

interface WikiArticle {
  id: number;
  title: string;
  slug: string;
  content_markdown: string;
  created_at: string;
  updated_at: string;
  user: {
    id: number;
    name: string;
    username: string;
    avatar_url?: string;
  };
}

async function getWikiArticle(slug: string) {
  try {
    const res = await api.get(`/wiki/${slug}`);
    return res.data;
  } catch (error) {
    console.error('Error fetching wiki article:', error);
    return null;
  }
}

export default function WikiArticlePage() {
  const params = useParams();
  const slug = params.slug as string;
  const { user } = useAuth();
  
  const { data: article, isLoading, error } = useQuery({
    queryKey: ['wiki', slug],
    queryFn: () => getWikiArticle(slug),
    retry: 1,
    enabled: !!slug,
  });

  if (isLoading) return <LoadingSpinner />;

  if (error || !article) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="text-gray-400 text-6xl mb-4">📚</div>
          <h1 className="text-2xl font-bold text-gray-900 mb-4">Maqola topilmadi</h1>
          <p className="text-gray-600 mb-6">Bunday wiki maqolasi mavjud emas yoki o'chirilgan</p>
          <Link
            href="/wiki"
            className="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
          >
            Wiki ga qaytish
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow-sm border-b border-gray-200">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div className="flex items-center justify-between mb-4">
            <Link
              href="/wiki"
              className="flex items-center text-gray-600 hover:text-gray-900"
            >
              <ArrowLeft className="w-5 h-5 mr-2" />
              Wiki ga qaytish
            </Link>
            {user && (
              <Link
                href={`/wiki/${slug}/edit`}
                className="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                <Edit className="w-4 h-4 mr-2" />
                Tahrirlash
              </Link>
            )}
          </div>
          
          <h1 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            {article.title}
          </h1>
          
          <div className="flex items-center justify-between text-sm text-gray-500">
            <div className="flex items-center">
              <img
                src={article.user.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(article.user.name)}`}
                alt={article.user.name}
                className="w-8 h-8 rounded-full mr-3"
              />
              <div>
                <div className="font-medium text-gray-900">{article.user.name}</div>
                <div className="flex items-center mt-1">
                  <Calendar className="w-4 h-4 mr-1" />
                  <span>{new Date(article.created_at).toLocaleDateString('uz-UZ')}</span>
                  {article.updated_at !== article.created_at && (
                    <span className="ml-3">
                      • Yangilangan: {new Date(article.updated_at).toLocaleDateString('uz-UZ')}
                    </span>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
          <div className="prose prose-lg max-w-none">
            <ReactMarkdown
              remarkPlugins={[remarkGfm]}
              rehypePlugins={[rehypePrismPlus]}
              className="prose-headings:font-semibold prose-h1:text-2xl prose-h2:text-xl prose-h3:text-lg prose-p:text-gray-700 prose-code:bg-gray-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:p-4 prose-pre:rounded-lg prose-pre:overflow-x-auto"
            >
              {article.content_markdown}
            </ReactMarkdown>
          </div>
        </div>

        {/* Related Articles */}
        <div className="mt-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">O'xshash Maqolalar</h2>
          <div className="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
            <BookOpen className="w-12 h-12 text-gray-400 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 mb-2">Tez orada</h3>
            <p className="text-gray-600">O'xshash maqolalar tez orada qo'shiladi</p>
          </div>
        </div>
      </div>
    </div>
  );
}
