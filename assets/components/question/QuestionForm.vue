<template>
    <v-form>
        <div class="w-100 d-flex flex-column align-center">
            
            <div>
                <v-textarea 
                    min-width="1000px" 
                    max-width="1000px"
                    label="Question"
                    density="compact"
                    v-model="store.question.text"
                    :rules="[rules.required]"
                ></v-textarea>


                <div class="d-flex justify-space-between container-category">
                    
                    <v-select
                        v-model="store.question.category"
                        :items="categoryStore.categories"
                        item-value="id"
                        item-title="name"
                        label="Catégorie"
                        placeholder="Selectionnez la catégorie"
                        return-object
                        min-width="450px"
                        max-width="450px"
                        :rules="[rules.required]"
                    ></v-select>
                
                    
                    <v-select
                        v-if="store.question.category !== null"
                        v-model="store.question.subCategory"
                        :items="store.question.category.subCategories"
                        item-value="id"
                        item-title="name"
                        label="Sous-catégorie"
                        placeholder="Selectionnez une sous-catégorie"
                        min-width="450px"
                        max-width="450px"
                        return-object
                        :rules="[rules.required]"
                    ></v-select>
                    

                </div>

            </div>
            
            <h3 class="mb-4 mt-2">Réponses</h3>
            
            <div class="mb-8 container-choice-button d-flex align-center justify-space-between">
                <v-btn
                    @click="toggleMultiple" 
                    :active="btnMultiple"
                    active-color="blue"
                >Multiple</v-btn>

                <v-switch 
                    density="compact" 
                    style="height:40px" 
                    v-model="switchBtn"
                    base-color="blue"
                    color="success"
                ></v-switch>
                
                <v-btn 
                    @click="toggleTrueFalse"
                    active-color="green"
                    :active="btnTrueFalse"
                >Vrai/Faux</v-btn>
            </div>

            <div v-if="switchBtn == true">
                <p>La question est elle vrai ou fausse ?</p>
                <v-radio-group v-model="store.question.answers[0].isTrue">
                    <v-radio label="Vrai" :value="true"></v-radio>
                    <v-radio label="faux" :value="false"></v-radio>
                </v-radio-group>
            </div>

            <div v-else-if="switchBtn == false">
                <v-row 
                    class="d-flex answer align-end justify-space-between" 
                    v-for="answer in store.question.answers" :key="answer.id">
                    <div>
                        <v-textarea
                            :rules="[rules.required]"
                            min-width="500px" 
                            max-width="500px" 
                            label="Réponse"
                            density="compact"
                            v-model="answer.text" 
                        ></v-textarea>
                    </div>
                   <div>
                        <v-checkbox label="Réponse valide" v-model="answer.isTrue"></v-checkbox>
                   </div>
                    <div class="pb-6">
                        <v-btn 
                            color="blue" 
                            class="mx-auto"
                            @click="addAnswerField"
                        ><v-icon>mdi-plus</v-icon></v-btn>
                    </div>
                </v-row>
            </div>
            
        </div>
    </v-form>
</template>
<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { useQuestionStore } from "../../stores/questionStore";
import { useCategoryStore } from "../../stores/categoryStore";
import { required } from '../../rules'

const store = useQuestionStore();
const categoryStore = useCategoryStore();
const btnMultiple = ref(false);
const btnTrueFalse = ref(false);
const switchBtn = ref(true);
const answerIsTrue = ref(null);
const boutonPlus = ref([{ value: ''}]);
const rules = ref({required});

// --------------------------------------------------------- Actions

// changer l'etat du switch via multiple bouton
const toggleMultiple = () => {  
    !btnMultiple.value ? btnMultiple.value = true : null;
    switchBtn.value = false;
} 

// changer l'etat du switch via trueFalse bouton
const toggleTrueFalse = () => {  
    !btnTrueFalse.value ? btnTrueFalse.value = true : null;
    switchBtn.value = true;
}

// Ajouter un champ Texte pour les réponses
const addAnswerField = () => {
    const isEmptyValue = store.question.answers.some((answer) => answer.text === null ? true : false);
    if (isEmptyValue !== true){
        store.countAnswers++;
        store.question.answers.push(
            { id: null, text: null, isTrue: false, nameForm: `answer-${store.countAnswers}` }
        );
    }
};

// determiner quel bouton doit etre actif
onMounted(() => {
    categoryStore.fetchCategoriesField();
    (store.question.type == 'true_false') ? btnTrueFalse.value = true : btnMultiple.value = true; 
    if (store.question.type == 'true_false'){
        btnTrueFalse.value = true;
    } else{
        btnMultiple.value = true;
        switchBtn.value = false;
    }  
});

// Observer le switch pour activer tel ou tel bouton
watch(switchBtn, () => {
    switchBtn.value ? btnTrueFalse.value = true : btnMultiple.value = true;
    !switchBtn.value ? btnTrueFalse.value = false : btnMultiple.value = false;
    switchBtn.value ? store.question.type = 'true_false' : store.question.type = 'multiple';
});

</script>
<style scoped>
.textarea {
    max-height: 50px;
}
.container-choice-button{
    min-width: 350px;
}
.answer{
    min-width: 1000px;
}
.container-category{
    width: 1000px;
} 

</style>