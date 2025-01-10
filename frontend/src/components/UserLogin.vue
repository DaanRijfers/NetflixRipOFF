<template>
  <div class="login-container">
    <form @submit.prevent="login">
      <div>
        <label for="email">Email:</label>
        <input type="email" v-model="email" required />
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" v-model="password" required />
      </div>
      <button type="submit">Login</button>
      <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
      <div v-if="jwtSecretError" class="error">JWT secret is not set. Please contact support.</div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',
      jwtSecretError: false
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost:8000/api/auth/login', {
          email: this.email,
          password: this.password
        });
        if (response.data.message === 'JWT secret is not set') {
          this.jwtSecretError = true;
        } else {
          return "Je bent ingelogd";
        }
      } catch (error) {
        this.errorMessage = error.response.data.message || 'Login failed';
      }
    }
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
