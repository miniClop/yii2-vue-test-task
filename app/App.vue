<template>
  <div class='container'>
    <a-typography-title>Тестовое задание для backend-программиста</a-typography-title>
    <router-view></router-view>
  </div>
</template>

<script>
import ClientsList from './components/ClientsList.vue';
import ClientItem from './components/ClientItem';

export default {
  components: {
    ClientsList,
    ClientItem
  }
}
</script>

<style scoped>
.container {
  padding: 2%;
}
</style>
