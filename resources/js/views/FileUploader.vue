<template>
  <AppLayout>
    <template #header>
      <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold text-gray-900">
            <span class="opacity-75">
              <router-link :to="{ name: 'FileList' }" class="hover:underline">
                File List
              </router-link>
              /
            </span>

            Upload
          </h1>
        </div>
      </header>
    </template>

    <!-- Container -->
    <article
      v-if="!uploading"
      aria-label="File Upload Section"
      class="relative flex flex-col h-full bg-white rounded-md shadow-xl"
      @dragenter="dragEnter"
      @dragover="dragOver"
      @dragleave="dragLeave"
      @drop="drop"
    >
      <!-- Drop Overlay -->
      <div
        :class="showDropOverlay ? 'bg-white opacity-70' : 'opacity-0'"
        class="absolute top-0 left-0 z-50 flex flex-col items-center justify-center w-full h-full rounded-md pointer-events-none"
      >
        <i>
          <svg
            class="w-12 h-12 mb-3 text-blue-700 fill-current"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
          >
            <path
              d="M19.479 10.092c-.212-3.951-3.473-7.092-7.479-7.092-4.005 0-7.267 3.141-7.479 7.092-2.57.463-4.521 2.706-4.521 5.408 0 3.037 2.463 5.5 5.5 5.5h13c3.037 0 5.5-2.463 5.5-5.5 0-2.702-1.951-4.945-4.521-5.408zm-7.479-1.092l4 4h-3v4h-2v-4h-3l4-4z"
            />
          </svg>
        </i>

        <p class="text-lg text-blue-700">Drop files to upload</p>
      </div>

      <!-- scroll area -->
      <section class="flex flex-col w-full h-full p-8 overflow-auto">
        <header
          class="flex flex-col items-center justify-center py-12 border-2 border-gray-400 border-dashed"
        >
          <p
            class="flex flex-wrap justify-center mb-3 font-semibold text-gray-900"
          >
            <span>Drag and drop your files anywhere or</span>
          </p>

          <input
            multiple
            @change="fileInputChanged"
            ref="fileInput"
            type="file"
            class="hidden"
          />

          <button
            @click="openFileSelect"
            class="px-3 py-1 mt-2 bg-gray-200 rounded-sm hover:bg-gray-300 focus:shadow-outline focus:outline-none"
          >
            Click here
          </button>
        </header>

        <FileItemList class="pt-8" label="To Upload" :isEmpty="filesIsEmpty">
          <FileItem v-for="file in files" :key="file.id" :file="file" />
        </FileItemList>
      </section>

      <!-- Actions Footer -->
      <footer class="flex justify-end px-8 pt-4 pb-8">
        <button
          @click="files = []"
          class="px-4 py-2 text-sm rounded-md hover:bg-gray-300 focus:shadow-outline focus:outline-none"
        >
          Cancel
        </button>

        <button
          @click="upload"
          :disabled="filesIsEmpty"
          :class="filesIsEmpty ? '' : 'hover:bg-indigo-700'"
          class="px-4 py-2 ml-3 text-sm text-white bg-indigo-600 rounded-md focus:shadow-outline focus:outline-none disabled:opacity-50"
        >
          Upload now
        </button>
      </footer>
    </article>

    <article
      v-else
      class="relative flex flex-col h-full bg-white rounded-md shadow-xl"
    >
      <section class="flex flex-col w-full h-full p-8 overflow-auto">
        <FileItemList :label="uploaded ? 'Uploaded' : 'Uploading'">
          <FileItem
            v-for="file in files"
            :key="file.id"
            :file="file"
            :showTrashIcon="false"
          />
        </FileItemList>
      </section>

      <!-- Actions Footer -->
      <footer v-if="uploaded" class="flex justify-end px-8 pt-4 pb-8">
        <button
          @click="resetPage"
          class="px-4 py-2 text-sm rounded-md hover:bg-gray-300 focus:shadow-outline focus:outline-none"
        >
          Upload More Files
        </button>

        <router-link
          :to="{ name: 'FileList' }"
          class="block px-4 py-2 ml-3 text-sm text-white bg-indigo-600 rounded-md focus:shadow-outline focus:outline-none disabled:opacity-50 hover:bg-indigo-700"
        >
          Back to File List
        </router-link>
      </footer>
    </article>
  </AppLayout>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import { putObject } from '@/api/aws';
import { store as storeFile, update as updateFile } from '@/api/files';
import FileItem from '@/components/FileUploader/FileItem.vue';
import FileItemList from '@/components/FileUploader/FileItemList.vue';
import AppLayout from '@/components/Layout/Layout.vue';
import { FileToBeUploaded } from '@/types/files';

@Component({
  components: { AppLayout, FileItem, FileItemList },
})
export default class FileUploader extends Vue {
  files: FileToBeUploaded[] = [];

  get filesIsEmpty() {
    return this.files.length === 0;
  }

  addFiles(files: FileList) {
    for (const file of files) {
      const objectUrl = URL.createObjectURL(file);
      this.files.push({
        file,
        id: objectUrl,
        progress: 0,
        success: null,
      });
    }
  }

  removeFile(id: string) {
    this.files = this.files.filter((f) => f.id !== id);
  }

  openFileSelect() {
    (this.$refs.fileInput as HTMLInputElement).click();
  }

  fileInputChanged(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files) {
      this.addFiles(input.files);
      input.value = '';
    }
  }

  /**
   * Drag & Drop
   */

  showDropOverlay = false;
  counter = 0;

  dragHasFiles(event: DragEvent) {
    if (!event.dataTransfer) {
      return false;
    }

    return event.dataTransfer.types.indexOf('Files') > -1;
  }

  dragEnter(event: DragEvent) {
    event.preventDefault();
    if (this.dragHasFiles(event)) {
      this.counter += 1;
      this.showDropOverlay = true;
    }
  }

  dragOver(event: DragEvent) {
    if (this.dragHasFiles(event)) {
      event.preventDefault();
    }
  }

  dragLeave() {
    this.counter -= 1;
    if (this.counter < 1) {
      this.showDropOverlay = false;
    }
  }

  drop(event: DragEvent) {
    event.preventDefault();

    if (event.dataTransfer) {
      this.addFiles(event.dataTransfer.files);
      this.showDropOverlay = false;
      this.counter = 0;
    }
  }

  /**
   * Upload
   */

  uploading = false;
  uploaded = false;

  async uploadFile(file: FileToBeUploaded) {
    try {
      const { name, type, size } = file.file;
      const { data: createdFile } = await storeFile({ name, type, size });
      file.storedData = createdFile;

      await putObject(createdFile.put_url, file.file, (progress) => {
        file.progress = (progress.loaded / progress.total) * 100;
      });

      await updateFile(createdFile.slug);

      file.success = true;
    } catch (err) {
      file.progress = 100;
      file.success = false;
    }
  }

  async upload() {
    this.uploading = true;

    for (const file of this.files) {
      await this.uploadFile(file);
    }

    this.uploaded = true;
  }

  resetPage() {
    this.files = [];
    this.uploading = false;
    this.uploaded = false;
  }
}
</script>
