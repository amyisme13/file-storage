import Vue from 'vue';
import VueRouter, { RouteConfig } from 'vue-router';

import { configure } from './hooks';
import FileList from '@/views/FileList.vue';
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
    name: 'FileList',
    component: FileList,
    meta: {
      title: 'File List',
    },
  },
  {
    path: '/upload',
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
