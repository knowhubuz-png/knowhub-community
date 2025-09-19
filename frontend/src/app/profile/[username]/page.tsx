import { api } from '@/lib/api';
import { notFound } from 'next/navigation';
import Link from 'next/link';
import { Trophy, Award, FileText } from 'lucide-react';

interface Badge {
  id: number;
  name: string;
  icon_url?: string;
}

interface Level {
  name: string;
}

interface UserProfile {
  id: number;
  name: string;
  username: string;
  avatar_url?: string;
  bio?: string;
  xp: number;
  level?: Level;
  badges?: Badge[];
}

interface UserPost {
  id: number;
  title: string;
  slug: string;
  created_at: string;
}

async function getUser(username: string): Promise<UserProfile> {
  const res = await api.get(`/api/v1/users/${username}`);
  return res.data;
}

async function getUserPosts(username: string): Promise<UserPost[]> {
  const res = await api.get(`/api/v1/users/${username}/posts`);
  return res.data;
}

export default async function ProfilePage({ params }: { params: { username: string } }) {
  let user: UserProfile;
  let posts: UserPost[];

  try {
    [user, posts] = await Promise.all([
      getUser(params.username),
      getUserPosts(params.username)
    ]);
  } catch (error: any) {
    if (error.response?.status === 404) {
      notFound();
    }
    throw error;
  }

  return (
    <div className="max-w-4xl mx-auto px-4 py-8">
      {/* Profil header */}
      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div className="flex items-center space-x-6">
          <img
            src={user.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}`}
            alt={user.name}
            className="w-24 h-24 rounded-full border-4 border-indigo-100"
          />
          <div>
            <h1 className="text-2xl font-bold text-gray-900">{user.name}</h1>
            <p className="text-gray-500">@{user.username}</p>
            {user.bio && <p className="mt-2 text-gray-700">{user.bio}</p>}
            <div className="flex items-center space-x-4 mt-3">
              <span className="flex items-center space-x-1 text-indigo-600 font-semibold">
                <Trophy className="w-4 h-4" /> <span>{user.xp} XP</span>
              </span>
              {user.level && (
                <span className="flex items-center space-x-1 bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-xs">
                  <Award className="w-4 h-4" /> <span>{user.level.name}</span>
                </span>
              )}
            </div>
          </div>
        </div>

        {/* Badge’lar */}
        {user.badges && user.badges.length > 0 && (
          <div className="mt-4 flex flex-wrap gap-2">
            {user.badges.map(badge => (
              <div
                key={badge.id}
                className="flex items-center space-x-1 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs"
              >
                {badge.icon_url && (
                  <img src={badge.icon_url} alt={badge.name} className="w-4 h-4" />
                )}
                <span>{badge.name}</span>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Foydalanuvchi postlari */}
      <div className="bg-white rounded-lg shadow-sm border border-gray-200">
        <div className="px-6 py-4 border-b border-gray-200 flex items-center space-x-2">
          <FileText className="w-5 h-5 text-gray-500" />
          <h2 className="text-lg font-semibold text-gray-900">So‘nggi postlar</h2>
        </div>
        {posts.length > 0 ? (
          <div className="divide-y divide-gray-200">
            {posts.map(post => (
              <div key={post.id} className="px-6 py-4 hover:bg-gray-50 transition-colors">
                <Link
                  href={`/posts/${post.slug}`}
                  className="text-indigo-600 font-medium hover:underline"
                >
                  {post.title}
                </Link>
                <p className="text-sm text-gray-500">
                  {new Date(post.created_at).toLocaleDateString('uz-UZ')}
                </p>
              </div>
            ))}
          </div>
        ) : (
          <div className="px-6 py-8 text-center text-gray-500">
            Bu foydalanuvchi hali post yozmagan
          </div>
        )}
      </div>
    </div>
  );
}

