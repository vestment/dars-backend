<template>
  <div id="app">
            <router-view></router-view>
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
