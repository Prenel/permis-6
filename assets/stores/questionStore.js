import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';


export const useQuestionStore = defineStore("questionStore", () => {

    const questions = ref([]);
    const countAnswer = ref(1);
    const question = ref({
        id: 0,
        text: "",
        subQuestions: [] 
    });
    const total = ref(0);
    
    const fetchQuestions = async (page = 1, limit = 10) => {
        try {
            const response = await axios.get("/admin/question/list",{
                params: {page, limit} 
            });
            console.log(response.data.result.data);
            if (response.status == 200) {
                
              questions.value = response.data.result.data;
              total.value = response.data.result.total;
            }

        } catch(error){
            console.log(error.response);
        } 
    }

    const addQuestion = async () => {
        return await axios.post('/admin/question/add', {
            question: question.value
        });
    }  

    const editQuestion = async () => {
        return await axios.post('/admin/question/edit', {
            question: question.value
        });
    }  

    return {
        questions,
        question,
        total,
        countSubQuestions,
        fetchQuestions,
        addQuestion,
        editQuestion,
    } 

});