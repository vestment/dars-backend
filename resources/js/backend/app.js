 import '@coreui/coreui'
 import '../bootstrap';
// Axios & Echo global
// require('./bootstrap');

/* Core */
import Vue from 'vue'
// import {VueLoaderPlugin} from 'vue-loader'
import Vuex from 'vuex'
import Vueditor from 'vueditor'
import router from '../router'
import Buefy from 'buefy'
Vue.use(Buefy)
import Multiselect from 'vue-multiselect'
Vue.component('multiselect', Multiselect)

import InputTag from 'vue-input-tag'
Vue.component('input-tag', InputTag)
import 'vueditor/dist/style/vueditor.min.css'
Vue.config.productionTip = false

// import VueVideoPlayer from 'vue-video-player'

// require videojs style
// import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'


/* Main component */

import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);
// import VueSimpleAlert from "vue-simple-alert";

// Vue.use(VueSimpleAlert);

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

/* This is main entry point */
new Vue({
  router,
  render: h => h(app),
}).$mount('#app')
// const app = new Vue({
//   el: '#app'
// });

