<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <div class="min-h-screen bg-gray-900 text-white flex items-center justify-center">
        <Head :title="title" />

        <div class="w-full max-w-md mx-auto px-4">
            <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <img src="/assets/images/lgpdgo.png" alt="LGPDGo Logo" class="w-32 h-auto mx-auto mb-4" />
                    <p class="text-gray-400 text-sm">
                        Mapear é proteger
                    </p>
                </div>

                <slot />
            </div>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-500">
                    © {{ new Date().getFullYear() }} LGPDGo. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </div>
</template>
