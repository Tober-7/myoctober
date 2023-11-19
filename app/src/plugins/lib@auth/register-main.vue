<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center w-4/5 p-8 bg-[#070707] border-2 rounded-3xl border-neutral-300">
            <div class="flex flex-col px-16 pb-2 pt-6">
                <span class="mb-2 text-2xl text-neutral-300 cursor-default">Register</span>
            </div>
            <form class="flex flex-col w-full">
                <div class="flex justify-between">
                    <div class="flex flex-col px-16 pb-0 pt-6 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">Full Name</span>
                        <input @keyup.enter="register()" v-model="registerName" type="text" autocomplete="name" placeholder="your full name" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerName.$dirty && this.v$.registerName.$invalid" class="text-base text-red-500 cursor-default">* {{ message_required }}</span>
                        </span>
                    </div>
                    <div class="flex flex-col px-16 pb-0 pt-6 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">E-mail</span>
                        <input @keyup.enter="register()" v-model="registerEmail" type="email" autocomplete="email" placeholder="your e-mail address" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerEmail.$dirty && this.v$.registerEmail.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.registerEmail.required.$invalid ? message_required : message_email }}</span>
                        </span>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="flex flex-col px-16 pb-6 pt-0 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">Password</span>
                        <input @keyup.enter="register()" v-model="registerPassword" type="password" autocomplete="off" placeholder="your password" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerEmail.$dirty && this.v$.registerPassword.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.registerPassword.required.$invalid ? message_required : message_length }}</span>
                        </span>
                    </div>
                    <div class="flex flex-col px-16 pb-6 pt-0 w-full">
                        <span class="mb-2 text-xl text-neutral-300 cursor-default">Confirmation Password</span>
                        <input @keyup.enter="register()" v-model="registerConfirmationPassword" type="password" autocomplete="off" placeholder="your password again" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                        <span class="mt-2">
                            <span class="text-base text-transparent cursor-default">.</span>
                            <span v-if="this.v$.registerEmail.$dirty && this.v$.registerConfirmationPassword.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.registerConfirmationPassword.required.$invalid ? message_required : message_samePassword }}</span>
                        </span>
                    </div>
                </div>
            </form>
            <div class="flex flex-col px-16 pb-6 pt-0">
                <button @click="register()" class="px-4 pb-3 pt-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-xl transition">Register</button>
            </div>
            <div class="flex flex-col px-16 pb-6 pt-6">
                <span class="mb-2 text-xl text-neutral-300 cursor-default">Already have an account? <button @click="goTo('login')" class="px-3 pb-1 pt-1 mx-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition">Login</button></span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import { useVuelidate } from '@vuelidate/core'
import { required, email, minLength, sameAs } from '@vuelidate/validators'

export default {
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            registerName: null,
            registerEmail: null,
            registerPassword: null,
            registerConfirmationPassword: null,

            message_required: 'This field is required',
            message_email: 'This field must be a valid e-mail',
            message_length: 'This field must be at least 8 characters long',
            message_samePassword: 'This field must match the first password',
        }
    },
    validations () {
        return {
            registerName: {required},
            registerEmail: {required, email},
            registerPassword: {required, minLength: minLength(8)},
            registerConfirmationPassword: {required, sameAs: sameAs(this.registerPassword)},
        }
    },
    methods: {
        async register() {
            this.v$.$touch();

            if (!this.v$.$invalid) {
                const res = await axios.post(`/api/v1/users?name=${this.registerName}&email=${this.registerEmail}&password=${this.registerPassword}&confirmationPassword=${this.registerConfirmationPassword}`);
                console.log(res);
                if (res.data.status === 200) {
                    if (localStorage.getItem('myoctober_backend_user_token')) this.$toast.error("Can't create a user when logged in.");
                    else {
                        localStorage.setItem('myoctober_backend_user_token', res.data.data);
                        this.goTo('home');
                        this.$forceUpdate();
                    }
                }
                else this.$toast.error(res.data.data)
            }
        },
        goTo(path) {
            this.$router.replace({ path })
        },
    }
}
</script>