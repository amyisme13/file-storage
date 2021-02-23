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
