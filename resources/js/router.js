import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)
import player from './components/player.vue'
import test from './components/test.vue'

const router = new Router({
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/player',
      name: 'player',
      component: player,
    },
    {
      path: '/test',
      name: 'Test',
      component: test,
    },
    

  ],
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
})
export default router