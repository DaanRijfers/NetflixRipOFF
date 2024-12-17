import { createRouter, createWebHistory } from 'vue-router';
import UserLogin from './components/UserLogin.vue';
import UserRegister from './components/UserRegister.vue';
import UserResetPassword from './components/UserResetPassword.vue';

const routes = [
  { path: '/login', component: UserLogin },
  { path: '/register', component: UserRegister },
  { path: '/reset-password', component: UserResetPassword }
];

const router = createRouter({
  history: createWebHistory(), // Gebruik van de history mode
  routes
});

export default router;
