<script setup>
import { CloudIcon } from '@heroicons/vue/24/outline'
</script>
<template>
    <div class="mt-6 grid grid-cols-1 gap-3">
        <div>
            <a href="http://localhost:8000/nextcloud/auth/redirect"
                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="inline-block mr-1">Nextcloud</span>
                <CloudIcon class="w-5 h-5" />
            </a>
        </div>
    </div>
</template>