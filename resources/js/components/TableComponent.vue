<template>
  <div class="container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col" v-for="column in columns" :id="column.key">{{ column.text }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in tabledata">
          <th v-for="column in columns">{{ data[column.key] }}</th>
        </tr>
      </tbody>
    </table>
  </div>

</template>

<script>
  export default {
    name: 'TableComponent',
    props: {
      columns: Array,
      inputurl: String,
    },
    data(){
      return {
        tabledata: null
      }
    },
    created(){
      this.getData();
    },
    methods: {
      getData(){
        this.$http.get(this.inputurl)
          .then(response => {
            this.tabledata = response.data
            this.$forceUpdate()
          })
          .catch(error => {
            this.errored = error
          })
          .finally(() => this.loading = false)
      }
    },
    mounted() {
      console.log('Table Component mounted.')
    }
  }
</script>
