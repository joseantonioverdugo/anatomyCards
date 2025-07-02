<template>

    <Head title="Game" />
    <AppGameHeader />
    <div v-if="newGame" class="flex flex-col justify-center items-center h-screen">
        <div v-if="!gameOver" class="flex flex-col items-center">
            <img v-if="selectedFlashcard" :src="selectedFlashcard.url" alt="Flashcard Image"
                class="w-96 h-96 object-contain mb-4 rounded-lg shadow-lg" />
            <p v-if="selectedFlashcard" class="text-lg font-semibold mb-4">¿Que opción es la número {{
                correctOption.option_number }}?</p>
            <div class="mt-4 grid grid-cols-2 gap-6">
                <button @click="handleAnswer(option)" v-for="(option) in selectedOptions" :key="option.id"
                    :disabled="isCorrect !== null" :class="[
                        'flex justify-center items-center border border-gray-300 p-2 rounded-lg cursor-pointer',
                        selectedAnswer === option && isCorrect === null ? 'bg-amber-300' : '',
                        isCorrect !== null && selectedAnswer === option && isCorrect === false ? 'bg-red-300' : '',
                        isCorrect !== null && selectedAnswer === option && isCorrect === true ? 'bg-green-300' : '',
                        isCorrect !== null && isCorrect === false && correctOption === option ? 'bg-green-300' : '',
                        isCorrect !== null ? 'disabled:opacity-65' : '']">
                    {{ option.title }}
                </button>
            </div>
        </div>
        <div v-else class="text-center mb-6">
            <h2 class="text-2xl font-bold mb-2">¡Juego Terminado!</h2>
            <p class="text-lg">Tu puntuación final: {{ score }}</p>
            <Button @click="startNewGame()" class="mt-4">Nueva Partida</Button>
        </div>
    </div>
    <div v-else class="flex flex-col justify-center items-center h-screen">
        <Button @click="startNewGame()">Nueva Partida</Button>
        <p class="pt-4">Mejor Puntuación: {{ user.best_score }}</p>
    </div>

</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import AppGameHeader from '@/components/AppGameHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import axios from 'axios';
import { defineProps } from 'vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    user: {
        type: Object,
    },
    flashcards: {
        type: Object,
    },
})

const newGame = ref(false);
const score = ref(0);
const gameFlashcards = ref([]);
const selectedFlashcard = ref(null);
const selectedOptions = ref([]);
const correctOption = ref(null);
const selectedAnswer = ref(null);
const isCorrect = ref(null);
const answerRevealed = ref(false);
const gameOver = ref(false);
const bestScore = ref(props.user.best_score);

const startNewGame = () => {
    newGame.value = true;
    score.value = 0;
    gameOver.value = false;
    if (props.flashcards.data && props.flashcards.data.length > 0) {
        gameFlashcards.value = orderFlashcards(props.flashcards.data);
        selectFlashcard();
        selectOption();
    }
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
    if (gameFlashcards.value.length > 0) {
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

    selectedAnswer.value = null;
    isCorrect.value = null;
    answerRevealed.value = false;
}

const handleAnswer = (option) => {
    if (!answerRevealed.value) {
        selectedAnswer.value = option;
        answerRevealed.value = true;
    } else if (selectedAnswer.value.id === option.id) {
        isCorrect.value = selectedAnswer.value.id === correctOption.value.id;

        if (isCorrect.value) {
            setTimeout(() => {
                selectFlashcard();
                selectOption();
            }, 1500);
            score.value += 1;
        } else {
            setTimeout(() => {
                gameOver.value = true;
            }, 1500);
            updateBestScore();
        }

        if (gameFlashcards.value.length === 0) {
            setTimeout(() => {
                gameOver.value = true;
            }, 1500);
            updateBestScore();
        }
    } else {
        selectedAnswer.value = option;
    }
}

const updateBestScore = async () => {
    if (score.value > props.user.best_score) {
        try {
            await axios.patch(route('best-score', props.user.id), {
                best_score: score.value
            });
            bestScore.value = score.value;
        } catch (error) {
            console.error('Error updating best score:', error);
        }
    }
}

onMounted(() => {
    if (props.flashcards.data && props.flashcards.data.length > 0) {
        gameFlashcards.value = orderFlashcards(props.flashcards.data);
        selectFlashcard();
        selectOption();
    }
});
</script>
