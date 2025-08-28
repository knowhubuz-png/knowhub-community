import { api } from '@/lib/api';

async function getTrendingPosts() {
  const res = await api.get('/posts?sort=trending');
  return res.data;
}

export default async function HomePage() {
  const posts = await getTrendingPosts();

  return (
    <main className="max-w-4xl mx-auto px-4 py-8 text-white">
      <h1 className="text-3xl font-bold mb-4">Trend Postlar</h1>
      <div className="space-y-4">
        {posts.data.map((post: any) => (
          <div key={post.id} className="bg-slate-800 p-4 rounded">
            <h2 className="text-xl font-semibold">{post.title}</h2>
            <p className="text-gray-400">{post.content_markdown.slice(0, 120)}...</p>
          </div>
        ))}
      </div>
    </main>
  );
}

