import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';


export const useQuizzStore = defineStore("quizzStore", () => {

    const questions = ref([]);

    const fetchQuestions = async (page = 1, limit = 10) => {
        try {
            const response = await axios.get("/admin/question/list",{
                params: {page, limit} 
            });
            
            if (response.status == 200) {
                console.log(response.data.result);
                questions.value = response.data.result.data;
                total.value = response.data.result.total;
            }

        } catch(error){
            console.log(error.response);
        } 
    }

    const removeLastQuestion = () =>  {
        if (questions.value.length > 0){
            questions.value.pop();
        } 
    }
    

    return {
        questions,
        fetchQuestions,
        removeLastQuestion
    } 

});