<template>
	<Head title="Game" />
    <AppGameHeader />
	<div v-if="!newGame" class="flex flex-col justify-center items-center h-screen">
		<Button @click="startNewGame()">Nueva Partida</Button>
		<p class="pt-4">Mejor Puntuación: {{ user.best_score }}</p>
	</div>
	<div v-else class="flex flex-col justify-center items-center h-screen">
		<p class="pt-4">Jugando...</p>
	</div>

</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import AppGameHeader from '@/components/AppGameHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import { defineProps } from 'vue';
import  { ref } from 'vue';

defineProps({
	user: {
		type: Object,
	},
	flashcards: {
		type: Object,
	},
})

const newGame = ref(false);
const startNewGame = () => {
	newGame.value = true;
}
</script>
