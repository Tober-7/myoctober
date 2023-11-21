<template>
    <div class="flex flex-col h-full">
        <div class="flex justify-between items-center px-16 h-28 bg-[#070707] border-b border-neutral-300">
            <div class="flex items-center">
                <img src="@/assets/icons/logo.png" class="w-11 h-11 mr-6">
                <button @click="goTo('home')" class="text-4xl tracking-wider text-neutral-300 hover:text-blue-600 transition">{{ $t('app.name') }}</button>
            </div>
            <button v-if="isLoggedIn" @click="profile()" class="p-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition"><img src="@/assets/icons/account.png" class="w-6"></button>
        </div>
        <router-view @set-is-logged-in="(value) => setIsLoggedIn(value)"></router-view>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoggedIn: false,
        }
    },

    methods: {
        profile() {
            this.goTo('profile');
        },
        setIsLoggedIn(value) {
            this.isLoggedIn = value ? true : false;
        },
        goTo(path) {
            this.$router.replace({ path })
        },
    },

    mounted() {
        this.setIsLoggedIn(localStorage.getItem('myoctober_backend_user_token'));
    },
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
    caret-color: rgb(212 212 212 / 1);
}

</style>