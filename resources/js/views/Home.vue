<template>
  <h1>Hello World</h1>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  metaInfo() {
    return {
      title: this.$route.meta.title,
    };
  },
})
export default class Home extends Vue {}
</script>
