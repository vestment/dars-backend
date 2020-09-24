import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)
import player from './components/player.vue'
import test from './components/test.vue'
const routes = [
  {
    path: '/player/:slug',
    name: 'player',
    component: player,
  },
  {
    path: '/test/:slug',
    name: 'test',
    component: test,
  },


];
const router = new VueRouter({
  base: process.env.BASE_URL,
  mode:'history',
  routes: routes,
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
})
router.afterEach((to) => {
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = "none";
  }
});
router.beforeEach((to, from, next) => {
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = "block";
  }
  return next();
});
export default router
