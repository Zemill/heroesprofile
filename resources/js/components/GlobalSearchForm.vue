<template>
  <div class="filter-form">

    <nav class="navbar primary-filter-bar">
      <b-dropdown id="dropdown-form"  ref="dropdown1"  boundary="window" @hidden="updateFields()">
        <template v-slot:button-content>
          Timeframe: {{ timeframetype | caps }} {{ form.timeframe | labels }}
        </template>
        <b-dropdown-form>
          <b-form-group label="Timeframe Type">
            <b-form-radio v-model="timeframetype" value="major" @change="timeframeChange()">Major</b-form-radio>
            <b-form-radio v-model="timeframetype" value="minor" @change="timeframeChange()">Minor</b-form-radio>
          </b-form-group>
          <b-form-group label="Timeframe" class="multiselect-wrapper" >
            <multiselect :show-labels="false" v-model="form.timeframe"  track-by="value" label="key" placeholder="All"  :multiple="true" :options="currentTimeFrameOptions" :searchable="true" :allow-empty="true" >
            </multiselect>
          </b-form-group>
          <b-button @click="hideDropdowns()" variant="primary" class="menu-close">Apply</b-button>
        </b-dropdown-form>
      </b-dropdown>



      <b-dropdown id="dropdown-form2"  ref="dropdown2"  boundary="window" @hidden="updateFields()">
        <template v-slot:button-content>
          Game Type {{ form.gametype | labels }}
        </template>
        <b-dropdown-form>
          <b-form-checkbox-group v-model="form.game_type"  :name="gametypes.name"  >
            <b-form-checkbox v-for="option in gametypes" :value="option.key" :key="option.key"> <div class="checkbox-image" v-if="option.icon"><img :alt="option.name" :src="option.icon"/></div><span v-else>{{ option.text }}</span> </b-form-checkbox>
          </b-form-checkbox-group>
          <b-button @click="hideDropdowns()" variant="primary" class="menu-close">Apply</b-button>
        </b-dropdown-form>
      </b-dropdown>


      <b-dropdown id="dropdown-formheroes"  ref="dropdownheroes"  boundary="window" @hidden="updateFields()">
        <template v-slot:button-content>
          Heroes {{ form.hero | labels }}
        </template>
        <b-dropdown-form>
          <b-form-group label="Heroes">
            <b-form-checkbox-group buttons v-model="form.hero"  :name="heroes.name" class="hero-images-checkbox" >
              <b-form-checkbox v-for="option in heroes" :value="option.name" :key="option.name">   <image-popup  :alttext="option.name" :imgSrc="'/images/heroes/'+option.short_name+'.png'" ></image-popup>
              </b-form-checkbox>
            </b-form-checkbox-group>
          </b-form-group>
          <b-button @click="hideDropdowns()" variant="primary" class="menu-close">Apply</b-button>
        </b-dropdown-form>
      </b-dropdown>


      <b-dropdown id="dropdown-form3"  ref="dropdown3"  boundary="window" @hidden="updateFields()">
        <template v-slot:button-content>
          Rank
        </template>
        <b-dropdown-form>
          <div class="filter-group">
            Player Rank
            <multiselect v-model="form.player_league_tier" track-by="value" label="key" placeholder="All" :multiple="true" :options="leaguetiers" :searchable="true" :allow-empty="true">
            </multiselect>

          </div>
          <div class="filter-group">
            Role Rank
            <multiselect v-model="form.role_league_tier" track-by="value" label="key" placeholder="All" :multiple="true" :options="leaguetiers" :searchable="true" :allow-empty="true">
            </multiselect>
          </div>
          <div class="filter-group">
            Hero Rank
            <multiselect v-model="form.hero_league_tier" track-by="value" label="key" placeholder="All" :multiple="true" :options="leaguetiers" :searchable="true" :allow-empty="true">
            </multiselect>
          </div>
          <b-button @click="hideDropdowns()" variant="primary" class="menu-close">Apply</b-button>
        </b-dropdown-form>
      </b-dropdown>


      <b-dropdown id="dropdown-form4"  ref="dropdown4"  boundary="window" @hidden="updateFields()">
        <template v-slot:button-content>
          More
        </template>
        <b-dropdown-form>
          <div class="filter-group">
            <b-form-group label="Game Map">
              <multiselect v-model="form.game_map" track-by="value" label="key" placeholder="All" :multiple="true" :options="gamemaps" :searchable="true" :allow-empty="true">
              </multiselect>
            </b-form-group>
            <b-form-group label="Hero Level">
              <b-form-checkbox-group  v-model="form.hero_level"  :name="herolevels.name"  >
                <b-form-checkbox v-for="option in herolevels" :value="option.key" :key="option.key"> <div class="checkbox-image" v-if="option.icon"><img :alt="option.name" :src="option.icon"/></div><span v-else>{{ option.text }}</span> </b-form-checkbox>
              </b-form-checkbox-group>
            </b-form-group>
            <b-form-group label="Role">
              <b-form-checkbox-group buttons v-model="form.role"  :name="roles.name"  >
                <b-form-checkbox v-for="option in roles" :value="option.key" :key="option.key"> <div class="checkbox-image" v-if="option.icon"><img :alt="option.name" :src="option.icon"/></div><span v-else>{{ option.text }}</span> </b-form-checkbox>
              </b-form-checkbox-group>
            </b-form-group>
          </div>
          <b-button @click="hideDropdowns()" variant="primary" class="menu-close">Apply</b-button>
        </b-dropdown-form>
      </b-dropdown>

    </nav>
  </div>
</template>

<script type="text/ecmascript-6">

export default {
  props: {
    majorpatches: Array,
    gametypes: Array,
    heroes: Object,
    leaguetiers: Array,
    herolevels: Object,
    roles: Array,
    gamemaps: Object,
  },
  data() {
    return{
      form: {},
      timeframetype: 'major',
      gametype: 'Storm League',
      timeframe: null,

      /*
      selectedTimeframe: null,
      showTimeframe: false,
      showGameType: false,
      showFilterMenu: false,
      finalFields : {},
      currentlySelectedPopup : '',
      selectedPopover : '',
      filtersChanged : true
      */
    }
  },
  mounted () {
  },
  watch: {
    form: function(){
      this.filtersChanged = true
    }
  },
  methods: {
    timeframeChange: function ()
    {
      this.form['timeframe'] = undefined;
      this.timeframe = this.timeframe
    },
    hideDropdowns(){
      // Close the menu and (by passing true) return focus to the toggle button
      this.$refs.dropdown1.hide(true)
      this.$refs.dropdown2.hide(true)
      this.$refs.dropdown3.hide(true)
      this.$refs.dropdown4.hide(true)
      this.$refs.dropdownheroes.hide(true)
    },
    updateFields(){
      if(this.filtersChanged){
        this.finalFields = this.form;
        this.$store.dispatch('updateFormData',  this.form);
        this.filtersChanged = false;
      }
    }
  },
  filters: {
    caps: function(value) {
      if (!value) return ''
      return value.split('_').map(function(item) {
        return item.charAt(0).toUpperCase() + item.substring(1);
      }).join(' ');
    },
    labels: function(selection){ // Add this filter to labels to display what's filtered in that field
      if(!selection) return ''
      var values = [];
      var i = 0;
      var counter = 0;
      for (const [key, val] of Object.entries(selection)) {
        if(val.text){
          values.push(val.text);
          counter++;
          if(counter > 1){
            values.push ("...");
            break;
          }

        }
        else if(val.name){
          values.push(val.name);
          counter++;
          if(counter > 1){
            values.push ("...");
            break;
          }

        }
        else{
          values.push(val);
          counter++;
          if(counter > 1){
            values.push ("...");
            break;
          }
        }
      }
      return "("+values.join(', ')+")";
    }
  },
  computed: {
    currentTimeFrameOptions: function() {
      let timeframe = ''
      switch(this.timeframetype) {
        case 'major':
          timeframe = this.majorpatches
          break;
        case 'minor':
          timeframe = this.minor_patch
          break;
        default:
          timeframe = this.majorpatches
      }

      console.log(timeframe);

      return timeframe
    }
  },
}
</script>
<style>
.primary-filter-popup{background-color:white; color:black;}
.popover-body{
  display:flex;
  flex-wrap:wrap;

}
.popover-body .menu-close{
  flex-basis:100%;
}
.multiselect-wrapper { width:100%;}

.test3{position:relative;}
.custom-popover{position:absolute;}

</style>
