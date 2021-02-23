import Vue from 'vue';
import Vuex from 'vuex';
import { config } from 'vuex-module-decorators';

Vue.use(Vuex);

config.rawError = true;

// Declare empty store first, dynamically register all modules later.
export default new Vuex.Store({});
