<template>
  <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
  <button v-if="button" :class="classes">
    <slot></slot>
  </button>

  <router-link
    v-else
    :to="to"
    active-class="text-white bg-gray-900"
    :class="classes"
  >
    <slot></slot>
  </router-link>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import { Location } from 'vue-router';

@Component
export default class NavbarLink extends Vue {
  @Prop({ default: false })
  button!: boolean;

  @Prop({ default: false })
  responsive!: boolean;

  @Prop({ default: false })
  active!: boolean;

  @Prop({ default: null })
  to!: Location | string | null;

  get classes(): string {
    let base = 'px-3 py-2 font-medium rounded-md';

    if (this.responsive) {
      base = `${base} block text-base`;
    } else {
      base = `${base} text-sm`;
    }

    return this.active
      ? `${base} text-white bg-gray-900`
      : `${base} text-gray-300 hover:bg-gray-700 hover:text-white`;
  }
}
</script>
