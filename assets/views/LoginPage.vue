<template>
    <div class="login-page">
        <LoginForm :csrfToken="csrfToken"/>
    </div>
</template>

<script>
import LoginForm from '../components/LoginForm.vue'

export default {
    name: "LoginPage",
    components:{
        LoginForm,
    },
    props:{
        csrfToken:{
            type: String,
            required:true,
        } 
    } 
}
</script>
<style scoped>
.login-page{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100 vh;
    background-color:#f8f9fa;
} 
</style>