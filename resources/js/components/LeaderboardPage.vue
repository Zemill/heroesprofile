<template>
  <div class="container">
    <table-component :fields="fields" :inputurl="inputurl"></table-component>
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
      fields: Array,
      inputurl: String,
    },
    mounted() {
      console.log('Leaderboard Page Component mounted.')
    }
  }
</script>
