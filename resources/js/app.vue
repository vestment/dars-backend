<template>
  <div id="app">
    <div class=" ">
      <div class="row ">

        <div >
          <div class="card">
            <div class="ajax-loader" style="display: none" id='loading-bg'></div>
       
            <router-view></router-view>
          </div>
        </div>
      
      </div>
    
     
     
     
    </div>

  </div>
</template>

<script>
import addCategory from './components/Categories/add.vue'

import axios from "./axios";

export default {
  name: 'app',
  data() {
    return {
    
    }
  },
  components: {
    addCategory,
   
  },

}
</script>
