// Axios & Echo global
require('./bootstrap');

/* Core */
// import Vue from 'vue'
import Vue from 'vue';


import Buefy from 'buefy'

/* Router & Store */
import router from './router'

/* Vue. Main component */
import App from './App.vue'
// Axios & Echo global


/* Vue. Main component */
//import PlayCourse from './website/PlayCourse.vue'


Vue.config.productionTip = false

// import VueVideoPlayer from 'vue-video-player'

// require videojs style
import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'


/* Main component */
//Vue.component('PlayCourse', PlayCourse)

Vue.component('player', require('./components/player.vue').default);


/* This is main entry point */

/*new Vue({
  render: h => h(PlayCourse),
}).$mount('#playcourse')*/

const app = new Vue({
  el: '#app'
});


/* Vue. Component in recursion */

/* Collapse mobile aside menu on route change */



/* These components are used in recursion algorithm */

/* Main component */
Vue.component('App', App)

/* Buefy */
Vue.use(Buefy)




/* This is main entry point */

new Vue({
  
  router,
  render: h => h(App),
//   mounted() {
//     document.documentElement.classList.remove('has-spinner-active')
//   }
}).$mount('#app')
