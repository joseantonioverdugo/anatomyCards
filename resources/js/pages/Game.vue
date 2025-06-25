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
import  { ref, onMounted } from 'vue';

const props = defineProps({
	user: {
		type: Object,
	},
	flashcards: {
		type: Object,
	},
})

const newGame = ref(false);
const gameFlashcards = ref([]);
const selectedFlashcard = ref(null);
const selectedOptions = ref([]);
const correctOption = ref(null);

const startNewGame = () => {
	newGame.value = true;
}

const orderFlashcards = (flashcards) => {
    const shuffled = [...flashcards];
    
    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }
    return shuffled;
}

const selectFlashcard = () => {
	if(gameFlashcards.value.length > 0) {
		selectedFlashcard.value = gameFlashcards.value.pop();
	} 
}

const selectOption = () => {
	const shuffledOptions = [...selectedFlashcard.value.options];
	for (let i = shuffledOptions.length - 1; i > 0; i--) {
		const j = Math.floor(Math.random() * (i + 1));
		[shuffledOptions[i], shuffledOptions[j]] = [shuffledOptions[j], shuffledOptions[i]];
	}
	selectedOptions.value = shuffledOptions.splice(0, 4);
	correctOption.value = selectedOptions.value[Math.floor(Math.random() * selectedOptions.value.length)];
}

onMounted(() => {
	if(props.flashcards.data && props.flashcards.data.length > 0) {
		gameFlashcards.value = orderFlashcards(props.flashcards.data);
		selectFlashcard();
		selectOption();
	}
});
</script>
