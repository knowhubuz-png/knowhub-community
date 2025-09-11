import Link from 'next/link';
import { Post } from '@/types';
import { Clock, MessageCircle, ArrowUp, User } from 'lucide-react';
import BookmarkButton from './BookmarkButton';

interface PostCardProps {
  post: Post;
}

export default function PostCard({ post }: PostCardProps) {
  return (
    <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
      {/* Header */}
      <div className="flex items-start justify-between mb-4">
        <div className="flex items-center space-x-3">
          <img
            src={post.user.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(post.user.name)}`}
            alt={post.user.name}
            className="w-10 h-10 rounded-full"
          />
          <div>
            <p className="font-medium text-gray-900">{post.user.name}</p>
            <div className="flex items-center text-sm text-gray-500">
              <Clock className="w-4 h-4 mr-1" />
              {new Date(post.created_at).toLocaleDateString('uz-UZ')}
            </div>
          </div>
        </div>
        
        {/* User Level */}
        {post.user.level && (
          <div className="flex items-center space-x-1 bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-xs">
            <span>{post.user.level.name}</span>
            <span>({post.user.xp} XP)</span>
          </div>
        )}
      </div>

      {/* Title */}
      <Link href={`/posts/${post.slug}`}>
        <h3 className="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition-colors mb-3 line-clamp-2">
          {post.title}
        </h3>
      </Link>

      {/* Content Preview */}
      <p className="text-gray-600 text-sm mb-4 line-clamp-3">
        {post.content_markdown.replace(/[#*`]/g, '').substring(0, 150)}...
      </p>

      {/* Tags */}
      {post.tags && post.tags.length > 0 && (
        <div className="flex flex-wrap gap-2 mb-4">
          {post.tags.slice(0, 3).map((tag) => (
            <Link
              key={tag.slug}
              href={`/posts?tag=${tag.slug}`}
              className="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200 transition-colors"
            >
              #{tag.name}
            </Link>
          ))}
          {post.tags.length > 3 && (
            <span className="text-xs text-gray-500">+{post.tags.length - 3} ko'proq</span>
          )}
        </div>
      )}

      {/* Category */}
      {post.category && (
        <div className="mb-4">
          <Link
            href={`/posts?category=${post.category.slug}`}
            className="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 text-sm rounded-full hover:bg-indigo-100 transition-colors"
          >
            📁 {post.category.name}
          </Link>
        </div>
      )}

      {/* Footer Stats */}
      <div className="flex items-center justify-between pt-4 border-t border-gray-100">
        <div className="flex items-center space-x-4">
          <div className="flex items-center space-x-1 text-gray-500">
            <ArrowUp className="w-4 h-4" />
            <span className="text-sm font-medium">{post.score}</span>
          </div>
          <div className="flex items-center space-x-1 text-gray-500">
            <MessageCircle className="w-4 h-4" />
            <span className="text-sm">{post.answers_count}</span>
          </div>
        </div>

        <div className="flex items-center space-x-2">
          <BookmarkButton postId={post.id} />
          {/* AI Suggestion Indicator */}
          {post.is_ai_suggested && (
            <div className="flex items-center space-x-1 text-purple-600 text-xs">
              <span>🤖</span>
              <span>AI tavsiya</span>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}