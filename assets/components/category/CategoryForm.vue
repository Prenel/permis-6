<template>
    <v-form>
        <v-text-field 
            label="Nom de catégorie" 
            placeholder="Entrez le nom de la catégorie"
            :rules="[rules.required]"
            v-model="store.category.name"
        ></v-text-field>

        <div v-for="subCategory in store.category.subCategories" :key="subCategory.name">
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

        
    </v-form>
</template>
<script setup>
import { ref } from 'vue';
import { required } from '../../rules'
import { useCategoryStore } from "../../stores/categoryStore";

const rules = ref({required});
const categoryName = ref("");
const countSub = ref(1);
const store = useCategoryStore();

// Ajouter un champ Texte pour sous catégories
const addSubCategoryField = () => {
    const isEmptyValue = store.category.subCategories.some((element) => element.value === "" ? true : false);
    if (isEmptyValue !== true){
        store.countSubCategories++;
        store.category.subCategories.push({'name': `subCategory-${store.countSubCategories}`, 'value': ""}) ;
    }
};

</script>