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
      <!-- Add a small "Reset Password" link -->
      <div class="reset-password-link">
        <router-link to="/reset-password">Forgot your password?</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    isLoggedIn: Boolean,
  },
  data() {
    return {
      email: '',
      password: '',
      jwtSecretError: false,
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost:8000/api/auth/login', {
          email: this.email,
          password: this.password,
        });
        if (response.data.message === 'JWT secret is not set') {
          this.jwtSecretError = true;
        } else if (response.data.message === 'User does not exist') {
          this.errorMessage = 'User does not exist. Please check your email and try again.';
        } else {
          this.successMessage = 'Login successful!';
          const token = response.data.user.access_token; // Get the access token
          const userId = response.data.user.user.id; // Get the user ID
          localStorage.setItem('token', token); // Store token in localStorage
          localStorage.setItem('email', this.email); // Store email in localStorage
          localStorage.setItem('userId', userId); // Store user ID in localStorage
          console.log('Token:', token); // Show token in the console

          // Emit an event to notify App.vue that the user is logged in
          this.$emit('login-success');

          // Redirect the user
          this.redirectUser();
        }
      } catch (error) {
        this.errorMessage = `Failed to login: ${error.response ? error.response.data.message : error.message}`;
      }
    },
    redirectUser() {
      this.$router.push('/admin-panel');
    },
  },
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

/* Reset Password Link Styles */
.reset-password-link {
  margin-top: 15px;
  text-align: center;
}

.reset-password-link a {
  color: #e50914; /* Netflix red */
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.3s ease;
}

.reset-password-link a:hover {
  color: #f40612; /* Lighter red on hover */
}
</style>