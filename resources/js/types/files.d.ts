import { File as StoredFile } from './api';

export interface FileToBeUploaded {
  id: string;
  file: File;
  progress: number;
  success: boolean | null;
  storedData?: StoredFile;
}
