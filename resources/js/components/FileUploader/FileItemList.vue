<template>
  <div>
    <h3 class="pb-3 font-semibold text-gray-900 sm:text-lg">{{ label }}</h3>

    <ul class="flex flex-wrap flex-1 -m-1">
      <li
        v-if="isEmpty"
        class="flex flex-col items-center justify-center w-full h-full text-center"
      >
        <img
          class="w-32 mx-auto"
          src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png"
          alt="no data"
        />
        <span class="text-gray-500 text-small">No files selected</span>
      </li>

      <slot></slot>
    </ul>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class FileItemList extends Vue {
  @Prop({ default: '' })
  label!: string;

  @Prop({ default: false })
  isEmpty!: boolean;
}
</script>
