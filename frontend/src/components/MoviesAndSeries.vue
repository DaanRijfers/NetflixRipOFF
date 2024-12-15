<script setup>
import { ref, onMounted } from 'vue';

// State for series and movies
const series = ref([]);
const movies = ref([]);
const loadingSeries = ref(true);
const loadingMovies = ref(true);

// Fetch data on component mount
const fetchData = async () => {
    try {
        // Fetch the API data
        const response = await fetch('http://localhost:8000/api/content');
        const data = await response.json();

        // Assign series and movies data
        series.value = data.series.data;
        movies.value = data.movies.data;

        loadingSeries.value = false;
        loadingMovies.value = false;
    } catch (error) {
        console.error('Error fetching data:', error);
        loadingSeries.value = false;
        loadingMovies.value = false;
    }
};

onMounted(fetchData);
</script>

<template>
    <div class="bg-red-900 text-white min-h-screen flex flex-col items-center py-10">
        <h1 class="text-3xl font-bold mb-6">Movies & Series</h1>

        <!-- Series Section -->
        <div class="w-full max-w-5xl mt-6">
            <h2 class="text-xl font-bold mb-4">Series</h2>
            <div v-if="loadingSeries" class="text-white">Loading series...</div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="serie in series" :key="serie.series_title"
                    class="p-4 border border-red-700 rounded shadow bg-red-800 hover:bg-red-700">
                    <h3 class="font-bold">{{ serie.series_title }}</h3>
                    <p>Total Seasons: {{ serie.total_seasons }}</p>
                    <p>Total Episodes: {{ serie.total_episodes }}</p>
                </div>
            </div>

            <!-- Movies Section -->
            <h2 class="text-xl font-bold mb-4 mt-8">Movies</h2>
            <div v-if="loadingMovies" class="text-white">Loading movies...</div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="movie in movies" :key="movie.movie_id"
                    class="p-4 border border-red-700 rounded shadow bg-red-800 hover:bg-red-700">
                    <h3 class="font-bold">{{ movie.movie_title }}</h3>
                    <p>Qualities: {{ movie.qualities }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
