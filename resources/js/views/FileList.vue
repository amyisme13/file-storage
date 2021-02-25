<template>
  <AppLayout>
    <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
      <div class="grid grid-cols-1 gap-2 p-4 bg-white border-b sm:grid-cols-12">
        <div class="relative sm:col-span-4">
          <input
            v-model="search"
            type="text"
            placeholder="Search..."
            class="block w-full px-8 py-2 text-gray-800 placeholder-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 focus:z-10 sm:text-sm"
          />

          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            class="absolute w-4 h-4 text-gray-500 left-3 bottom-3"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
          </svg>
        </div>

        <button
          @click="loadFiles"
          class="py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-md sm:col-span-2 lg:col-span-1"
        >
          Refresh
        </button>

        <router-link
          :to="{ name: 'FileUploader' }"
          class="py-2 text-sm font-medium text-center text-white bg-indigo-600 border-0 rounded-md sm:col-start-9 sm:col-span-4 md:col-start-11 md:col-span-2"
        >
          Upload Files
        </router-link>
      </div>

      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              File
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Uploader
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Status
            </th>
            <th scope="col" class="relative px-6 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>

        <tbody v-if="loading">
          <tr class="h-36 bg-gradient-to-br from-gray-600 to-gray-400">
            <td colspan="4"></td>
          </tr>
        </tbody>

        <tbody v-else class="bg-white divide-y divide-gray-200">
          <tr v-for="file in files" :key="file.slug">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10">
                  <img
                    v-if="file.thumbnail_url"
                    class="w-10 h-10 rounded-md"
                    :src="file.thumbnail_url"
                    alt=""
                  />

                  <svg
                    v-else
                    class="w-10 h-10"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                  </svg>
                </div>

                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    <a :href="file.url" target="_blank">{{ file.name }}</a>
                  </div>

                  <div class="text-sm text-gray-500">
                    {{ toPrettyBytes(file.size) }}
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">
                {{ file.user.name }}
              </div>

              <div class="text-sm text-gray-500">
                {{ file.user.email }}
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <span
                v-if="file.processed_at"
                class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full"
              >
                Processed
              </span>

              <span
                v-else
                class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full"
              >
                Processing
              </span>
            </td>

            <td
              class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap"
            >
              <a
                v-if="file.type.includes('video/')"
                :href="`${playerUrl}/${file.slug}`"
                target="_blank"
                class="text-indigo-500 hover:text-indigo-700"
              >
                Play
              </a>

              <button
                @click="deleteFile(file)"
                class="ml-2 text-red-500 hover:text-red-700"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <Pagination
        v-if="meta"
        :max="maxPage"
        :showingFrom="meta.from"
        :showingTo="meta.to"
        :showingTotal="meta.total"
        v-model="page"
      />
    </div>
  </AppLayout>
</template>

<script lang="ts">
import prettyBytes from 'pretty-bytes';
import { Component, Vue, Watch } from 'vue-property-decorator';

import { destroy, index } from '@/api/files';
import AppLayout from '@/components/Layout/Layout.vue';
import Pagination from '@/components/Pagination.vue';
import { File, PaginationMeta } from '@/types/api';
import config from '@/utils/config';

@Component({
  components: { AppLayout, Pagination },
})
export default class FileList extends Vue {
  loading = false;

  created() {
    this.loadFiles();
  }

  toPrettyBytes(number: number) {
    return prettyBytes(number);
  }

  files: File[] = [];

  search = '';

  page = 1;
  maxPage = 1;
  meta: PaginationMeta | null = null;

  get playerUrl() {
    return `${config.appUrl}/player`;
  }

  async loadFiles() {
    this.loading = true;

    const { data } = await index({ page: this.page, search: this.search });

    this.files = data.data;
    this.maxPage = data.meta.last_page;
    this.meta = data.meta;

    this.loading = false;
  }

  async deleteFile(file: File) {
    const confirmed = confirm(`Are you sure you want to delete ${file.name}?`);

    if (confirmed) {
      await destroy(file.slug);
      this.loadFiles();
    }
  }

  @Watch('page')
  pageChanged() {
    this.loadFiles();
  }

  @Watch('search')
  searchChanged() {
    this.loadFiles();
  }
}
</script>
