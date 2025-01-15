import { createRouter, createWebHistory } from 'vue-router';
import UserLogin from './components/UserLogin.vue';
import UserRegister from './components/UserRegister.vue';
import UserResetPassword from './components/UserResetPassword.vue';
import AdminPanel from './components/AdminPanel.vue';
import UserProfile from './components/UserProfile.vue';
import UserLogout from './components/UserLogout.vue';

const routes = [
  { path: '/login', component: UserLogin },
  { path: '/register', component: UserRegister },
  { path: '/reset-password', component: UserResetPassword },
  { path: '/admin-panel', component: AdminPanel },
  { path: '/profile', component: UserProfile },
  { path: '/logout', component: UserLogout },
];

const router = createRouter({
  history: createWebHistory(), // Gebruik van de history mode
  routes
});

export default router;
