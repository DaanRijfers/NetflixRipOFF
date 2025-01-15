<template>
  <div class="profile-container">
    <form v-if="isLoggedIn" @submit.prevent="updateProfile" class="profile-form">
      <h2>Profile</h2>
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="name" required />
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required />
      </div>
      <div class="form-group">
        <label for="favoriteAnimal">Favorite Animal:</label>
        <input type="text" id="favoriteAnimal" v-model="favoriteAnimal" required />
      </div>
      <button type="submit" class="update-button">Update Profile</button>
      <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success">{{ successMessage }}</div>
    </form>
    <div v-else class="error">You must be logged in to view this page.</div>
    <div v-if="isLoggedIn" class="favorite-content">
      <h2>Favorite Content</h2>
      <ul>
        <li v-for="item in favoriteContent" :key="item.id">{{ item.title }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      name: '',
      email: '',
      favoriteAnimal: '',
      errorMessage: '',
      successMessage: '',
      isLoggedIn: false,
      favoriteContent: []
    };
  },
  async created() {
    try {
      const response = await axios.get('http://localhost:8000/api/user/profile');
      this.isLoggedIn = true;
      this.name = response.data.name;
      this.email = response.data.email;
      this.favoriteAnimal = response.data.favoriteAnimal;
    } catch (error) {
      this.errorMessage = `Failed to load profile: ${error.response ? error.response.data.message : error.message}`;
    }
    try {
      const contentResponse = await axios.get('http://localhost:8000/api/user/favorite-content');
      this.favoriteContent = contentResponse.data;
    } catch (error) {
      this.errorMessage = `Failed to load favorite content: ${error.response ? error.response.data.message : error.message}`;
    }
  },
  methods: {
    async updateProfile() {
      try {
        const response = await axios.put('http://localhost:8000/api/user/profile', {
          name: this.name,
          email: this.email,
          favoriteAnimal: this.favoriteAnimal
        });
        this.successMessage = response.data.message;
      } catch (error) {
        this.errorMessage = `Failed to update profile: ${error.response ? error.response.data.message : error.message}`;
      }
    }
  }
};
</script>

<style scoped>
/* General Styles */
.profile-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 20px;
}

.profile-form {
  background: rgba(0, 0, 0, 0.8); /* Semi-transparent black */
  padding: 30px;
  border-radius: 15px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  width: 100%;
  max-width: 400px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.profile-form h2 {
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
.update-button {
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

.update-button:hover {
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

.favorite-content {
  margin-top: 20px;
}

.favorite-content h2 {
  color: #e50914;
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.favorite-content ul {
  list-style: none;
  padding: 0;
}

.favorite-content li {
  background: rgba(255, 255, 255, 0.1);
  padding: 10px;
  margin-bottom: 5px;
  border-radius: 4px;
}
</style>