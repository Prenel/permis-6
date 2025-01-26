<template>
    <div class="login-form">

        <h1>Login</h1>

        <form @submit.prevent="submitLogin">
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" v-model="username" placeholder="Entrez votre identifiant" required>
                <v-input></v-input>
            </div>

            <div>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" v-model="password" placeholder="Entrez votre mot de passe" required>
                <v-input></v-input>
            </div>
            
            <div v-if="error" class="error">
                {{ error }}
            </div>

            <button type="submit">Login</button>
        </form>

        <p v-if="isAuthenticated" class="success">Vous êtes connecté avec succès !</p>
    </div>
</template>
<script>
import axios from "axios";

export default {
    name: "LoginForm",
    data(){
        return{
            username: "",
            password: "",
            error: null,
            isAuthenticated: false,

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
            try{
                const response = await axios.post("/login_check",{
                    username: this.username,
                    password: this.password,
                    "_csrf_token": this.csrfToken
                });

                if (response.data.success == true){
                    console.log(response.data);
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
</script>
<style scoped> 
.login-form { 
    max-width: 400px; 
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
.login-form label { 
    display: block; 
    margin-bottom: 5px; 
} 
.login-form input { 
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