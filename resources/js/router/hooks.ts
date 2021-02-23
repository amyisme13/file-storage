import Router, { Route } from 'vue-router';

import { AuthModule } from '@/store/modules/auth';

export const configure = (router: Router) => {
  router.beforeEach(async (to: Route, _: Route, next: any) => {
    if (!AuthModule.authenticated && to.name === 'Login') {
      next();
      return;
    }

    if (!AuthModule.authenticated) {
      next({ name: 'Login' });
      return;
    }

    if (to.name === 'Login') {
      next({ name: 'Home' });
      return;
    }

    if (!AuthModule.user) {
      await AuthModule.loadUser();

      next({ ...to, replace: true });
      return;
    }

    next();
  });
};
