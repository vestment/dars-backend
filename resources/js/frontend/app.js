import '../bootstrap';
// Axios & Echo global
// require('./bootstrap');

/* Core */
import Vue from 'vue'
import app from '../app.vue'
/* Vue. Main component */
//import PlayCourse from './website/PlayCourse.vue'


Vue.config.productionTip = false

// import VueVideoPlayer from 'vue-video-player'

// require videojs style
// import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'


/* Main component */
//Vue.component('PlayCourse', PlayCourse)

Vue.component('player', require('../components/player.vue').default);


/* This is main entry point */

new Vue({
  render: h => h(app),
}).$mount('#app')

// const app = new Vue({
//   el: '#app'
// });
