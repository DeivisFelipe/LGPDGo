<script setup>
import { computed } from 'vue';

const props = defineProps({
    score: {
        type: Number,
        required: true,
        validator: (value) => value >= 0 && value <= 100
    },
    size: {
        type: Number,
        default: 200
    }
});

const radius = computed(() => props.size / 2 - 10);
const circumference = computed(() => 2 * Math.PI * radius.value);
const strokeDashoffset = computed(() => {
    return circumference.value - (props.score / 100) * circumference.value;
});

const color = computed(() => {
    if (props.score >= 85) return '#10b981'; // green-500
    if (props.score >= 70) return '#3b82f6'; // blue-500
    if (props.score >= 50) return '#f59e0b'; // amber-500
    if (props.score >= 30) return '#f97316'; // orange-500
    return '#ef4444'; // red-500
});

const label = computed(() => {
    if (props.score >= 85) return 'Excelente';
    if (props.score >= 70) return 'Bom';
    if (props.score >= 50) return 'Regular';
    if (props.score >= 30) return 'Baixo';
    return 'Crítico';
});

const icon = computed(() => {
    if (props.score >= 85) return '🏆';
    if (props.score >= 70) return '✅';
    if (props.score >= 50) return '⚠️';
    if (props.score >= 30) return '⚡';
    return '🚨';
});
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="relative" :style="{ width: size + 'px', height: size + 'px' }">
            <!-- Background Circle -->
            <svg :width="size" :height="size" class="transform -rotate-90">
                <circle
                    :cx="size / 2"
                    :cy="size / 2"
                    :r="radius"
                    stroke="#e2e8f0"
                    stroke-width="12"
                    fill="none"
                />
                <!-- Progress Circle -->
                <circle
                    :cx="size / 2"
                    :cy="size / 2"
                    :r="radius"
                    :stroke="color"
                    stroke-width="12"
                    fill="none"
                    stroke-linecap="round"
                    :stroke-dasharray="circumference"
                    :stroke-dashoffset="strokeDashoffset"
                    class="transition-all duration-1000 ease-out"
                />
            </svg>

            <!-- Center Content -->
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <div class="text-4xl mb-1">{{ icon }}</div>
                <div class="text-4xl font-black text-slate-900">{{ score }}</div>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-wider">/ 100</div>
            </div>
        </div>

        <!-- Label -->
        <div class="mt-4 text-center">
            <div 
                class="inline-block px-4 py-2 rounded-full font-black text-sm uppercase tracking-wider"
                :class="{
                    'bg-green-100 text-green-700': score >= 85,
                    'bg-blue-100 text-blue-700': score >= 70 && score < 85,
                    'bg-amber-100 text-amber-700': score >= 50 && score < 70,
                    'bg-orange-100 text-orange-700': score >= 30 && score < 50,
                    'bg-red-100 text-red-700': score < 30
                }"
            >
                {{ label }}
            </div>
        </div>
    </div>
</template>
