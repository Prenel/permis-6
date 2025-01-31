<template>
    <v-form>
        <v-text-field 
            label="Nom de catégorie" 
            placeholder="Entrez le nom de la catégorie"
            :rules="[rules.required]"
            v-model="categoryName"
        ></v-text-field>

        <div v-for="subCategory in subCategories" :key="subCategory.name">
            <v-text-field 
                label="Nom de sous-catégorie" 
                placeholder="Entrez le nom de la sous-catégorie"
                :rules="[rules.required]"
                :name="subCategory.name"
                v-model="subCategory.value"
            ></v-text-field>
        </div>
        
        <div class="d-flex">
            <v-btn @click="addSubCategoryField" class="mx-auto"><v-icon>mdi-plus</v-icon></v-btn>
        </div>

        <v-snackbar 
            v-model="viewableSnackbar" 
            timeout="2000" 
            location="top"
            :color="colorMessage">
           {{message}} 
        </v-snackbar>
    </v-form>
</template>
<script setup>
import { ref, reactive, defineEmits, defineExpose } from 'vue';
import axios from "axios";
import { required } from '../../rules'

const emit = defineEmits(['categorySave']);

const viewableSnackbar = ref(false);
const message = ref("");
const colorMessage = ref("");
const rules = ref({required});
const categoryName = ref("");
const countSub = ref(1);

const subCategories = reactive([
    {'name': `subCategory-${countSub.value}`, 'value': "" } 
])

// Ajouter un champ Texte pour sous catégories
const addSubCategoryField = () => {
    const isEmptyValue = subCategories.some((element) => element.value === "" ? true : false);
    if (isEmptyValue !== true){
        countSub.value++;
        subCategories.push({'name': `subCategory-${countSub.value}`, 'value': ""});
    }
};

// Réinitialiser les données
const reInitData = () => {
    categoryName.value = "";
    countSub.value = 1;
    subCategories.splice(1);
    subCategories[0].value = "";
}

const addCategory = async () => {
    
    try{
        const response = await axios.post("/admin/category/add",{
            categoryName: categoryName.value,
            subCategories: subCategories,
        });

        if (response.data.success == true){
            console.log(response.data);
            message.value = response.data.message;
            viewableSnackbar.value =  true;
            colorMessage.value = "success";
            emit('categorySave');
        } 
    } catch (error){
        if (error.response.data.success == false){
            message.value = error.response.data.message;
            viewableSnackbar.value = true;
            colorMessage.value = "warning"
        } 
    }
     
} 
defineExpose({ reInitData, addCategory });
</script>