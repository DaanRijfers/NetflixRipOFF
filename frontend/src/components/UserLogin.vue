<template>
  <div class="login-container">
    <form @submit.prevent="login" class="login-form">
      <h2>Login</h2>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required />
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required />
      </div>
      <button type="submit" class="login-button">Login</button>
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
/* General Styles */
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 20px;
}

.login-form {
  background: rgba(0, 0, 0, 0.8); /* Semi-transparent black */
  padding: 30px;
  border-radius: 15px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  width: 100%;
  max-width: 400px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.login-form h2 {
  text-align: center;
  color: #e50914; /* Netflix red */
  margin-bottom: 20px;
  font-size: 2rem;
}

/* Form Group Styles */
.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #fff;
  font-size: 1rem;
}

.form-group input {
  width: 95%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 4px;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  transition: border-color 0.3s ease;
}

.form-group input:focus {
  outline: none;
  border-color: #e50914;
}

/* Button Styles */
.login-button {
  width: 100%;
  padding: 12px;
  font-size: 1rem;
  font-weight: bold;
  background-color: #e50914; /* Netflix red */
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-button:hover {
  background-color: #f40612;
}

/* Error and Success Message Styles */
.error, .success {
  margin-top: 15px;
  padding: 10px;
  border-radius: 4px;
  text-align: center;
  font-size: 0.9rem;
}

.error {
  background-color: rgba(229, 9, 20, 0.2); /* Light red background */
  border: 1px solid #e50914;
  color: #e50914;
}

.success {
  background-color: rgba(0, 128, 0, 0.2); /* Light green background */
  border: 1px solid #00ff00;
  color: #00ff00;
}
</style>