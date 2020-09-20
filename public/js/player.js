// Axios & Echo global
// require('./bootstrap');
{/* <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script> */}

/* Core */
// Axios & Echo global
// require('../../bootstrap');

// /* Core */
// import Vue from 'vue'
// // import Buefy from 'buefy'

// /* Router & Store */
// import router from '../../resources/js/router'

// /* Vue. Main component */
// import App from '../../resources/js/app.vue'
// Axios & Echo global


/* Vue. Main component */
//import PlayCourse from './website/PlayCourse.vue'


Vue.config.productionTip = false

// import VueVideoPlayer from 'vue-video-player'

// require videojs style
// import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'


/* Main component */
//Vue.component('PlayCourse', PlayCourse)

Vue.component('player', require('../../resources/js/components/player.vue').default);

Vue.component('player', {
  template: require('../../resources/js/components/player.vue')});



/* This is main entry point */

/*new Vue({
  render: h => h(PlayCourse),
}).$mount('#playcourse')*/



/* Vue. Component in recursion */

/* Collapse mobile aside menu on route change */



/* These components are used in recursion algorithm */

/* Main component */
// Vue.component('App', App)

/* Buefy */
// Vue.use(Buefy)




/* This is main entry point */

new Vue({
  
  router,
  // render: h => h(App),
//   mounted() {
//     document.documentElement.classList.remove('has-spinner-active')
//   }
}).$mount('#app')

// import Vue from 'vue'
// import Vue from '../../node_modules/vue';



/* Vue. Main component */
//import PlayCourse from './website/PlayCourse.vue'


Vue.config.productionTip = false


// require videojs style
// import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'



/* Main component */
//Vue.component('PlayCourse', PlayCourse)


/* This is main entry point */

/*new Vue({
  render: h => h(PlayCourse),
}).$mount('#playcourse')*/

const app = new Vue({
  el: '#app'
});
