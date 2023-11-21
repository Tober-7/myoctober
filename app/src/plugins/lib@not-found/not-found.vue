<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center w-2/5 p-8 bg-[#070707] border-2 rounded-3xl border-neutral-300">
            <div class="flex flex-col px-16 pb-2 pt-6">
                <span class="mb-2 text-2xl text-neutral-300 cursor-default">Page not found</span>
            </div>
            <div class="flex flex-col px-16 pb-6 pt-6">
                <span class="mb-2 text-xl text-neutral-300 cursor-default">Go back?<button @click="goTo(isLoggedIn ? 'home' : 'login')" class="px-3 pb-1 pt-1 mx-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition">{{ isLoggedIn ? 'Home' : 'Login' }}</button></span>
            </div>
        </div>
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
        goTo(path) {
            this.$router.replace({ path })
        },
    },

    mounted() {
        this.isLoggedIn = localStorage.getItem('myoctober_backend_user_token');
    },
}
</script>