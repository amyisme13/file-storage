<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator';

@Component({
  metaInfo() {
    return {
      title: this.$route.meta.title,
      titleTemplate: '%s - File Storage',
    };
  },
})
export default class App extends Vue {}
</script>
