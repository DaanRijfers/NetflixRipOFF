<template>
  <div class="reset-password-container">
    <form @submit.prevent="resetPassword" class="reset-password-form">
      <h2>Reset Password</h2>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required placeholder="Enter your email" />
      </div>
      <button type="submit" class="reset-button">Send Reset Link</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserResetPassword',
  data() {
    return {
      email: ''
    };
  },
  methods: {
    async resetPassword() {
      try {
        const response = await axios.post('http://localhost:8000/api/auth/password-reset', {
          email: this.email
        });
        alert(response.data.message);
      } catch (error) {
        alert(`Failed to send reset link: ${error.response ? error.response.data.message : error.message}. Please check if the route [password.reset] is defined in your backend.`);
      }
    }
  }
};
</script>

<style scoped>
/* General Styles */
.reset-password-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%; /* Full viewport height */
  padding: 20px;
}

.reset-password-form {
  background: rgba(0, 0, 0, 0.9); /* Darker semi-transparent black */
  padding: 40px;
  border-radius: 15px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  width: 100%;
  max-width: 400px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.reset-password-form h2 {
  text-align: center;
  color: #e50914; /* Netflix red */
  margin-bottom: 30px;
  font-size: 2rem;
}

/* Form Group Styles */
.form-group {
  margin-bottom: 25px;
}

.form-group label {
  display: block;
  margin-bottom: 10px;
  color: #fff;
  font-size: 1rem;
  font-weight: bold;
}

.form-group input {
  width: 95%;
  padding: 12px;
  font-size: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 6px;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  transition: border-color 0.3s ease;
}

.form-group input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

.form-group input:focus {
  outline: none;
  border-color: #e50914;
}

/* Button Styles */
.reset-button {
  width: 100%;
  padding: 14px;
  font-size: 1rem;
  font-weight: bold;
  background-color: #e50914; /* Netflix red */
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.reset-button:hover {
  background-color: #f40612;
}
</style>