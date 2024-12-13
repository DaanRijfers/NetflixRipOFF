<script setup>
import { Head } from '@inertiajs/vue3';
defineProps(['content']);
</script>

<template>
    <Head title="Content Details" />
    <div class="bg-gray-50 text-black dark:bg-black">
        <div class="min-h-screen flex flex-col items-center py-10">
            <h1 class="text-2xl font-bold text-black dark:text-white">
                Content Details
            </h1>

            <div class="mt-6 w-full max-w-3xl bg-white shadow rounded-lg p-6 dark:bg-gray-800">
                <h2 class="text-xl font-semibold">{{ content.title }}</h2>
                <p class="mt-4">{{ content.description }}</p>
                <div class="mt-4">
                    <a
                        href="/content"
                        class="text-blue-600 hover:underline"
                    >
                        Back to Content
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
