import VueRouter from 'vue-router'
import Vue from 'vue'
import Home from './components/Home.vue'

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',

	routes: [
		{
			path: '/',
			name: 'Home',
			component: Home,
		},
	],

	scrollBehavior (to, from, savedPosition) {
		if (savedPosition) {
			return savedPosition
		} else {
			return { x: 0, y: 0 }
		}
	},
})

export default router
