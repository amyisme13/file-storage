<template>
  <svg
    v-if="active"
    class="block w-6 h-6"
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke="currentColor"
    aria-hidden="true"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M6 18L18 6M6 6l12 12"
    />
  </svg>

  <svg
    v-else
    class="block w-6 h-6"
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke="currentColor"
    aria-hidden="true"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M4 6h16M4 12h16M4 18h16"
    />
  </svg>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class MenuIcon extends Vue {
  @Prop({ default: false })
  active!: boolean;
}
</script>
