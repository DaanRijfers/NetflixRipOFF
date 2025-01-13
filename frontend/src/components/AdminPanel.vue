<template>
  <div class="bg-gray-900 text-white min-h-screen flex flex-col items-center py-10">
    <h1 class="text-3xl font-bold mb-6">Admin Panel</h1>

    <!-- Users Section -->
    <div class="w-full max-w-5xl mt-6">
      <h2 class="text-xl font-bold mb-4">Users</h2>
      <div v-if="loadingUsers" class="text-white">Loading users...</div>
      <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-gray-800 border border-gray-700">
          <thead>
            <tr>
              <th class="px-4 py-2 border border-gray-700">Name</th>
              <th class="px-4 py-2 border border-gray-700">Email</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-700">
              <td class="px-4 py-2 border border-gray-700">{{ user.name }}</td>
              <td class="px-4 py-2 border border-gray-700">{{ user.email }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Profiles Section -->
    <div class="w-full max-w-5xl mt-6">
      <h2 class="text-xl font-bold mb-4">Profiles</h2>
      <div v-if="loadingProfiles" class="text-white">Loading profiles...</div>
      <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-gray-800 border border-gray-700">
          <thead>
            <tr>
              <th class="px-4 py-2 border border-gray-700">Name</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="profile in profiles" :key="profile.id" class="hover:bg-gray-700">
              <td class="px-4 py-2 border border-gray-700">{{ profile.name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Content Section -->
    <div class="w-full max-w-5xl mt-6">
      <h2 class="text-xl font-bold mb-4">Content</h2>
      <div v-if="loadingContent" class="text-white">Loading content...</div>
      <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-gray-800 border border-gray-700">
          <thead>
            <tr>
              <th class="px-4 py-2 border border-gray-700">Title</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in content" :key="item.id" class="hover:bg-gray-700">
              <td class="px-4 py-2 border border-gray-700">{{ item.title }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Subscriptions Section -->
    <div class="w-full max-w-5xl mt-6">
      <h2 class="text-xl font-bold mb-4">Subscriptions</h2>
      <div v-if="loadingSubscriptions" class="text-white">Loading subscriptions...</div>
      <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-gray-800 border border-gray-700">
          <thead>
            <tr>
              <th class="px-4 py-2 border border-gray-700">Name</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="subscription in subscriptions" :key="subscription.id" class="hover:bg-gray-700">
              <td class="px-4 py-2 border border-gray-700">{{ subscription.name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// State for users
const users = ref([]);
const loadingUsers = ref(true);

// State for profiles
const profiles = ref([]);
const loadingProfiles = ref(true);

// State for content
const content = ref([]);
const loadingContent = ref(true);

// State for subscriptions
const subscriptions = ref([]);
const loadingSubscriptions = ref(true);

// Fetch data on component mount
const fetchUsers = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/user');
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    users.value = data.users;
    loadingUsers.value = false;
  } catch (error) {
    console.error('Error fetching users:', error);
    loadingUsers.value = false;
  }
};

const fetchProfiles = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/profile');
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    profiles.value = data.profiles;
    loadingProfiles.value = false;
  } catch (error) {
    console.error('Error fetching profiles:', error);
    loadingProfiles.value = false;
  }
};

const fetchContent = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/contents');
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    content.value = data.content;
    loadingContent.value = false;
  } catch (error) {
    console.error('Error fetching content:', error);
    loadingContent.value = false;
  }
};

const fetchSubscriptions = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/subscriptions');
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    subscriptions.value = data.subscriptions;
    loadingSubscriptions.value = false;
  } catch (error) {
    console.error('Error fetching subscriptions:', error);
    loadingSubscriptions.value = false;
  }
};

onMounted(() => {
  fetchUsers();
  fetchProfiles();
  fetchContent();
  fetchSubscriptions();
});
</script>

<style scoped>
h1 {
  text-align: center;
  margin-bottom: 20px;
}
h2 {
  margin-top: 20px;
}
.overflow-x-auto {
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 12px;
  text-align: left;
  border: 1px solid #4a5568;
}
th {
  background-color: #2d3748;
  font-weight: bold;
}
tr:hover {
  background-color: #4a5568;
}
</style>