import '../bootstrap';
// Axios & Echo global
// require('./bootstrap');

/* Core */
import Vue from 'vue'

import App from '../app.vue'
import player from '../components/player.vue'

// import VueVideoPlayer from 'vue-video-player'
 
// require videojs style
// import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'
 


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

const app = new Vue({
  el: '#app'
});
