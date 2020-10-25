import Vue from 'vue'
import VueRouter from 'vue-router'
import addCategory from './components/Categories/add.vue'
import semesters from './components/Categories/Semesters.vue'
import subjects from './components/Categories/subjects.vue'
import addCourse from './components/Courses/add.vue'




Vue.use(VueRouter)

const routes = [
 
  {
    path: '/user/addcategory',
    name: 'addCategory',
    component: addCategory,
  },
  {
    path: '/user/semesters',
    name: 'semesters',
    component: semesters,
  },
  {
    path: '/user/subjects',
    name: 'subjects',
    component: subjects,
  },
  {
    path: '/user/addCourse',
    name: 'addCourse',
    component: addCourse,
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
