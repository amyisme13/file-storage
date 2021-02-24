<template>
  <li class="block w-1/2 h-24 p-1 sm:w-1/3 md:w-1/4 lg:w-1/6 xl:w-1/8">
    <article
      tabindex="0"
      class="relative w-full h-full bg-gray-100 rounded-md shadow-sm focus:outline-none focus:shadow-outline"
    >
      <div
        class="absolute top-0 left-0 h-full"
        :class="file.success === false ? 'bg-red-200' : 'bg-green-200'"
        :style="{ width: `${file.progress}%` }"
      ></div>

      <section
        class="absolute top-0 z-20 flex flex-col w-full h-full px-3 py-2 text-xs break-words rounded-md"
      >
        <span class="flex-1 text-gray-800">
          {{ file.file.name }}
        </span>

        <div class="flex">
          <span class="text-gray-800">
            <i>
              <svg
                class="w-4 h-4 pt-1 ml-auto fill-current"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
              >
                <path d="M15 2v5h5v15h-16v-20h11zm1-2h-14v24h20v-18l-6-6z" />
              </svg>
            </i>
          </span>

          <p class="p-1 text-xs text-gray-700">
            {{ toPrettyBytes(file.file.size) }}
          </p>

          <div v-if="file.success !== null" class="p-1 ml-auto text-gray-800">
            <svg
              v-if="file.success"
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
              ></path>
            </svg>

            <svg
              v-else
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </div>

          <button
            v-else-if="showTrashIcon"
            @click="$emit('removeItem', file.id)"
            class="p-1 ml-auto text-gray-800 rounded-md focus:outline-none hover:bg-gray-100"
          >
            <svg
              class="w-4 h-4 ml-auto"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              ></path>
            </svg>
          </button>
        </div>
      </section>
    </article>
  </li>
</template>

<script lang="ts">
import prettyBytes from 'pretty-bytes';
import { Component, Prop, Vue } from 'vue-property-decorator';

import { FileToBeUploaded } from '@/types/files';

@Component
export default class FileItem extends Vue {
  @Prop({ required: true })
  file!: FileToBeUploaded;

  @Prop({ default: true })
  showTrashIcon!: boolean;

  toPrettyBytes(number: number) {
    return prettyBytes(number);
  }
}
</script>
