<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center w-2/5 p-8 bg-[#070707] border-2 rounded-3xl border-neutral-300">
            <div class="flex flex-col px-16 pb-2 pt-6">
                <span class="mb-2 text-3xl text-neutral-300 cursor-default">Login</span>
            </div>
            <form class="flex flex-col w-full">
                <div class="flex flex-col px-16 pb-2 pt-6">
                    <span class="mb-2 text-xl text-neutral-300 cursor-default">E-mail</span>
                    <input v-model="loginEmail" type="email" autocomplete="email" placeholder="your e-mail address" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                    <span class="mt-2">
                        <span class="text-base text-transparent cursor-default">.</span>
                        <span v-if="this.v$.loginEmail.$dirty && this.v$.loginEmail.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.loginEmail.required.$invalid ? message_required : message_email }}</span>
                    </span>
                </div>
                <div class="flex flex-col px-16 pb-6 pt-2">
                    <span class="mb-2 text-xl text-neutral-300 cursor-default">Password</span>
                    <input v-model="loginPassword" type="password" autocomplete="off" placeholder="your password" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                    <span class="mt-2">
                        <span class="text-base text-transparent cursor-default">.</span>
                        <span v-if="this.v$.loginEmail.$dirty && this.v$.loginPassword.$invalid" class="text-base text-red-500 cursor-default">* {{ message_required }}</span>
                    </span>
                </div>
            </form>
            <div class="flex flex-col px-16 pb-6 pt-2">
                <button @click="login" class="px-3 py-1 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition">Login</button>
            </div>
            <div class="flex flex-col px-16 pb-6 pt-6">
                <span class="mb-2 text-xl text-neutral-300 cursor-default">Don't have an account? <button @click="goTo('register')" class="px-3 py-1 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition">Register</button></span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'

export default {
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            loginEmail: null,
            loginPassword: null,

            message_required: 'This field is required',
            message_email: 'This field must be a valid e-mail',
            message_length: 'This field must be at least 8 characters long',
        }
    },
    validations () {
        return {
            loginEmail: {required, email},
            loginPassword: {required},
        }
    },
    methods: {
        async login() {
            this.v$.$touch();

            if (!this.v$.$invalid) {
                const token = await axios.get(`/api/v1/users/login?email=${this.loginEmail}}&password=${this.loginPassword}`);
                console.log(token);
            }
        },
        goTo(path) {
            this.$router.replace({ path })
        },
    },
}
</script>