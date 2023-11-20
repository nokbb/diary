<template>
  <div id="app">
      <!-- ルータービューを追加 -->
      <router-view></router-view>
    </div>

</template>

<script>
import HelloWorld from '../../diary/src/components/HelloWorld.vue'

export default {
  name: 'App',
  components: {
    HelloWorld
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
