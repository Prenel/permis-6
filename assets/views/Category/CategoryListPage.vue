<template>
    <div class="category-page">
        <div class="category-table" >
            
            <h1>{{title}}</h1>
            
            <v-btn class="mt-5" color="blue" @click="toggleDialog">Ajouter <v-icon>mdi-plus</v-icon></v-btn>
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
                            <v-btn elevated class="ma-1" color="blue">
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
                        <v-btn text @click="toggleDialog" >Fermer</v-btn> 
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

const categoryStore = useCategoryStore();
const store = useCategoryStore();
const title = ref('Liste des catégories');
const categoryForm = ref(null);

onMounted(() => {
    store.fetchCategories();
})

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


// ------------------ Actions

// Ajouter une category
const save = () => {
    store.addCategory()
        .then(response => {
            if (response.data.success == true){

                toggleDialog();                
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

// Ajouter des coleur au badge chips
const getChipColor = (index) => {
    const colors = ["blue", "green", "red", "purple", "orange", "teal"];
    return colors[index % colors.length];  
}

// Fermer Ouvrir la PopUp
const toggleDialog = () => {
    !dialog.value ? dialog.value = true : dialog.value = false;
};

watch(dialog, () => {
    store.category.name = "";
    store.countSubCategories = 1;
    store.category.subCategories.splice(1);
    store.category.subCategories[0].value = "";
});


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
