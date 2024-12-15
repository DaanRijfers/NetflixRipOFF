<script setup>
import MoviesAndSeries from './components/MoviesAndSeries.vue';

// Placeholder data for Movies and Series
const series = {
  data: [
    { series_title: "Breaking Bad", season_number: 1, episode_number: 1, episode_title: "Pilot" },
    { series_title: "Breaking Bad", season_number: 1, episode_number: 2, episode_title: "Cat's in the Bag" },
    { series_title: "Breaking Bad", season_number: 1, episode_number: 3, episode_title: "And the Bag's in the River" }
  ]
};

const movies = {
  data: [
    { movie_id: 1, movie_title: "Inception", qualities: "SD, HD, UHD" },
    { movie_id: 2, movie_title: "The Dark Knight", qualities: "HD, UHD" },
    { movie_id: 3, movie_title: "Interstellar", qualities: "HD, UHD, 4K" }
  ]
};
</script>

<template>
  <MoviesAndSeries :series="series" :movies="movies" />
</template>
