<template>
  <div id="app">
    <nav>
      <router-link to="/login">Login</router-link>
      <router-link to="/register">Register</router-link>
      <router-link to="/reset-password">Reset Password</router-link>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'App'
};
</script>

<style>
nav {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}
nav a {
  text-decoration: none;
  color: blue;
}
nav a.router-link-exact-active {
  font-weight: bold;
  color: red;
}
</style>