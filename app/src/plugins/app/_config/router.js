import { createRouter, createWebHashHistory } from "vue-router"

const routes = [
	{
		path: '/',
		name: 'Root',
	},
	{
		path: '/login',
		name: 'Login',
		component: () => import('@/plugins/lib@auth/login-main.vue'),
	},
	{
		path: '/register',
		name: 'Register',
		component: () => import('@/plugins/lib@auth/register-main.vue'),
	},
	{
		path: '/home',
		name: 'Home',
		component: () => import('@/plugins/lib@home/home-main.vue'),
		meta: { requiresAuth: true },
	},
	{
		path: '/profile',
		name: 'Profile',
		component: () => import('@/plugins/lib@profile/profile-main.vue'),
		meta: { requiresAuth: true },
	},
	{
		path: '/:catchAll(.*)',
		name: 'NotFound',
		component: () => import('@/plugins/lib@not-found/not-found.vue'),
	}
]

const router = createRouter({
	history: createWebHashHistory(process.env.BASE_URL),
	routes
})

router.beforeEach((to, from) => {
	if (to.meta.requiresAuth === true) {
		if (!isLoggedIn()) return { name: from.name || "Login" };
	} else {
		if (to.name === "Root") {
			if (!isLoggedIn()) return { name: 'Login' };
			else return { name: 'Home' };
		}  
		if ((to.name === "Login" || to.name === "Register") && isLoggedIn()) return false;
	}
})

function isLoggedIn() {
	return localStorage.getItem('myoctober_backend_user_token') ? true : false;
}

export default router