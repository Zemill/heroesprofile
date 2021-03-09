<template>
  <div class="container">
    <table-component :columns="columns" :inputurl="inputurl"></table-component>
  </div>
</template>

<script>
import TableComponent from './TableComponent.vue'

  export default {
    name: 'LeaderboardPage',
    components: {
      TableComponent
    },
    props: {
      columns: Array,
      inputurl: String,
    },
    mounted() {
      console.log('Leaderboard Page Component mounted.')
    }
  }
</script>
