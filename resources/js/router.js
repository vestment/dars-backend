import Vue from 'vue'
import VueRouter from 'vue-router'
import addCategory from './components/Categories/add.vue'

Vue.use(VueRouter)

const routes = [
 
  {
    path: '/categories/add',
    name: 'addCategory',
    component: addCategory,
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
