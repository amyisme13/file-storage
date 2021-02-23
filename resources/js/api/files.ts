import request from '@/utils/request';
import {
  File,
  Paginated,
  CreatedFile,
  StoreFileDTO,
  IndexFileDTO,
} from '@/types/api';

export const index = (input?: IndexFileDTO) =>
  request.get<Paginated<File>>('files', {
    params: input,
  });

export const store = (input: StoreFileDTO) =>
  request.post<CreatedFile>('files', input);

export const show = (slug: string) =>
  request.get<Paginated<File>>(`files/${slug}`);

export const update = (slug: string) => request.put<File>(`files/${slug}`);

export const destroy = (slug: string) => request.delete(`files/${slug}`);
