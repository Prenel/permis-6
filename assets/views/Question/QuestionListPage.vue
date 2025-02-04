<template>
    <div class="question-page">
        <div class="question-table" >
            
            <h1>{{title}}</h1>
            
            <v-btn class="mt-5" color="blue" @click="toggleDialog(null, 'add')">Ajouter <v-icon>mdi-plus</v-icon></v-btn>
            <Table :header="tableHeader">
                <template #thead>
                    <tr>
                        <th v-for="thead in tableHeader" :key="thead.name"><h3>{{thead.name}}</h3></th>
                    </tr>
                </template>

                <template #tbody>
                    <tr v-for="question in store.questions" :key="question.id" class="tr-tbody">
                        <td class="w-25"> <span class="font-weight-bold">{{question.text}}</span> </td>
                        <td class="w-33">
                            <div v-for="answer in question.answers" :key="answer.id">
                                <div v-if="question.type == 'multiple'">
                                    <ul>
                                        <li>
                                            {{answer.text}} 
                                            <v-icon v-if="answer.isTrue" color="success">mdi-check-circle</v-icon>
                                            <v-icon v-else color="red">mdi-close-circle</v-icon>
                                        </li>

                                    </ul>  
                                </div>
                                <div v-else-if ="question.type == 'true_false'">
                                    <span v-if="answer.isTrue">
                                        Vrai 
                                    <v-icon color="success">mdi-check-circle</v-icon>
                                    </span>
                                    <span v-else>
                                        Faux
                                        <v-icon color="red">mdi-close-circle</v-icon>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p>{{question.subCategory.category.name}}</p>
                            <p>{{question.subCategory.name}}</p>
                        </td>
                        <td>
                            <v-btn @click="toggleDialog(question, 'edit')" elevated class="ma-1" color="blue">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn elevated class="ma-1" color="red">
                                <v-icon>mdi-trash-can</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </template>
                <template #pagination>
                    <v-pagination v-model="page" :length="totalPages" @update:modelValue="store.fetchQuestions" ></v-pagination>
                </template>
            </Table>
        </div>


        <v-dialog v-model="dialog" max-width="1600">
            <v-card>
                <v-card-title>Ajouter une question</v-card-title>
                <v-container fluid>
                    <div class="form">
                        <QuestionForm ref="questionForm" />
                    </div>    
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <div>
                        <v-btn text @click="toggleDialog(null, null)" >Fermer</v-btn> 
                        <v-btn 
                            @click="save" 
                            varitant="outlined" 
                            color="green"
                        >Enregistrer</v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar 
            v-model="viewableSnackbar" 
            timeout="2000" 
            location="top"
            :color="colorMessage">
           {{message}} 
        </v-snackbar>


    </div>
</template>

<script setup>
import Table from '../../components/Table.vue';
import QuestionForm from '../../components/question/QuestionForm.vue'
import { ref, reactive, onMounted, computed, watch } from 'vue';
import axios from "axios";
import { useQuestionStore } from "../../stores/questionStore";

// --------------------------------------------------------- Add


const questionStore = useQuestionStore();
const store = useQuestionStore();
const title = ref('Liste des questions');
const questionForm = ref(null);
const action = ref("");

// Tableau
const tableHeader = reactive([
    {'name': 'Question'},
    {'name': 'Réponse'},
    {'name': 'Categorie'},
    {'name': 'Action'},
]);

// Pagination
const page = ref(1);
const limit = ref(10);
const totalPages = computed(() => Math.ceil(store.total / limit.value));

// Dialog
const dialog = ref(false);

// Snackbar
const viewableSnackbar = ref(false);
const message = ref("");
const colorMessage = ref("");

// --------------------------------------------------------- Actions


// Fermer Ouvrir la PopUp
const toggleDialog = (question, actionClick) => {
    action.value = actionClick;


    if (question !== null){
        store.question.id = question.id;
        store.question.text = question.text;
        store.question.type = question.type;
        store.question.subCategory = question.subCategory;
        store.question.category = question.subCategory.category;
        store.question.answers = []; 

        question.answers.forEach(answer => {
            store.question.answers.push(
                { 
                    id: answer.id, 
                    text: answer.text, 
                    isTrue: answer.isTrue, 
                    nameForm: `answer-${store.countAnswers.value}` 
                }
            )
            store.countAnswers++;
        });
    } 

    !dialog.value ? dialog.value = true : dialog.value = false;
};

// Ajouter une category
const save = () => {

    if (action.value == 'edit'){
        store.editQuestion()
            .then(response => {
                if (response.data.success == true){

                    toggleDialog(null, null);                
                    message.value = response.data.message;
                    viewableSnackbar.value =  true;
                    colorMessage.value = "success";
                } 
        }).catch(error => {
            if (error.response.data.success == false){
                message.value = error.response.data.message;
                viewableSnackbar.value = true;
                colorMessage.value = "warning"
            } 
        });    
    } else {
        store.addQuestion()
            .then(response => {
                if (response.data.success == true){
                    toggleDialog(null, null);                
                    message.value = response.data.message;
                    viewableSnackbar.value =  true;
                    colorMessage.value = "success";
                } 
        }).catch(error => {
            if (error.response.data.success == false){
                message.value = error.response.data.message;
                viewableSnackbar.value = true;
                colorMessage.value = "warning"
            } 
        }); 
    } 

}

watch(dialog, () => {
    if (action.value !== "edit"){
        store.question.text = null;
        store.countAnswers = 1;
        store.question.answers.splice(1);
        store.question.answers[0].id = null;
        store.question.answers[0].text = null;
        store.question.type = 'true_false';
        store.question.answers[0].isTrue = false;
        store.question.category = null;
        store.question.subCategory = null;
    } 
    if (action.value == 'add'){
        store.question.category = null;
        store.question.subCategory = null;
    }
});

// --------------------------------------------------------- Hook



onMounted(() => {
    store.fetchQuestions();
})

</script>
<style scoped>
.question-page{
    display: flex;
    justify-content: center;
    align-items: center;   
}
.question-table {
    min-width: 100%;
}
.tr-tbody{
    height: 100px;
} 
</style>
