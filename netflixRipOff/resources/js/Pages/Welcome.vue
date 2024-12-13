<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 text-black dark:bg-gray-900 dark:text-white">
        <h1 class="text-4xl font-bold mb-4">Welcome to Laravel + Vue.js</h1>
        <p class="mb-6">Laravel Version: {{ laravelVersion }} | PHP Version: {{ phpVersion }}</p>

        <div class="space-x-4">
            <Link
                href="/content"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition"
            >
                Go to Content
            </Link>
            <template v-if="canLogin">
                <Link
                    href="/login"
                    class="px-6 py-3 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition"
                >
                    Login
                </Link>
            </template>
            <template v-if="canRegister">
                <Link
                    href="/register"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition"
                >
                    Register
                </Link>
            </template>
        </div>
    </div>
</template>
