<template>
    <div class="login-page">
        <LoginForm />
    </div>
</template>

<script>
import LoginForm from '../components/LoginForm.vue'

export default {
    name: "LoginPage",
    componets:{
        LoginForm,
    },     
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