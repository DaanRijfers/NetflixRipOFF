<template>
  <div>
    <button class="logout-button" @click="confirmLogout">Logout</button>
    <div v-if="showConfirm" class="confirm-popup">
      <p>Are you sure you want to logout?</p>
      <button class="confirm-button" @click="logout">Yes</button>
      <button class="cancel-button" @click="cancelLogout">No</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserLogoutComponent', // Rename the component to follow the multi-word naming convention
  data() {
    return {
      showConfirm: false
    };
  },
  methods: {
    confirmLogout() {
      this.showConfirm = true;
    },
    cancelLogout() {
      this.showConfirm = false;
    },
    async logout() {
      try {
        const email = localStorage.getItem('email');
        const token = localStorage.getItem('token');
        await axios.post('http://localhost:8000/api/auth/logout', {}, {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Email': email
          }
        });
        console.log('User logged out');
        localStorage.clear();
        this.$router.push('/login');
      } catch (error) {
        console.error('Failed to logout:', error);
      }
    }
  }
}
</script>

<style scoped>
.logout-button {
  background-color: #e50914;
  color: #fff;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.logout-button:hover {
  background-color: #f40612;
}

.confirm-popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: rgba(0, 0, 0, 0.8);
  padding: 20px;
  border: 1px solid #ccc;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  color: #fff;
  text-align: center;
  border-radius: 10px;
}

.confirm-button, .cancel-button {
  background-color: #e50914;
  color: #fff;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin: 10px;
}

.confirm-button:hover, .cancel-button:hover {
  background-color: #f40612;
}
</style>
