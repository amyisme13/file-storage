<template>
  <!-- This example requires Tailwind CSS v2.0+ -->
  <div
    class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6"
  >
    <div class="flex justify-between flex-1 sm:hidden">
      <button
        @click="prev"
        :disabled="isFirstPage"
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 disabled:opacity-50"
      >
        Previous
      </button>

      <button
        @click="next"
        :disabled="isLastPage"
        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 disabled:opacity-50"
      >
        Next
      </button>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p
          v-if="showingFrom && showingTo && showingTotal"
          class="text-sm text-gray-700"
        >
          Showing
          <span class="font-medium">{{ showingFrom }}</span>
          to
          <span class="font-medium">{{ showingTo }}</span>
          of
          <span class="font-medium">{{ showingTotal }}</span>
          results
        </p>
      </div>

      <div>
        <button
          @click="prev"
          :disabled="isFirstPage"
          class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 disabled:opacity-50"
        >
          Previous
        </button>

        <button
          @click="next"
          :disabled="isLastPage"
          class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class Pagination extends Vue {
  @Prop({ default: 1 })
  value!: number;

  @Prop({ default: 1 })
  max!: number;

  @Prop({ default: false })
  showingFrom!: number | false;

  @Prop({ default: false })
  showingTo!: number | false;

  @Prop({ default: false })
  showingTotal!: number | false;

  get isFirstPage() {
    return this.value === 1;
  }

  get isLastPage() {
    return this.value === this.max;
  }

  prev() {
    this.$emit('input', this.value - 1);
  }

  next() {
    this.$emit('input', this.value + 1);
  }
}
</script>
