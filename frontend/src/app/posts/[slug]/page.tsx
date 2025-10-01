import { api } from '@/lib/api';
import { notFound } from 'next/navigation';
import Link from 'next/link';
import { ArrowLeft, Calendar, User, MessageCircle, ThumbsUp, Bookmark, Share2 } from 'lucide-react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';

interface Post {
  id: number;
  title: string;
  slug: string;
  content_markdown: string;
  score: number;
  answers_count: number;
  created_at: string;
  user: {
    id: number;
    name: string;
    username: string;
    avatar_url?: string;
    level?: {
      name: string;
    };
  };
  category?: {
    id: number;
    name: string;
    slug: string;
  };
  tags: Array<{
    name: string;
    slug: string;
  }>;
  ai_suggestion?: {
    model: string;
    content_markdown: string;
  };
  is_ai_suggested: boolean;
}

async function getPost(slug: string): Promise<Post> {
  try {
    const res = await api.get(`/posts/${slug}`);
    return res.data;
  } catch (error: any) {
    if (error.response?.status === 404) {
      notFound();
    }
    throw error;
  }
}

export default async function PostPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const post = await getPost(slug);

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow-sm border-b border-gray-200">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div className="flex items-center justify-between mb-4">
            <Link
              href="/posts"
              className="flex items-center text-gray-600 hover:text-gray-900"
            >
              <ArrowLeft className="w-5 h-5 mr-2" />
              Postlarga qaytish
            </Link>
            <div className="flex items-center space-x-2">
              <button className="flex items-center px-3 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                <Bookmark className="w-4 h-4 mr-1" />
                Saqlash
              </button>
              <button className="flex items-center px-3 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                <Share2 className="w-4 h-4 mr-1" />
                Ulashish
              </button>
            </div>
          </div>
          
          <h1 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            {post.title}
          </h1>
          
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-4">
              <img
                src={post.user.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(post.user.name)}`}
                alt={post.user.name}
                className="w-12 h-12 rounded-full"
              />
              <div>
                <div className="font-medium text-gray-900">{post.user.name}</div>
                <div className="flex items-center text-sm text-gray-500">
                  <Calendar className="w-4 h-4 mr-1" />
                  <span>{new Date(post.created_at).toLocaleDateString('uz-UZ')}</span>
                  {post.user.level && (
                    <>
                      <span className="mx-2">•</span>
                      <span className="text-indigo-600">{post.user.level.name}</span>
                    </>
                  )}
                </div>
              </div>
            </div>
            
            <div className="flex items-center space-x-4 text-sm text-gray-500">
              <div className="flex items-center">
                <ThumbsUp className="w-4 h-4 mr-1" />
                <span>{post.score}</span>
              </div>
              <div className="flex items-center">
                <MessageCircle className="w-4 h-4 mr-1" />
                <span>{post.answers_count}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
          <div className="prose prose-lg max-w-none prose-headings:font-semibold prose-h1:text-2xl prose-h2:text-xl prose-h3:text-lg prose-p:text-gray-700 prose-code:bg-gray-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:p-4 prose-pre:rounded-lg prose-pre:overflow-x-auto">
            <ReactMarkdown remarkPlugins={[remarkGfm]}>
              {post.content_markdown}
            </ReactMarkdown>
          </div>

          {/* Tags */}
          {post.tags && post.tags.length > 0 && (
            <div className="mt-8 pt-6 border-t border-gray-200">
              <div className="flex flex-wrap gap-2">
                {post.tags.map((tag) => (
                  <Link
                    key={tag.slug}
                    href={`/posts?tag=${tag.slug}`}
                    className="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-gray-200 transition-colors"
                  >
                    #{tag.name}
                  </Link>
                ))}
              </div>
            </div>
          )}

          {/* Category */}
          {post.category && (
            <div className="mt-4">
              <Link
                href={`/posts?category=${post.category.slug}`}
                className="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 text-sm rounded-full hover:bg-indigo-100 transition-colors"
              >
                📁 {post.category.name}
              </Link>
            </div>
          )}
        </div>

        {/* AI Suggestion */}
        {post.ai_suggestion && (
          <div className="mt-8 bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-6">
            <div className="flex items-center mb-4">
              <span className="text-2xl mr-3">🤖</span>
              <h2 className="text-lg font-semibold text-gray-900">AI Tavsiya Javobi</h2>
              <span className="ml-2 text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">
                {post.ai_suggestion.model}
              </span>
            </div>
            <div className="prose max-w-none prose-p:text-gray-700">
              <ReactMarkdown remarkPlugins={[remarkGfm]}>
                {post.ai_suggestion.content_markdown}
              </ReactMarkdown>
            </div>
          </div>
        )}

        {/* Comments Section */}
        <div className="mt-8">
          <h2 className="text-xl font-semibold text-gray-900 mb-6">
            Kommentlar ({post.answers_count})
          </h2>
          <div className="text-center py-12 bg-white rounded-lg border border-gray-200">
            <MessageCircle className="w-12 h-12 text-gray-400 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 mb-2">Kommentlar tez orada</h3>
            <p className="text-gray-600">Komment tizimi keyingi versiyada qo'shiladi</p>
          </div>
        </div>
      </div>
    </div>
  );
}