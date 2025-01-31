import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';


export const useCategoryStore = defineStore("categoryStore", () => {

    const categories = ref([]);
    const countSubCategories = ref(1);
    const category = ref({
        'name': "",
        subCategories: [{'name': `subCategory-${countSubCategories.value}`, 'value': "" }] 
    });
    const total = ref(0);
    
    const fetchCategories = async (page = 1, limit = 10) => {
        try {
            const response = await axios.get("/admin/category/list",{
                params: {page, limit} 
            });
            
            if (response.status == 200) {
                
              categories.value = response.data.result.data;
              total.value = response.data.result.total;
            }

        } catch(error){
            console.log(error.response);
        } 
    }

    const addCategory = async () => {
        return await axios.post('/admin/category/add', {
            category: category.value
        });
    }  

    return {
        categories,
        category,
        total,
        countSubCategories,
        fetchCategories,
        addCategory,
    } 

});