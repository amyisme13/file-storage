import request from '@/utils/request';

export const putObject = (
  url: string,
  file: File,
  onUploadProgress: (progress: ProgressEvent) => void
) =>
  request.put(url, file, {
    onUploadProgress,
    headers: {
      'Content-Type': file.type,
    },
  });
