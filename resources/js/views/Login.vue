<template>
  <div
    class="flex items-center justify-center min-h-screen px-4 py-12 bg-gray-50 sm:px-6 lg:px-8"
  >
    <div class="w-full max-w-md space-y-8">
      <div>
        <img
          class="w-auto h-12 mx-auto"
          src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg"
          alt="Workflow"
        />

        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900">
          Sign in to your account
        </h2>
      </div>

      <form @submit.prevent="login" class="mt-8 space-y-6">
        <div
          v-if="error && error.errors"
          class="p-4 text-sm text-red-500 bg-red-200 rounded-md"
        >
          <ul class="mx-4 list-disc">
            <li v-for="(message, i) in error.errors" :key="i">
              {{ message[0] }}
            </li>
          </ul>
        </div>

        <div class="-space-y-px rounded-md shadow-sm">
          <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input
              required
              autocomplete="email"
              id="email-address"
              name="email"
              type="email"
              class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email address"
              v-model="email"
            />
          </div>

          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              required
              autocomplete="current-password"
              id="password"
              name="password"
              type="password"
              class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Password"
              v-model="password"
            />
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember"
              name="remember"
              type="checkbox"
              class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
              v-model="remember"
            />

            <label for="remember" class="block ml-2 text-sm text-gray-900">
              Remember me
            </label>
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md group hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <!-- Heroicon name: solid/lock-closed -->
              <svg
                class="w-5 h-5 text-indigo-500 group-hover:text-indigo-400"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
              >
                <path
                  fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                  clip-rule="evenodd"
                />
              </svg>
            </span>

            Sign in
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator';

import { AuthModule } from '@/store/modules/auth';
import { LaravelError } from '@/types/api';

@Component
export default class Login extends Vue {
  loading = false;

  email = '';
  password = '';
  remember = false;

  error: LaravelError | null = null;

  get formErrors() {
    if (this.error && this.error.errors) {
      return this.error.errors;
    }

    return {};
  }

  async login() {
    this.loading = true;

    try {
      await AuthModule.login({
        email: this.email,
        password: this.password,
        remember: this.remember,
      });

      this.$router.push({ name: 'FileList' });
    } catch (err) {
      if (err && err.response) {
        this.error = err.response.data;
      }
    }

    this.loading = false;
  }
}
</script>
