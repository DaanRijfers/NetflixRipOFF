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
      <div v-if="successMessage" class="success">{{ successMessage }}</div>
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
      jwtSecretError: false,
      successMessage: ''
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
          this.successMessage = "Je bent ingelogd";
        }
      } catch (error) {
        this.errorMessage = `Failed to login: ${error.response ? error.response.data.message : error.message}`;
      }
    }
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
