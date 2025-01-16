<template>
  <div class="register-container">
    <form @submit.prevent="register" class="register-form">
      <h2>Register</h2>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required placeholder="Enter your email" />
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required placeholder="Enter your password" />
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" v-model="confirmPassword" required placeholder="Confirm your password" />
      </div>
      <button type="submit" class="register-button">Register</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserRegister',
  data() {
    return {
      email: '',
      password: '',
      confirmPassword: '',
    };
  },
  methods: {
    async register() {
      if (this.password !== this.confirmPassword) {
        alert('Passwords do not match.');
        return;
      }
      try {
        const response = await axios.post('http://localhost:8000/api/auth/register', {
          email: this.email,
          password: this.password,
          confirmPassword: this.confirmPassword,
        });
        alert(response.data.message);
        this.$router.push('/login'); 
      } catch (error) {
        alert(`Registration failed: ${error.response ? error.response.data.message : error.message}`);
      }
    }
  }
};
</script>

<style scoped>
/* General Styles */
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 20px;
}

.register-form {
  background: rgba(0, 0, 0, 0.9); /* Darker semi-transparent black */
  padding: 40px;
  border-radius: 15px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  width: 100%;
  max-width: 400px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.register-form h2 {
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

/* CAPTCHA Styles */
.captcha-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.captcha-text {
  font-size: 1.2rem;
  font-weight: bold;
  color: #e50914;
  user-select: none;
}

/* Button Styles */
.register-button {
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

.register-button:hover {
  background-color: #f40612;
}
</style>