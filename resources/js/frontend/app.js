import '../bootstrap';
// Axios & Echo global
// require('./bootstrap');

/* Core */
import Vue from 'vue'

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
import app from '../app.vue'

// your config here
let config = {
  toolbar: [
    'removeFormat', 'undo', '|', 'elements', 'fontName', 'fontSize', 'foreColor', 'backColor', 'divider',
    'bold', 'italic', 'underline', 'strikeThrough', 'links', 'divider', 'subscript', 'superscript',
    'divider', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', '|', 'indent', 'outdent',
    'insertOrderedList', 'insertUnorderedList', '|', 'picture', 'tables', '|', 'switchView'
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
Vue.use(Vueditor, config);
/* This is main entry point */
new Vue({
  router,
  render: h => h(app),
}).$mount('#app')
// const app = new Vue({
//   el: '#app'
// });
