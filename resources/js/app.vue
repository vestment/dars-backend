<template>
  <div id="app">
            <router-view></router-view>
            <!-- <vue-confirm-dialog></vue-confirm-dialog> -->
    </div>

</template>

<script>
import addCategory from './components/Categories/add.vue'
import semesters from './components/Categories/Semesters.vue'


import axios from "./axios";

export default {
  name: 'app',
  data() {
    return {
    
    }
  },
  components: {
    addCategory,
    semesters
   
  },

}
</script>
