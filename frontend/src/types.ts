type Category = {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
};

type Author = {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
};

type Source = {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
};
type Article = {
  id: number;
  title: string;
  description: string;
  url: string;
  thumbnail_url: string;
  author_id: string;
  source_id: string;
  category_id: string;
  published_at: string;
  created_at: string;
  updated_at: string;
  category: Category;
  author: Author;
  source: Source;
};

export default Article;