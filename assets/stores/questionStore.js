import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';


export const useQuestionStore = defineStore("questionStore", () => {

    const questions = ref([]);
    const countAnswers = ref(1);
    const question = ref({
        id: 0,
        text: "",
        type: "true_false",
        subCategory: null,
        category: null,
        answers: [
           { id: null, text: null, isTrue: false, nameForm: `answer-${countAnswers.value}` } 
        ],
    });
    const total = ref(0);
    
    const fetchQuestions = async (page = 1, limit = 10) => {
        try {
            const response = await axios.get("/admin/question/list",{
                params: {page, limit} 
            });
            
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
        countAnswers,
        fetchQuestions,
        addQuestion,
        editQuestion,
    } 

});