<template>
    <div class="category-page">
        <v-container>
            
            <h1>{{title}}</h1>
            
            <v-btn @click="toggleDialog">Ajouter <v-icon>mdi-plus</v-icon></v-btn>
            <Table :header="tableHeader">
                <template #thead>
                    <tr>
                        <th v-for="thead in tableHeader" :key="thead.name">{{thead.name}}</th>
                    </tr>
                </template>

                <template #tbody>
                    <tr v-for="category in tableData" :key="category.id">
                        <td> {{category.name}} </td>
                        <td >
                            <ul>
                                <li v-for="subCategory in category.subCategories" :key="subCategory.id">
                                    {{subCategory.name}}
                                </li>
                            </ul>   
                        </td>
                        <td>
                            <v-btn elevated><v-icon>mdi-pencil</v-icon></v-btn>
                            <v-btn elevated><v-icon>mdi-trash-can</v-icon></v-btn>
                        </td>
                    </tr>
                </template>
            </Table>
        </v-container>
        
        <v-dialog v-model="dialog" @update:modelValue="dialogClosed" max-width="500">
            <v-card>
                <v-card-title>Ajouter une catégorie</v-card-title>
                <v-container>
                    <div class="form">
                        <CategoryForm ref="categoryForm" @categorySave="toggleDialog" />
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
    </div>
</template>
<script setup>
import Table from '../../components/Table.vue';
import CategoryForm from '../../components/category/CategoryForm.vue'
import { ref, reactive } from 'vue';
import axios from "axios";

const title = ref('Liste des catégories');
const dialog = ref(false);
const categoryForm = ref(null);


// Tableau
const tableHeader = reactive([
    {'name': 'Nom'},
    {'name': 'Sous-catégorie'},
    {'name': 'Action'},
]);
const tableData = reactive([
    {'id': 1, 'name': 'Sécurité', 'subCategories': [
        {'id': 1, 'name': 'Risque'},
        {'id': 2, 'name': 'Cyber'},
        {'id': 3, 'name': 'Incident de sécurité'},
        {'id': 4, 'name': 'PSSI'},
        ] 
    },
    {'id': 2, 'name': 'Prod First', 'subCategories': [
        {'id': 1, 'name': 'Incident'},
        {'id': 2, 'name': 'Problème'},
        {'id': 3, 'name': 'Changement'},
        {'id': 4, 'name': 'CMDB'},
        {'id': 4, 'name': 'Catalogue des demandes/Requête'},
        {'id': 4, 'name': 'Erreur humaine'},
        ] 
    }, 
]);

// Dialog
const toggleDialog = () => {
    console.log("hello");
    !dialog.value ? dialog.value = true : dialog.value = false;
    dialogClosed();
};
const dialogClosed = () => {
    if (dialog.value == false) {
        categoryForm.value.reInitData();
    }
}
const save = () => {
    categoryForm.value.addCategory();
} 

</script>
<style scoped>
.category-page{
    display: flex;
    justify-content: center;
    align-items: center;   
} 
</style>
