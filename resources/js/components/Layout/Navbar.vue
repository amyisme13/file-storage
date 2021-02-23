<template>
  <nav class="bg-gray-800">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <img
              class="w-8 h-8"
              src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg"
              alt="Workflow"
            />
          </div>

          <div class="hidden md:block">
            <div class="flex items-baseline ml-10 space-x-4">
              <NavbarLink :to="{ name: 'Home' }">Home</NavbarLink>
            </div>
          </div>
        </div>

        <div class="hidden md:block">
          <div class="flex items-center ml-4 md:ml-6">
            <!-- Profile dropdown -->
            <div v-if="user" class="relative ml-3">
              <div>
                <button
                  class="flex items-center max-w-xs text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                  @click="showUserMenu = !showUserMenu"
                  id="user-menu"
                  aria-haspopup="true"
                >
                  <span class="sr-only">Open user menu</span>
                  <svg
                    class="w-8 h-8 text-gray-200 rounded-full"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                  </svg>
                </button>
              </div>

              <transition
                enter-active-class="transition duration-100 ease-out"
                enter-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <div
                  v-show="showUserMenu"
                  class="absolute right-0 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                  role="menu"
                  aria-orientation="vertical"
                  aria-labelledby="user-menu"
                >
                  <button
                    @click="logout"
                    class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100"
                    role="menuitem"
                  >
                    Sign out
                  </button>
                </div>
              </transition>
            </div>
          </div>
        </div>
        <div class="flex -mr-2 md:hidden">
          <!-- Mobile menu button -->
          <button
            type="button"
            class="inline-flex items-center justify-center p-2 text-gray-400 bg-gray-800 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
            aria-controls="mobile-menu"
            @click="showMobileMenu = !showMobileMenu"
            aria-expanded="false"
          >
            <span class="sr-only">Open main menu</span>
            <MenuIcon :active="showMobileMenu" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div v-show="showMobileMenu" class="md:hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <NavbarLink responsive :to="{ name: 'Home' }">Home</NavbarLink>
      </div>

      <div class="pt-4 pb-3 border-t border-gray-700">
        <!-- User Info -->
        <div v-if="user" class="flex items-center px-5">
          <div class="flex-shrink-0">
            <svg
              class="w-10 h-10 text-gray-200 rounded-full"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              ></path>
            </svg>
          </div>
          <div class="ml-3">
            <div class="text-base font-medium leading-none text-white">
              {{ user.name }}
            </div>

            <div class="text-sm font-medium leading-none text-gray-400">
              {{ user.email }}
            </div>
          </div>
        </div>

        <div class="px-2 mt-3 space-y-1">
          <button
            @click="logout"
            class="block w-full px-3 py-2 text-base font-medium text-left text-gray-400 rounded-md hover:text-white hover:bg-gray-700"
          >
            Sign out
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script lang="ts">
import { AuthModule } from '@/store/modules/auth';
import { Component, Vue } from 'vue-property-decorator';

import MenuIcon from './MenuIcon.vue';
import NavbarLink from './NavbarLink.vue';

@Component({
  components: { MenuIcon, NavbarLink },
})
export default class Navbar extends Vue {
  showUserMenu = false;
  showMobileMenu = false;

  get user() {
    return AuthModule.user;
  }

  async logout() {
    await AuthModule.logout();
    this.$router.push({ name: 'Login' });
  }
}
</script>
