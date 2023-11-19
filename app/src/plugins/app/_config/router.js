import { createRouter, createWebHashHistory } from "vue-router"

const routes = [
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
		path: '/:catchAll(.*)',
		name: 'NotFound',
	}
]

const router = createRouter({
	history: createWebHashHistory(process.env.BASE_URL),
	routes
})

router.beforeEach((to, from) => {
	if (to.meta.requiresAuth === true) {
		if (!localStorage.getItem('myoctober_backend_user_token')) return { name: from.name || "Login" };
	} else {
		if (to.name === "NotFound") return { name: "Home" };
		if ((to.name === "Login" || to.name === "Register") && localStorage.getItem('myoctober_backend_user_token')) return false;
	}
})

export default router