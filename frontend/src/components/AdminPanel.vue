<template>
    <div class="bg-gray-900 text-white min-h-screen flex flex-col items-center py-10">
      <h1 class="text-3xl font-bold mb-6">Admin Panel</h1>
  
      <!-- Users Section -->
      <div class="w-full max-w-5xl mt-6">
        <h2 class="text-xl font-bold mb-4">Users</h2>
        <div v-if="loadingUsers" class="text-white">Loading users...</div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="user in users" :key="user.id"
            class="p-4 border border-gray-700 rounded shadow bg-gray-800 hover:bg-gray-700">
            <h2 class="font-bold">{{ user.name }}</h2>
            <p>{{ user.email }}</p>
          </div>
        </div>
      </div>
  
      <!-- Profiles Section -->
      <div class="w-full max-w-5xl mt-6">
        <h2 class="text-xl font-bold mb-4">Profiles</h2>
        <div v-if="loadingProfiles" class="text-white">Loading profiles...</div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="profile in profiles" :key="profile.id"
            class="p-4 border border-gray-700 rounded shadow bg-gray-800 hover:bg-gray-700">
            <h2 class="font-bold">{{ profile.name }}</h2>
          </div>
        </div>
      </div>
  
      <!-- Content Section -->
      <div class="w-full max-w-5xl mt-6">
        <h2 class="text-xl font-bold mb-4">Content</h2>
        <div v-if="loadingContent" class="text-white">Loading content...</div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="item in content" :key="item.id"
            class="p-4 border border-gray-700 rounded shadow bg-gray-800 hover:bg-gray-700">
            <h2 class="font-bold">{{ item.title }}</h2>
          </div>
        </div>
      </div>
  
      <!-- Subscriptions Section -->
      <div class="w-full max-w-5xl mt-6">
        <h2 class="text-xl font-bold mb-4">Subscriptions</h2>
        <div v-if="loadingSubscriptions" class="text-white">Loading subscriptions...</div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="subscription in subscriptions" :key="subscription.id"
            class="p-4 border border-gray-700 rounded shadow bg-gray-800 hover:bg-gray-700">
            <h2 class="font-bold">{{ subscription.name }}</h2>
          </div>
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
  ul {
    list-style-type: none;
    padding: 0;
  }
  li {
    margin: 5px 0;
  }
  </style>