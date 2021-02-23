import config from '@/utils/config';
import request from '@/utils/request';
import { LoginDTO, User } from '@/types/api';

// Login, logout & csrf url is not located in api namespace
export const login = (credentials: LoginDTO) =>
  request.post(`${config.appUrl}/login`, credentials);

export const logout = () => request.post(`${config.appUrl}/logout`);

export const csrf = () => request.get(`${config.appUrl}/sanctum/csrf-cookie`);

export const user = () => request.get<User>('user');
