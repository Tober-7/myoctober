<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center w-1/2 p-16 bg-[#070707] border-2 rounded-3xl border-neutral-300">
            <div class="flex flex-col">
                <span class="mb-2 text-2xl text-neutral-300 cursor-default">{{ $t("auth.titles.register") }}</span>
            </div>
            <form class="flex flex-col w-full">
                <div class="flex justify-between">
                    <div class="flex flex-col pr-6 pt-6 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.name") }}</span>
                        <input @keyup.enter="register()" v-model="registerForm.name" type="text" :autocomplete="registerForm.name == '' ? 'name' : 'none'" :placeholder="$t('auth.placeholders.name')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerForm.name.$dirty && this.v$.registerForm.name.$invalid" class="text-base text-red-500 cursor-default">* {{ $t("auth.errors.required") }}</span>
                        </span>
                    </div>
                    <div class="flex flex-col pl-6 pt-6 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.email") }}</span>
                        <input @keyup.enter="register()" v-model="registerForm.email" type="email" :autocomplete="registerForm.email == '' ? 'email' : 'none'" :placeholder="$t('auth.placeholders.email')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerForm.email.$dirty && this.v$.registerForm.email.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.registerForm.email.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.email") }}</span>
                        </span>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="flex flex-col pr-6 pb-6 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.password") }}</span>
                        <input @keyup.enter="register()" v-model="registerForm.password" type="password" autocomplete="none" :placeholder="$t('auth.placeholders.password')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerForm.password.$dirty && this.v$.registerForm.password.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.registerForm.password.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.minLength") }}</span>
                        </span>
                    </div>
                    <div class="flex flex-col pl-6 pb-6 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.confirmationPassword") }}</span>
                        <input @keyup.enter="register()" v-model="registerForm.confirmationPassword" type="password" autocomplete="none" :placeholder="$t('auth.placeholders.confirmationPassword')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerForm.confirmationPassword.$dirty && this.v$.registerForm.confirmationPassword.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.registerForm.confirmationPassword.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.sameAsPassword") }}</span>
                        </span>
                    </div>
                </div>
            </form>
            <div class="flex flex-col pb-6">
                <button @click="register()" class="px-4 pb-3 pt-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-xl transition">{{ $t("auth.buttons.register") }}</button>
            </div>
            <div class="flex flex-col pt-6">
                <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.titles.accountExists") }} <button @click="goTo('login')" class="px-3 pb-1 pt-1 mx-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition">{{ $t("auth.buttons.login") }}</button></span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import { useVuelidate } from '@vuelidate/core';
import { required, email, minLength, sameAs } from '@vuelidate/validators';

export default {
    emits: ['setIsLoggedIn'],

    setup () {
        return { v$: useVuelidate() }
    },

    data() {
        return {
            registerForm: {
                name: '',
                email: '',
                password: '',
                confirmationPassword: '',
            },
        }
    },
    validations () {
        return {
            registerForm: {
                name: {required},
                email: {required, email},
                password: {required, minLength: minLength(8)},
                confirmationPassword: {required, sameAs: sameAs(this.registerForm.password)},
            },
        }
    },
    
    methods: {
        async register() {
            this.v$.$touch();

            if (!this.v$.$invalid) {
                const res = await axios.post(`/api/v1/users?name=${this.registerForm.name}&email=${this.registerForm.email}&password=${this.registerForm.password}&confirmationPassword=${this.registerForm.confirmationPassword}`);

                if (res.data.status === 200) {
                    localStorage.setItem('myoctober_backend_user_token', res.data.token);
                    this.$emit('setIsLoggedIn', localStorage.getItem('myoctober_backend_user_token'));
                    this.goTo('home');
                }
                else this.$toast.error(res.data.message, {position: 'bottom'})
            }
        },
        goTo(path) {
            this.$router.replace({ path })
        },
    },
    updated() {
        console.log(this.registerForm.email == '');
    }
}
</script>