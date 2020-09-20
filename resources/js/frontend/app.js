import '../bootstrap';
// Axios & Echo global
// require('./bootstrap');

/* Core */
import Vue from 'vue'
<<<<<<< HEAD

import App from '../app.vue'
import player from '../components/player.vue'

import VueVideoPlayer from 'vue-video-player'
 
// require videojs style
import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'
 
Vue.use(VueVideoPlayer, /* {
  options: global default options,
  events: global videojs events
} */)

=======
import app from '../app.vue'
>>>>>>> 0305c5f3b5be19d291e93d628ddf57c47e2063e2
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

Vue.component('player', player)



/* This is main entry point */
new Vue({
  render: h => h(App),
})

// const app = new Vue({
//   el: '#app'
// });
