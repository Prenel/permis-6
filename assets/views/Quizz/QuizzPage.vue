<template>
    <div>
        <h2 class="mb-8">Examen de Production</h2>
        
        <div v-if="lastQuestion">
            
            <p>{{lastQuestion.text}}</p>

            <div class="mb-10">
                <div v-if="lastQuestion.type == 'true_false'">
                    <v-radio-group v-model="answerTrueFalse">
                        <v-radio label="Vrai" :value="true"></v-radio>
                        <v-radio label="faux" :value="false"></v-radio>
                    </v-radio-group>
                </div>
                
                <div v-else-if="lastQuestion.type == 'multiple'" > 
                    <div>
                        <v-checkbox 
                            v-for="answer in lastQuestion.answers" :key="answer.id" 
                            v-model="selectedAnswers"
                            :label="answer.text"
                            :value="answer.id"
                            ></v-checkbox>
                    </div>
                </div>
            </div>

            <div class="pb-10 d-flex flex-row-reverse">
                <v-btn @click="next" color="green">Suivante</v-btn>
            </div>
        </div>

        <div v-if="quizzStore.questions.length == 0" class="pb-8">
            <p>Vous avez fait {{mistakes}} erreurs sur 10 questions.</p>
            <p>Votre examen pour le permis de production a été enregistré.</p>
        </div>


    </div>
</template>
<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import axios from "axios";
import { useQuizzStore } from "../../stores/quizzStore";


const quizzStore = useQuizzStore();
const lastQuestion = computed(() => {
    return quizzStore.questions.length > 0 ? quizzStore.questions[quizzStore.questions.length - 1] : null; 
});
const answerTrueFalse = ref(null);
const selectedAnswers = ref([]);
const mistakes = ref(0);
const multipleLastQuestion = ref([]);
const quizzFinish = ref(false);

onMounted(() => {
    quizzStore.fetchQuestions();
})

const next = () => {
    if (lastQuestion.value.type == 'true_false') {
        
        if (lastQuestion.value.answers[0].isTrue !== answerTrueFalse.value){
            mistakes.value++;
        } 
    } else if (lastQuestion.value.type == 'multiple'){
        const correctAnswersIds = lastQuestion.value.answers.filter(answer => answer.isTrue).map(answer => answer.id);
        const isCorrect = selectedAnswers.value.length === correctAnswersIds.length && 
        selectedAnswers.value.every(id => correctAnswersIds.includes(id));

        if (!isCorrect){
            mistakes.value++;
        }

    } 
    quizzStore.removeLastQuestion();
    console.log(quizzStore.questions.length)
}

</script>
