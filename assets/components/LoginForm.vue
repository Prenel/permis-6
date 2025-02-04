<template>
    <div class="login-form">

        <h1>Login</h1>

        

        <v-form @submit.prevent="submitLogin" >
            
                <v-text-field
                    label="Identifiant"
                    :rules="[rules.required]"
                    v-model="username"
                    placeholder="Entrez votre identifiant"
                    class="input"
                    ></v-text-field>                
            

            
                <v-text-field
                    label="Mot de passe"
                    :rules="[rules.required]"
                    v-model="password"
                    placeholder="Entrez votre mot de passe"
                    class="input"
                    ></v-text-field>                
           
            
            <div v-if="error" class="error">
                {{ error }}
            </div>

            <button type="submit">Login</button>
        </v-form>
                
    </div>
</template>
<script>
import axios from "axios";
import { required } from '../rules' 
export default {
    name: "LoginForm",
    data(){
        return{
            username: "",
            password: "",
            error: null,
            isAuthenticated: false,
            rules: {
                required,
            } 

        };
    },
    props:{
        csrfToken:{
            type: String,
            required:true
        } 
    },
    methods:{
        async submitLogin(){
            this.error = null;
            if (this.username !== "" && this.password !== "" && this.csrfToken !== ""){
                try{
                    const response = await axios.post("/login_check",{
                        username: this.username,
                        password: this.password,
                        "_csrf_token": this.csrfToken
                    });
    
                    if (response.data.success == true){
                        window.location.href = response.data.redirectUrl;
                    } 
                } catch (err){
                    if (err.response && err.response.status == 401){
                        this.error = "Identifiant ou mot de passe invalide.";
                    } else {
                        this.error = "Une erreur est survenur, Essayer une nouvelle fois.";
                    } 
                }
            } 
        } 
    } 

}
</script>
<style scoped> 
.login-form { 
    max-width: 400px;
    min-width: 400px;
    margin: 0 auto; 
    padding: 20px; 
    border: 1px solid #ccc; 
    border-radius: 5px; 
}
.login-form h1 { 
    text-align: center; 
    margin-bottom: 20px; 
} 
.login-form div { 
    margin-bottom: 15px; 
} 
.login-form .input { 
    width: 100%; 
    padding: 10px; 
    font-size: 16px; 
} 
.login-form .error { 
    color: red; 
    margin-bottom: 15px; 
    text-align: center; 
} 
.login-form button { 
    width: 100%; 
    padding: 10px; 
    font-size: 16px; 
    background-color: #007bff; 
    color: white; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
} 
.login-form button:hover {
    background-color: #0056b3; 
} 
</style> 