import Vue from 'vue';
import VueMeta from 'vue-meta';

import App from './App.vue';
import router from './router';
import store from './store';

Vue.use(VueMeta, {
  refreshOnceOnNavigation: true,
});

new Vue({
  store,
  router,
  render: (h) => h(App),
}).$mount('#app');
