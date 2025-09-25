export interface User {
  id: number;
  name: string;
  username: string;
  email?: string;
  avatar_url?: string;
  xp: number;
  bio?: string;
  stats?: {
    posts_count?: number;
    followers_count?: number;
    following_count?: number;
  };
  badges?: {
    id: number;
    name: string;
    icon_url?: string;
  }[];
  level?: {
    id: number;
    name: string;
    min_xp: number;
  };
}

export interface Tag {
  name: string;
  slug: string;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
}

export interface Post {
  id: number;
  slug: string;
  title: string;
  content_markdown: string;
  status: string;
  score: number;
  answers_count: number;
  tags: Tag[];
  category?: Category;
  user: User;
  ai_suggestion?: {
    model: string;
    content_markdown: string;
  };
  is_ai_suggested: boolean;
  created_at: string;
}

export interface Comment {
  id: number;
  content_markdown: string;
  score: number;
  user: User;
  children?: Comment[];
  created_at: string;
}

export interface WikiArticle {
  id: number;
  title: string;
  slug: string;
  content_markdown: string;
  status: string;
  version: number;
  created_at: string;
}