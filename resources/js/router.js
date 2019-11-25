import Vue from 'vue'
import Router from 'vue-router'
import Main from './layouts/main/Main.vue';
import Campaigns from './views/Campaigns.vue';
import AddCampaign from './views/AddCampaign.vue';
import Files from './views/Files.vue';
import Countries from './views/Countries.vue';
import Analytics from './views/Analytics.vue';
import Login from './views/Login.vue';
import Register from './views/Register.vue';
import Settings from './views/Settings.vue';
import Home from './views/Home.vue';
//import { settings } from 'cluster';

Vue.use(Router)

const router = new Router({
	mode: 'history',
	base: process.env.BASE_URL,
	scrollBehavior () {
		return { x: 0, y: 0 }
	},
	routes: [
		{
			path: '/5cd1pvt2020',
			component: Register
		},
		{
			path: '/5cd2pvt2020',
			component: Login
		},
		{
			path: '/api'
		},
		{
			path: '/home',
			component: Home,
			meta: {
				requiresAuth: false
			}
		},
		{
			path: '/',
			meta: {
				requiresAuth: true
			},
			name: 'main',
			component: Main,
			children: [
				{
					path: '/campaigns',
					component: Campaigns,
				},
				{
					path: '/campaigns/add',
					component: AddCampaign,
				},
				{
					path: '/campaigns/analytics/:id',
					component: Analytics,
				},
				{
					path: '/countries',
					component: Countries,
				},
				{
					path: '/files',
					component: Files,
				},
				{
					path: '/settings',
					component: Settings,
				}
			]
		},
		{
			path: '*',
			redirect: '/home'
		}
	],
})



export default router