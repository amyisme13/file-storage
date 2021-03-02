/**
 * Common
 */

export interface ValidationError {
  [key: string]: string[];
}

export interface LaravelError {
  message: string;
  errors?: ValidationError;
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  to: number;
  from: number;
}

export interface Paginated<T> {
  data: T[];
  meta: PaginationMeta;
}

/**
 * Auth
 */

export interface LoginDTO {
  email: string;
  password: string;
  remember?: boolean;
}

/**
 * Users
 */

export interface User {
  email: string;
  name: string;
}

/**
 * Files
 */

export interface File {
  user: User;

  name: string;
  type: string;
  size: number;
  slug: string;

  url?: string;
  video_url?: string;
  thumbnail_url?: string;
  scorm_url?: string;

  created_at: string;
  uploaded_at: string;
  processed_at: string;
}

export interface CreatedFile extends File {
  put_url: string;
}

export interface IndexFileDTO {
  page?: number;
  search?: string;
}

export interface StoreFileDTO {
  name: string;
  type: string;
  size: number;
}
