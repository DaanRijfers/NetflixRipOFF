<template>
  <div class="profile-container">
    <div v-if="isLoggedIn">
      <h2>Profiles</h2>
      <div v-if="profiles.length > 0">
        <div v-for="profile in filteredProfiles" :key="profile.id" class="profile-card">
          <h3>{{ profile.name }}</h3>
          <p>Media Preference: {{ profile.media_preference === 'EPISODE' ? 'Series' : 'Movie' }}</p>
          <p>Language: {{ getLanguageName(profile.language_id) }}</p>
          <button @click="selectProfile(profile)">Select Profile</button>
        </div>
      </div>
      <div v-else>
        <p>No profiles found. Create a new profile.</p>
      </div>

      <h2>Create New Profile</h2>
      <form @submit.prevent="createProfile" class="profile-form">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" v-model="newProfile.name" required />
        </div>
        <div class="form-group">
          <label for="media_preference">Media Preference:</label>
          <select id="media_preference" v-model="newProfile.media_preference" required>
            <option value="MOVIE">Movie</option>
            <option value="EPISODE">Series</option>
          </select>
        </div>
        <div class="form-group">
          <label for="language_id">Language:</label>
          <select id="language_id" v-model="newProfile.language_id" required>
            <option v-for="language in languages" :key="language.id" :value="language.id">{{ language.name }}</option>
          </select>
        </div>
        <button type="submit" class="update-button">Create Profile</button>
      </form>

      <div v-if="selectedProfile" class="selected-profile">
        <h2>Selected Profile: {{ selectedProfile.name }}</h2>
        <form @submit.prevent="updateProfile" class="profile-form">
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" v-model="selectedProfile.name" required />
          </div>
          <div class="form-group">
            <label for="favorite_animal">Favorite Animal:</label>
            <input type="text" id="favorite_animal" v-model="selectedProfile.favorite_animal" required />
          </div>
          <div class="form-group">
            <label for="media_preference">Media Preference:</label>
            <select id="media_preference" v-model="selectedProfile.media_preference" required>
              <option value="MOVIE">Movie</option>
              <option value="EPISODE">Series</option>
            </select>
          </div>
          <div class="form-group">
            <label for="language_id">Language:</label>
            <select id="language_id" v-model="selectedProfile.language_id" required>
              <option v-for="language in languages" :key="language.id" :value="language.id">{{ language.name }}</option>
            </select>
          </div>
          <button type="submit" class="update-button">Update Profile</button>
        </form>
      </div>

      <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success">{{ successMessage }}</div>
    </div>
    <div v-else class="error">You must be logged in to view this page.</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      profiles: [],
      selectedProfile: null,
      newProfile: {
        name: '',
        media_preference: 'MOVIE',
        language_id: 1,
      },
      languages: [],
      errorMessage: '',
      successMessage: '',
      isLoggedIn: false,
    };
  },
  async created() {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('Token not provided');
      }
      const profileResponse = await axios.get('http://localhost:8000/api/profile', {
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      });
      this.isLoggedIn = true;
      this.profiles = profileResponse.data.profiles || [];
      if (!profileResponse.data.profiles) {
        throw new Error('No profiles data returned from server');
      }

      const languageResponse = await axios.get('http://localhost:8000/api/languages', {
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      });
      this.languages = languageResponse.data.languages || languageResponse.data;
    } catch (error) {
      console.error('Error fetching data:', error.response ? error.response.data : error.message);
      this.errorMessage = `Failed to load data: ${error.response ? error.response.data.message : error.message}`;
    }
  },
  computed: {
    filteredProfiles() {
      return this.profiles.filter(profile => profile);
    },
  },
  methods: {
    selectProfile(profile) {
      this.selectedProfile = profile;
    },
    async createProfile() {
      try {
        const response = await axios.post(
          'http://localhost:8000/api/profile',
          {
            name: this.newProfile.name,
            media_preference: this.newProfile.media_preference,
            language_id: this.newProfile.language_id,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
          }
        );

        this.profiles.push(response.data.profile);
        this.successMessage = 'Profile created successfully!';
        this.errorMessage = '';
        this.newProfile = {
          name: '',
          media_preference: 'MOVIE',
          language_id: 1,
        };
      } catch (error) {
        this.errorMessage = `Failed to create profile: ${error.response && error.response.data && error.response.data.message ? error.response.data.message : error.message}`;
        this.successMessage = '';
      }
    },
    async updateProfile() {
      const id = localStorage.getItem('userId');

      try {
        const response = await axios.put(
          `http://localhost:8000/api/profile/${id}`,
          {
            name: this.selectedProfile.name,
            media_preference: this.selectedProfile.media_preference,
            language_id: this.selectedProfile.language_id,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
          }
        );

        this.successMessage = 'Profile updated successfully!';
        this.errorMessage = '';
        console.log('Update response:', response.data);
      } catch (error) {
        this.errorMessage = `Failed to update profile: ${error.response ? error.response.data.message : error.message}`;
        this.successMessage = '';
      }
    },
    getLanguageName(languageId) {
      const language = this.languages.find(lang => lang.id === languageId);
      return language ? language.name : 'Unknown';
    },
  },
};
</script>

<style scoped>
.profile-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 20px;
}

.profile-form {
  background: rgba(0, 0, 0, 0.8);
  padding: 30px;
  border-radius: 15px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  width: 100%;
  max-width: 400px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.profile-form h2 {
  text-align: center;
  color: #e50914;
  margin-bottom: 20px;
  font-size: 2rem;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #fff;
  font-size: 1rem;
}

.form-group input,
.form-group select {
  width: 95%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 4px;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #e50914;
}

.update-button {
  width: 100%;
  padding: 12px;
  font-size: 1rem;
  font-weight: bold;
  background-color: #e50914;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.update-button:hover {
  background-color: #f40612;
}

.error,
.success {
  margin-top: 15px;
  padding: 10px;
  border-radius: 4px;
  text-align: center;
  font-size: 0.9rem;
}

.error {
  background-color: rgba(229, 9, 20, 0.2);
  border: 1px solid #e50914;
  color: #e50914;
}

.success {
  background-color: rgba(0, 128, 0, 0.2);
  border: 1px solid #00ff00;
  color: #00ff00;
}

.profile-card {
  background: rgba(255, 255, 255, 0.1);
  padding: 20px;
  margin-bottom: 10px;
  border-radius: 4px;
}

.selected-profile {
  margin-top: 20px;
}
</style>
