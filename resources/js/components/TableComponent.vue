<template>
  <div class="data-table" >

   <!--<div class="loading" v-if="loading">
      <b-spinner></b-spinner>
    </div>
    <div class="error" v-else-if="error.length > 0">
      Error retreiving data.
    </div>-->
    <b-table striped bordered responsive small :items="items" :fields="fields" :busy="loading">
      <template v-slot:cell(name)="data" >
        <div class="image-with-name">
          <image-popup  :alttext="data.value.hero_name" :imgSrc="'/images/heroes/'+data.value.short_name+'.png'" :popupdata="'Role: '+ data.value.role+ ' <br>Type: '+data.value.type"></image-popup>  <span class="emphasis">{{ data.value.hero_name }}</span>
        </div>
      </template>
      <template v-slot:cell(win_rate)="data">
        {{data.value}}%
      </template>
      <template v-slot:cell(ban_rate)="data">
        {{data.value}}%
      </template>
      <template v-slot:cell(popularity)="data">
        {{data.value}}%
      </template>
      <template v-slot:cell(change)="data">
        {{data.value}}%
      </template>
      <template v-slot:cell(talent_builds)="row">
        <b-button size="sm" @click="row.toggleDetails" class="mr-2">
          {{ row.detailsShowing ? 'Hide' : 'Show'}} <span class="mobileHide">Talent Builds</span>
        </b-button>
      </template>
      <template v-slot:row-details="row" :loaded="false">
        <b-card>
          <b-row class="mb-2">

            <hero-talent-data :hero="row.item.name" @loading-status="talentsLoaded=true"></hero-talent-data>
          </b-row>

        </b-card>
      </template>
    </b-table>

  </div>
</template>

<script>
  export default {
    name: 'TableComponent',
    props: {
      fields: Array,
      inputurl: String,
    },
    data(){
      return {
        stickyHeader: true,
        items: null,
        talentsLoaded: false,
        loading: false,
      }
    },
    created(){
      this.getData();
    },
    methods: {
      getData(){
        this.$http.get(this.inputurl)
          .then(response => {
            this.items = response.data
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
