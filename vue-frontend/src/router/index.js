import { createRouter, createWebHistory } from 'vue-router';
import UserLogin from '@/components/UserLogin.vue';
import UserRegister from '@/components/UserRegister.vue';
import UserDashboard from '@/components/UserDashboard.vue';
import auth from '@/auth'; // Import the auth module

const routes = [
  {
    path: '/login',
    name: 'login',
    component: UserLogin,
    beforeEnter: (to, from, next) => {
      if (auth.isLoggedIn()) {
        next({ name: 'dashboard' }); // Redirect to dashboard if logged in
      } else {
        next(); // Allow access to the login page
      }
    },
  },
  {
    path: '/register',
    name: 'register',
    component: UserRegister,
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: UserDashboard,
    meta: { requiresAuth: true }, // Protect the dashboard route
  },
  {
    path: '/',
    redirect: '/login', // Redirect to login by default
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global navigation guard to protect routes that require authentication
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !auth.isLoggedIn()) {
    next({ name: 'login' }); // Redirect to login if not authenticated
  } else {
    next(); // Allow access to the route
  }
});

export default router;