// Axios & Echo global
require('./bootstrap');

/* Core */
// import Vue from 'vue'
import Vue from '../../node_modules/vue';



/* Vue. Main component */
//import PlayCourse from './website/PlayCourse.vue'


Vue.config.productionTip = false


// require videojs style
// import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'



/* Main component */
//Vue.component('PlayCourse', PlayCourse)

Vue.component('player', require('../../../resources/js/components/player.vue').default);


/* This is main entry point */

/*new Vue({
  render: h => h(PlayCourse),
}).$mount('#playcourse')*/

const app = new Vue({
  el: '#app'
});
