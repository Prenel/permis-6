<template>
    <div class="category-page">
        <div class="category-table" >
            
            <h1>{{title}}</h1>
            
            <v-btn class="mt-5" color="blue" @click="toggleDialog(null, 'add')">Ajouter <v-icon>mdi-plus</v-icon></v-btn>
            <Table :header="tableHeader">
                <template #thead>
                    <tr>
                        <th v-for="thead in tableHeader" :key="thead.name"><h3>{{thead.name}}</h3></th>
                    </tr>
                </template>

                <template #tbody>
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
                </template>
                <template #pagination>
                    <v-pagination v-model="page" :length="totalPages" @update:modelValue="store.fetchCategories" ></v-pagination>
                </template>
            </Table>
        </div>
        
        <v-dialog v-model="dialog" max-width="500">
            <v-card>
                <v-card-title>Ajouter une catégorie</v-card-title>
                <v-container>
                    <div class="form">
                        <CategoryForm ref="categoryForm"  />
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
import CategoryForm from '../../components/category/CategoryForm.vue'
import { ref, reactive, onMounted, computed, watch } from 'vue';
import axios from "axios";
import { useCategoryStore } from "../../stores/categoryStore";

// --------------------------------------------------------- Add


const categoryStore = useCategoryStore();
const store = useCategoryStore();
const title = ref('Liste des catégories');
const categoryForm = ref(null);
const action = ref("");

// Tableau
const tableHeader = reactive([
    {'name': 'Nom'},
    {'name': 'Sous-catégorie'},
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

// Ajouter une category
const save = () => {

    if (action.value == 'edit'){
        store.editCategory()
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
        store.addCategory()
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

// Ajouter des coleur au badge chips
const getChipColor = (index) => {
    const colors = ["blue", "green", "red", "purple", "orange", "teal"];
    return colors[index % colors.length];  
}

// Fermer Ouvrir la PopUp
const toggleDialog = (category, actionClick) => {
    action.value = actionClick;

    if (category !== null){
        store.category.id = category.id;
        store.category.name = category.name;
        store.category.subCategories = []; 
        
        category.subCategories.forEach(subCategory => {
            store.category.subCategories.push(
                {
                    'id': subCategory.id, 
                    'name': `subCategory-${store.countSubCategories}`, 
                    'value': subCategory.name,  
                }
            )
            store.countSubCategories++;
        });

    } 

    !dialog.value ? dialog.value = true : dialog.value = false;
};

// --------------------------------------------------------- Hook

// Vide le model si ce n'est pas edit
watch(dialog, () => {
    if (action.value !== "edit"){
        store.category.name = "";
        store.countSubCategories = 1;
        store.category.subCategories.splice(1);
        store.category.subCategories[0].value = "";
    } 
});

onMounted(() => {
    store.fetchCategories();
})

</script>
<style scoped>
.category-page{
    display: flex;
    justify-content: center;
    align-items: center;   
}
.category-table {
    min-width: 100%;
}
.tr-tbody{
    height: 75px;
} 
</style>
