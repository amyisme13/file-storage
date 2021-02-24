import Vue from 'vue';
import VueRouter, { RouteConfig } from 'vue-router';

import { configure } from './hooks';
import Home from '@/views/Home.vue';
import Login from '@/views/Login.vue';

Vue.use(VueRouter);

const routes: RouteConfig[] = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: {
      title: 'Login',
    },
  },
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      title: 'Home',
    },
  },
  {
    path: '/files',
    name: 'FileList',
    component: () =>
      import(/* webpackChunkName: "views/file-list" */ '@/views/FileList.vue'),
    meta: {
      title: 'File List',
    },
  },
  {
    path: '/files/upload',
    name: 'FileUploader',
    component: () =>
      import(
        /* webpackChunkName: "views/file-uploader" */ '@/views/FileUploader.vue'
      ),
    meta: {
      title: 'Upload',
    },
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [...routes],
});

configure(router);

export default router;
