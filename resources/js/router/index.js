import Vue from 'vue'
import VueRouter from 'vue-router'
import Users from '../components/users/TabsComponent'
import Roles from '../components/roles/TabsComponent'
import Categories from '../components/categories/TabsComponent'
import Expenses from '../components/expenses/TabsComponent'

Vue.use(VueRouter)

  const routes = [
    {
      path: '/users',
      name: 'users',
      component: Users
    },
    {
      path: '/roles',
      name: 'roles',
      component: Roles
    },
    {
      path: '/categories',
      name: 'categories',
      component: Categories
    },
    {
      path: '/expenses',
      name: 'expenses',
      component: Expenses
    }
  ]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
