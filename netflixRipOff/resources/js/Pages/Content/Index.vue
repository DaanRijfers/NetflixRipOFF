<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps(['series', 'movies']);
</script>

<template>
    <Head title="Content" />
    <div class="bg-red-900 text-white">
        <div class="min-h-screen flex flex-col items-center py-10">
            <h1 class="text-3xl font-bold text-white mb-6">
                Movies & Series
            </h1>

            <div class="w-full max-w-5xl mt-6">
                <!-- Series Section -->
                <h2 class="text-xl font-bold mb-4">Series</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="serie in series.data"
                        :key="serie.series_title"
                        class="p-4 border border-red-700 rounded shadow bg-red-800 hover:bg-red-700"
                    >
                        <h3 class="font-bold">{{ serie.series_title }}</h3>
                        <p>Total Seasons: {{ serie.total_seasons }}</p>
                        <p>Total Episodes: {{ serie.total_episodes }}</p>
                    </div>
                </div>

                <!-- Series Pagination -->
                <div class="mt-4 flex justify-center space-x-2">
                    <Link
                        v-for="link in series.links"
                        :key="link.url"
                        :href="link.url"
                        v-html="link.label"
                        :class="['px-2 py-1', link.active ? 'font-bold underline text-red-400' : 'text-white']"
                        class="hover:underline"
                    ></Link>
                </div>

                <!-- Movies Section -->
                <h2 class="text-xl font-bold mb-4 mt-8">Movies</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="movie in movies.data"
                        :key="movie.movie_id"
                        class="p-4 border border-red-700 rounded shadow bg-red-800 hover:bg-red-700"
                    >
                        <h3 class="font-bold">{{ movie.movie_title }}</h3>
                        <p>Qualities: {{ movie.qualities }}</p>
                    </div>
                </div>

                <!-- Movies Pagination -->
                <div class="mt-4 flex justify-center space-x-2">
                    <Link
                        v-for="link in movies.links"
                        :key="link.url"
                        :href="link.url"
                        v-html="link.label"
                        :class="['px-2 py-1', link.active ? 'font-bold underline text-red-400' : 'text-white']"
                        class="hover:underline"
                    ></Link>
                </div>
            </div>
        </div>
    </div>
</template>
