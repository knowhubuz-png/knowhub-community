'use client';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/api';
import LoadingSpinner from '@/components/LoadingSpinner';
import { Hash, TrendingUp, Search } from 'lucide-react';
import Link from 'next/link';
import { useState } from 'react';

interface Tag {
  id: number;
  name: string;
  slug: string;
  usage_count?: number;
}

async function getTags() {
  try {
    const res = await api.get('/tags');
    return res.data;
  } catch (error) {
    console.error('Error fetching tags:', error);
    return [];
  }
}

async function getTrendingTags() {
  try {
    const res = await api.get('/tags/trending');
    return res.data;
  } catch (error) {
    console.error('Error fetching trending tags:', error);
    return [];
  }
}

export default function TagsPage() {
  const [searchTerm, setSearchTerm] = useState('');
  const [showTrending, setShowTrending] = useState(true);

  const { data: allTags, isLoading: allLoading } = useQuery({
    queryKey: ['tags'],
    queryFn: getTags,
    retry: 1,
  });

  const { data: trendingTags, isLoading: trendingLoading } = useQuery({
    queryKey: ['tags', 'trending'],
    queryFn: getTrendingTags,
    retry: 1,
  });

  const filteredTags = (showTrending ? trendingTags : allTags)?.filter((tag: Tag) =>
    tag.name.toLowerCase().includes(searchTerm.toLowerCase())
  ) || [];

  if (allLoading || trendingLoading) return <LoadingSpinner />;

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      {/* Header */}
      <div className="text-center mb-12">
        <div className="flex items-center justify-center mb-4">
          <Hash className="w-12 h-12 text-indigo-600" />
        </div>
        <h1 className="text-4xl font-bold text-gray-900 mb-4">Teglar</h1>
        <p className="text-xl text-gray-600">
          Mavzular bo'yicha postlarni topish va kuzatish
        </p>
      </div>

      {/* Controls */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div className="flex items-center space-x-4 mb-4 sm:mb-0">
          <button
            onClick={() => setShowTrending(true)}
            className={`px-4 py-2 rounded-lg font-medium transition-colors ${
              showTrending 
                ? 'bg-indigo-600 text-white' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            }`}
          >
            <TrendingUp className="w-4 h-4 mr-2 inline" />
            Trend Teglar
          </button>
          <button
            onClick={() => setShowTrending(false)}
            className={`px-4 py-2 rounded-lg font-medium transition-colors ${
              !showTrending 
                ? 'bg-indigo-600 text-white' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            }`}
          >
            <Hash className="w-4 h-4 mr-2 inline" />
            Barcha Teglar
          </button>
        </div>

        <div className="relative">
          <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
          <input
            type="text"
            placeholder="Teglarni qidirish..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          />
        </div>
      </div>

      {/* Tags Grid */}
      {filteredTags.length > 0 ? (
        <div className="flex flex-wrap gap-3">
          {filteredTags.map((tag: Tag) => (
            <Link
              key={tag.slug}
              href={`/posts?tag=${tag.slug}`}
              className="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors group"
            >
              <Hash className="w-4 h-4 text-gray-400 group-hover:text-indigo-600 mr-2" />
              <span className="font-medium text-gray-900 group-hover:text-indigo-600">
                {tag.name}
              </span>
              {tag.usage_count && (
                <span className="ml-2 text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                  {tag.usage_count}
                </span>
              )}
            </Link>
          ))}
        </div>
      ) : (
        <div className="text-center py-12">
          <Hash className="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 mb-2">
            {searchTerm ? 'Teglar topilmadi' : 'Teglar mavjud emas'}
          </h3>
          <p className="text-gray-600">
            {searchTerm 
              ? 'Boshqa kalit so\'zlar bilan urinib ko\'ring' 
              : 'Postlar yaratilganda teglar avtomatik paydo bo\'ladi'
            }
          </p>
        </div>
      )}
    </div>
  );
}