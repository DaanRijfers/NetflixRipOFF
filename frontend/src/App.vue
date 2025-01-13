<template>
  <div id="app">
    <!-- Background with Blur -->
    <div class="background"></div>

    <!-- Floating Panel -->
    <div class="floating-panel">
      <!-- Title -->
      <h1 class="title">Netflix RippOFF</h1>

      <!-- Navigation Bar -->
      <nav>
        <router-link to="/login">Login</router-link>
        <router-link to="/register">Register</router-link>
        <router-link to="/reset-password">Reset Password</router-link>
        <router-link to="/admin-panel">Admin Panel</router-link>
      </nav>

      <!-- Content -->
      <div class="content">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App'
};
</script>

<style>
/* General Styles */
body, html {
  margin: 0;
  padding: 0;
  min-height: 100vh;
  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
  color: #fff;
}

#app {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  min-height: 100vh;
  width: 100%;
}

/* Background with Blur */
.background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url('./assets/background.jpg');
  background-size: cover;
  background-position: center;
  filter: blur(10px);
  z-index: -1;
}

/* Floating Panel */
.floating-panel {
  display: flex;
  width: 90%;
  max-width: 1200px;
  background: rgba(0, 0, 0, 0.7);
  border-radius: 15px;
  -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
  padding: 20px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  flex-direction: column;
  justify-content: center;
}

/* Title */
.title {
  text-align: center;
  color: #e50914; /* Netflix red */
  margin-bottom: 20px;
  font-size: 2.5rem;
}

/* Navigation Styles */
nav {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-bottom: 30px;
  padding: 10px 20px;
  background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

nav a {
  text-decoration: none;
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  padding: 10px 15px;
  border-radius: 4px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

nav a:hover {
  background-color: rgba(229, 9, 20, 0.8); /* Semi-transparent red */
  color: #fff;
}

nav a.router-link-exact-active {
  background-color: #e50914; /* Netflix red */
  color: #fff;
}

/* Content Styles */
.content {
  padding: 20px;
  background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Netflix-inspired Button Styles */
button {
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

button:hover {
  background-color: #f40612;
}

/* Netflix-inspired Input Styles */
input {
  background-color: rgba(255, 255, 255, 0.1);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 10px;
  font-size: 16px;
  border-radius: 4px;
  width: 80%;
  margin-bottom: 15px;
  transition: border-color 0.3s ease;
}

input:focus {
  outline: none;
  border-color: #e50914;
}

/* Netflix-inspired Card Styles */
.card {
  background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
  padding: 20px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 20px;
}

.card h2 {
  margin-top: 0;
  color: #e50914;
}

.card p {
  color: #fff;
}

/* Netflix-inspired Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

table th, table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

table th {
  background-color: rgba(229, 9, 20, 0.8); /* Semi-transparent red */
  color: #fff;
}

table tr:hover {
  background-color: rgba(255, 255, 255, 0.05);
}
</style>