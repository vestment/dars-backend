import '../bootstrap';
// Axios & Echo global
// require('./bootstrap');

/* Core */
import Vue from 'vue'
// import {VueLoaderPlugin} from 'vue-loader'
import player from '../components/player.vue'
import Vuex from 'vuex'
import Vueditor from 'vueditor'
import router from '../router'


import 'vueditor/dist/style/vueditor.min.css'
Vue.config.productionTip = false

// import VueVideoPlayer from 'vue-video-player'

// require videojs style
// import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'


/* Main component */
//Vue.component('PlayCourse', PlayCourse)
Vue.component('player', require('../components/player.vue').default);
Vue.component('test', require('../components/test.vue').default)
import VuePlyr from 'vue-plyr'
import app from '../app.vue'
import VueToast from 'vue-toast-notification';
// your config here
let config = {
  toolbar: [
    'removeFormat', 'undo', '|', 'elements', 'fontName', 'fontSize', 'foreColor', 'divider',
    'bold', 'italic', 'underline', 'links', 'divider', 'subscript', 'superscript',
    'divider', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', '|', 
    'insertOrderedList', 'insertUnorderedList', '|',  'tables', 'switchView'
  ],
  fontName: [
    {val: 'arial black'},
    {val: 'times new roman'},
    {val: 'Courier New'}
  ],
  fontSize: ['12px', '14px', '16px', '18px', '0.8rem', '1.0rem', '1.2rem', '1.5rem', '2.0rem'],
  uploadUrl: ''
};
Vue.use(Vuex);
Vue.use(VueToast);
// Vue.use(VueLoaderPlugin);
Vue.use(Vueditor, config);
Vue.use(VuePlyr, {
  plyr: {
    fullscreen: { enabled: false }
  },
  emit: ['ended']
})
/* This is main entry point */
new Vue({
  router,
  render: h => h(app),
}).$mount('#app')
// const app = new Vue({
//   el: '#app'
// });
