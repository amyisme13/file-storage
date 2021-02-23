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
            <div class="relative ml-3">
              <div>
                <button
                  class="flex items-center max-w-xs text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                  @click="showUserMenu = !showUserMenu"
                  id="user-menu"
                  aria-haspopup="true"
                >
                  <span class="sr-only">Open user menu</span>
                  <img
                    class="w-8 h-8 rounded-full"
                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                    alt=""
                  />
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
                  <a
                    href="#"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    role="menuitem"
                  >
                    Sign out
                  </a>
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
        <div class="flex items-center px-5">
          <div class="flex-shrink-0">
            <img
              class="w-10 h-10 rounded-full"
              src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
              alt=""
            />
          </div>
          <div class="ml-3">
            <div class="text-base font-medium leading-none text-white">Amy</div>

            <div class="text-sm font-medium leading-none text-gray-400">
              amy.azmim@gmail.com
            </div>
          </div>
        </div>
        <div class="px-2 mt-3 space-y-1">
          <a
            href="#"
            class="block px-3 py-2 text-base font-medium text-gray-400 rounded-md hover:text-white hover:bg-gray-700"
          >
            Sign out
          </a>
        </div>
      </div>
    </div>
  </nav>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import MenuIcon from './MenuIcon.vue';
import NavbarLink from './NavbarLink.vue';

@Component({
  components: { MenuIcon, NavbarLink },
})
export default class Navbar extends Vue {
  showUserMenu = false;
  showMobileMenu = false;
}
</script>
