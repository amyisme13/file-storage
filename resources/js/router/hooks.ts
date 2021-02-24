import Router, { Route } from 'vue-router';

import { AuthModule } from '@/store/modules/auth';

const loadUserWhenNull = () => !AuthModule.user && AuthModule.loadUser();

const getLoginRoute = (to: Route) =>
  to.name === 'Login' ? undefined : { name: 'Login' };

export const configure = (router: Router) => {
  router.beforeEach(async (to: Route, _: Route, next: any) => {
    if (!AuthModule.authenticated) {
      const loginRoute = getLoginRoute(to);
      next(loginRoute);
      return;
    }

    if (to.name === 'Login') {
      next({ name: 'FileList' });
      return;
    }

    await loadUserWhenNull();

    next();
  });
};
