<template>
  <div>
    <Navbar />

    <slot name="header">
      <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold text-gray-900">
            {{ pageTitle }}
          </h1>
        </div>
      </header>
    </slot>

    <main>
      <slot name="container">
        <div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
          <slot></slot>
        </div>
      </slot>
    </main>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import Navbar from './Navbar.vue';

@Component({
  components: { Navbar },
})
export default class Layout extends Vue {
  get pageTitle(): string {
    return this.$route.meta.title;
  }
}
</script>
