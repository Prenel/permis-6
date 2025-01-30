<template>
    <div class="category-page">
        <v-container>
            
            <h1>{{title}}</h1>
            
            <v-btn>Ajouter <v-icon>mdi-plus</v-icon></v-btn>
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
    </div>
</template>
<script setup>
import Table from '../../components/Table.vue';
import { ref, reactive } from 'vue';

const title = ref('Liste des catégories');
const tableHeader = reactive([
    {'name': 'Nom'},
    {'name': 'Sous-catégorie'},
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
</script>
<style scoped>
.category-page{
    
    display: flex;
    justify-content: center;
    align-items: center;
    
} 

</style>
