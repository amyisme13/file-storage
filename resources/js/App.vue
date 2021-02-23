<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator';

@Component({
  metaInfo: {
    title: 'Default',
    titleTemplate: '%s - File Storage',
  },
})
export default class App extends Vue {}
</script>
