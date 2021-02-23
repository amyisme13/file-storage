import axios from 'axios';

import { AuthModule } from '@/store/modules/auth';
import config from './config';
import router from '@/router';

const request = axios.create({
  baseURL: config.baseApi,
  withCredentials: true, // send cookies on cross-domain requests
});

request.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response.status === 401) {
      AuthModule.resetAuth();
      router.replace({ name: 'Login' });
    }

    return Promise.reject(error);
  }
);

export default request;
