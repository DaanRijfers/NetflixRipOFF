<template>
  <div>
    <h2>Reset Password</h2>
    <form @submit.prevent="resetPassword">
      <div>
        <label>Email:</label>
        <input type="email" v-model="email" required>
      </div>
      <button type="submit">Send Reset Link</button>
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
        const response = await axios.post('http://localhost:8000/api/reset-password', {
          email: this.email
        });
        alert(response.data.message);
      } catch (error) {
        alert('Failed to send reset link');
      }
    }
  }
};
</script>