<template>
    <div class="question-page">
        <div class="question-table" >
            
            <h1>{{title}}</h1>
            
            <!-- <v-btn class="mt-5" color="blue" @click="toggleDialog(null, 'add')">Ajouter <v-icon>mdi-plus</v-icon></v-btn> -->
            <Table :header="tableHeader">
                <template #thead>
                    <tr>
                        <th v-for="thead in tableHeader" :key="thead.name"><h3>{{thead.name}}</h3></th>
                    </tr>
                </template>

                <!-- <template #tbody>
                    <tr v-for="category in store.categories" :key="category.id" class="tr-tbody">
                        <td> {{category.name}} </td>
                        <td class="w-50">
                            <v-chip 
                                v-for="(subCategory, index) in category.subCategories" 
                                :key="subCategory.id" 
                                :color="getChipColor(index)" 
                                class="ma-1" 
                            >
                                {{subCategory.name}}
                            </v-chip>  
                        </td>
                        <td>
                            <v-btn @click="toggleDialog(category, 'edit')" elevated class="ma-1" color="blue">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn elevated class="ma-1" color="red">
                                <v-icon>mdi-trash-can</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </template> -->
                <template #pagination>
                    <v-pagination v-model="page" :length="totalPages" @update:modelValue="store.fetchCategories" ></v-pagination>
                </template>
            </Table>
        </div>
    </div>
</template>

<script setup>
import Table from '../../components/Table.vue';
import CategoryForm from '../../components/category/CategoryForm.vue'
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
    {'name': 'Categorie'},
    {'name': 'Action'},
]);

// Pagination
const page = ref(1);
const limit = ref(10);
const totalPages = computed(() => Math.ceil(store.total / limit.value));



// --------------------------------------------------------- Actions

// Ajouter une category
// const save = () => {

//     if (action.value == 'edit'){
//         store.editCategory()
//             .then(response => {
//                 if (response.data.success == true){

//                     toggleDialog(null, null);                
//                     message.value = response.data.message;
//                     viewableSnackbar.value =  true;
//                     colorMessage.value = "success";
//                 } 
//         }).catch(error => {
//             if (error.response.data.success == false){
//                 message.value = error.response.data.message;
//                 viewableSnackbar.value = true;
//                 colorMessage.value = "warning"
//             } 
//         });    
//     } else {
//         store.addCategory()
//             .then(response => {
//                 if (response.data.success == true){
    
//                     toggleDialog(null, null);                
//                     message.value = response.data.message;
//                     viewableSnackbar.value =  true;
//                     colorMessage.value = "success";
//                 } 
//         }).catch(error => {
//             if (error.response.data.success == false){
//                 message.value = error.response.data.message;
//                 viewableSnackbar.value = true;
//                 colorMessage.value = "warning"
//             } 
//         }); 
//     } 

// }


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
    height: 75px;
} 
</style>
