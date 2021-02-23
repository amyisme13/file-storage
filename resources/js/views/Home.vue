<template>
  <AppLayout>
    <h1>Hello World</h1>
  </AppLayout>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import AppLayout from '@/components/Layout/Layout.vue';

@Component({
  components: { AppLayout },
})
export default class Home extends Vue {}
</script>
