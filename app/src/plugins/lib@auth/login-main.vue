<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center min-w-[600px] p-16 bg-[#070707] border-2 rounded-3xl border-neutral-300">
            <div class="flex flex-col">
                <span class="mb-2 text-2xl text-neutral-300 cursor-default">{{ $t("auth.titles.login") }}</span>
            </div>
            <form class="flex flex-col w-full">
                <div class="flex flex-col pt-6">
                    <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.email") }}</span>
                    <input @keyup.enter="login()" v-model="loginForm.email" type="email" :autocomplete="loginForm.email == '' ? 'email' : 'none'" :placeholder="$t('auth.placeholders.email')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                    <span class="mt-2">
                        <span class="text-base text-transparent cursor-default">.</span>
                        <span v-if="this.v$.loginForm.email.$dirty && this.v$.loginForm.email.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.loginForm.email.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.email") }}</span>
                    </span>
                </div>
                <div class="flex flex-col pb-6">
                    <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.password") }}</span>
                    <input @keyup.enter="login()" v-model="loginForm.password" type="password" autocomplete="none" :placeholder="$t('auth.placeholders.password')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                    <span class="mt-2">
                        <span class="text-base text-transparent cursor-default">.</span>
                        <span v-if="this.v$.loginForm.password.$dirty && this.v$.loginForm.password.$invalid" class="text-base text-red-500 cursor-default">* {{ $t("auth.errors.required") }}</span>
                    </span>
                </div>
            </form>
            <div class="flex flex-col pb-6">
                <button @click="login()" class="px-4 pb-3 pt-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-xl transition">{{ $t("auth.buttons.login") }}</button>
            </div>
            <div class="flex flex-col pt-6">
                <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.titles.noAccount") }} <button @click="goTo('register')" class="px-3 pb-1 pt-1 mx-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition">{{ $t("auth.buttons.register") }}</button></span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import { useVuelidate } from '@vuelidate/core';
import { required, email } from '@vuelidate/validators';

export default {
    emits: ['setIsLoggedIn'],

    setup () {
        return { v$: useVuelidate() }
    },

    data() {
        return {
            loginForm: {
                email: '',
                password: '',
            },
        }
    },
    validations () {
        return {
            loginForm: {
                email: {required, email},
                password: {required},
            },
        }
    },

    methods: {
        async login() {
            this.v$.$touch();

            if (!this.v$.$invalid) {
                try {
                    const res = await axios.get(`/api/v1/users?email=${this.loginForm.email}&password=${this.loginForm.password}`);

                    localStorage.setItem('myoctober_backend_user_token', res.data);
                    this.$emit('setIsLoggedIn', localStorage.getItem('myoctober_backend_user_token'));
                    this.goTo('home');
                } catch (error) {
                    this.$toast.error(error.response.data, {position: 'bottom'})
                }
            }
        },
        goTo(path) {
            this.$router.replace({ path })
        },
    },
}
</script>