<template>
    <div class="flex flex-col h-full">
        <div class="flex justify-between px-16 py-8 bg-[#070707] border-b border-neutral-300">
            <span class="text-3xl tracking-wider text-neutral-300 cursor-default">Student Arrivals</span>
            <button v-if="isLoggedIn" @click="logout()" class="px-4 pb-1 pt-1 rounded text-neutral-300 bg-transparent hover:bg-red-500 border border-neutral-300 hover:border-transparent text-base transition">Log out</button>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    computed: {
        isLoggedIn() {
            return localStorage.getItem('myoctober_backend_user_token') ? true : false;
        }
    },
    methods: {
        logout() {
            localStorage.removeItem('myoctober_backend_user_token');
            this.goTo('login');
            this.$forceUpdate();
        },
        goTo(path) {
            this.$router.replace({ path })
        },
    }
}
</script>

<style>

html, body, #app {
    height: 100%;

    margin: 0;

    background-color: #0A0A0A;
}

input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active{
    -webkit-text-fill-color: rgb(212 212 212 / 1) !important;
    -webkit-box-shadow: 0 0 0 30px #070707 inset !important;
}

</style>