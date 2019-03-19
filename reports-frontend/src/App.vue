<template>
  <div id="app">
    <app-layout>
      <router-view />
    </app-layout>
  </div>
</template>

<script>
import AppLayout from './AppLayout'

export default {
  name: 'App',
  components: {
    AppLayout
  }
}
</script>


<style lang="scss">
#app {
  min-width: 360px;
  height: 100vh;
}
</style>
