import { api } from '../api';

export async function getTrendingPosts() {
  const res = await api.get('/posts?sort=trending');
  return res.data;
}

export async function getPost(slug: string) {
  const res = await api.get(`/posts/${slug}`);
  return res.data;
}

