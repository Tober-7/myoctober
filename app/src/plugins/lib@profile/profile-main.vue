<template>
    <div class="flex flex-col justify-start items-center min-w-[800px] h-full px-16">
        <template v-if="accountId">
            <div class="flex flex-col w-full py-8">
                <div class="flex justify-between w-full pb-8">
                    <span class="text-3xl text-neutral-300 cursor-default">{{ $t("account.titles.title") }}</span>
                    <button @click="logout()" class="px-3 pb-1 pt-1 rounded text-neutral-300 bg-transparent hover:bg-red-500 border border-neutral-300 hover:border-transparent text-base transition">{{ $t("account.buttons.logout") }}</button>
                </div>
                
                <hr class="w-full border-1">

                <div class="flex justify-start w-full py-8">
                    <span class="mb-2 text-3xl text-neutral-300 cursor-default">{{ $t("account.titles.changeAccount") }}</span>
                </div>
                <form class="flex flex-col w-full">
                    <div class="flex justify-start w-1/2">
                        <div class="flex flex-col w-full">
                            <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.name") }}</span>
                            <input @keyup.enter="updateAccount()" v-model="profileForm.accountForm.name" type="text" autocomplete="none" :placeholder="$t('auth.placeholders.name')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                            <span class="mt-2">
                                <span class="text-base text-transparent cursor-default">.</span>
                                <span v-if="this.v$.profileForm.accountForm.name.$dirty && this.v$.profileForm.accountForm.name.$invalid" class="text-base text-red-500 cursor-default">* {{ $t("auth.errors.required") }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-start w-1/2">
                        <div class="flex flex-col w-full">
                            <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.email") }}</span>
                            <input @keyup.enter="updateAccount()" v-model="profileForm.accountForm.email" type="email" autocomplete="none" :placeholder="$t('auth.placeholders.email')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                            <span class="mt-2">
                                <span class="text-base text-transparent cursor-default">.</span>
                                <span v-if="this.v$.profileForm.accountForm.email.$dirty && this.v$.profileForm.accountForm.email.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.profileForm.accountForm.email.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.email") }}</span>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="pb-12 pt-6">
                    <button @click="updateAccount()" class="px-4 pb-3 pt-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-xl transition">{{ $t("account.buttons.updateAccount") }}</button>
                </div>

                <hr class="w-full border-1">

                <div class="flex justify-start w-full py-8">
                    <span class="mb-2 text-3xl text-neutral-300 cursor-default">{{ $t("account.titles.changePassword") }}</span>
                </div>
                <form class="flex flex-col w-full">
                    <div class="flex justify-start">
                        <div class="flex flex-col w-full pr-6">
                            <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.password") }}</span>
                            <input @keyup.enter="updatePassword()" v-model="profileForm.passwordForm.password" type="password" autocomplete="none" :placeholder="$t('auth.placeholders.password')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                            <span class="mt-2">
                                <span class="text-base text-transparent cursor-default">.</span>
                                <span v-if="this.v$.profileForm.passwordForm.password.$dirty && this.v$.profileForm.passwordForm.password.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.profileForm.passwordForm.password.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.minLength") }}</span>
                            </span>
                        </div>
                        <div class="flex flex-col w-full pr-6">
                            <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.newPassword") }}</span>
                            <input @keyup.enter="updatePassword()" v-model="profileForm.passwordForm.newPassword" type="password" autocomplete="none" :placeholder="$t('auth.placeholders.newPassword')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                            <span class="mt-2">
                                <span class="text-base text-transparent cursor-default">.</span>
                                <span v-if="this.v$.profileForm.passwordForm.newPassword.$dirty && this.v$.profileForm.passwordForm.newPassword.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.profileForm.passwordForm.newPassword.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.minLength") }}</span>
                            </span>
                        </div>
                        <div class="flex flex-col w-full">
                            <span class="mb-2 text-xl text-neutral-300 cursor-default">{{ $t("auth.fields.confirmationPassword") }}</span>
                            <input @keyup.enter="updatePassword()" v-model="profileForm.passwordForm.confirmationPassword" type="password" autocomplete="none" :placeholder="$t('auth.placeholders.confirmationNewPassword')" class="w-full px-4 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 focus:border-blue-600 outline-none text-neutral-300 preventAutofill placeholder-neutral-600 focus:placeholder-transparent transition">
                            <span class="mt-2">
                                <span class="text-base text-transparent cursor-default">.</span>
                                <span v-if="this.v$.profileForm.passwordForm.confirmationPassword.$dirty && this.v$.profileForm.passwordForm.confirmationPassword.$invalid" class="text-base text-red-500 cursor-default">* {{ this.v$.profileForm.passwordForm.confirmationPassword.required.$invalid ? $t("auth.errors.required") : $t("auth.errors.sameAsNewPassword") }}</span>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="pb-12 pt-6">
                    <button @click="updatePassword()" class="px-4 pb-3 pt-2 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-xl transition">{{ $t("account.buttons.updateAccount") }}</button>
                </div>

                <hr class="w-full border-1">

                <div class="flex justify-start w-full pt-8">
                    <span class="mb-2 text-3xl text-neutral-300 cursor-default">{{ $t("account.titles.deleteAccount") }}</span>
                </div>
                <div class="pt-8">
                    <button @click="deleteAccount()" class="px-4 pb-3 pt-2 rounded text-neutral-300 bg-transparent hover:bg-red-500 border border-neutral-300 hover:border-transparent text-xl transition">{{ $t("account.buttons.deleteAccount") }}</button>
                </div>
            </div>
        </template>
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
            profileForm: {
                accountForm: {
                    name: '',
                    email: '',
                },
                passwordForm: {
                    password: '',
                    newPassword: '',
                    confirmationPassword: '',
                },
            },

            accountId: null,

            canInteract: false,
        }
    },
    validations () {
        return {
            profileForm: {
                accountForm: {
                    name: {required},
                    email: {required, email},
                },
                passwordForm: {
                    password: {required, minLength: minLength(8)},
                    newPassword: {required, minLength: minLength(8)},
                    confirmationPassword: {required, sameAs: sameAs(this.profileForm.passwordForm.newPassword)},
                },
            },
        }
    },

    methods: {
        async updateAccount() {
            if (!this.canInteract) return;
            this.canInteract = false;

            this.v$.profileForm.accountForm.$touch();

            if (!this.v$.profileForm.accountForm.$invalid) {
                try {
                    const res = await axios.post(`/api/v1/updateUserAccount?name=${this.profileForm.accountForm.name}&email=${this.profileForm.accountForm.email}`, {}, this.createRequestConfig());

                    this.v$.profileForm.accountForm.$reset();
                    
                    this.$toast.success(res.data, {position: 'bottom'});
                } catch (error) {
                    this.$toast.error(error.response.data.error, {position: 'bottom'});
                }
            }

            this.canInteract = true;
        },
        async updatePassword() {
            if (!this.canInteract) return;
            this.canInteract = false;

            this.v$.profileForm.passwordForm.$touch();

            if (!this.v$.profileForm.passwordForm.$invalid) {
                try {
                    const res = await axios.post(`/api/v1/updateUserPassword?password=${this.profileForm.passwordForm.password}&newPassword=${this.profileForm.passwordForm.newPassword}&confirmationPassword=${this.profileForm.passwordForm.confirmationPassword}`, {}, this.createRequestConfig());

                    this.profileForm.passwordForm.password = '';
                    this.profileForm.passwordForm.newPassword = '';
                    this.profileForm.passwordForm.confirmationPassword = '';

                    this.v$.profileForm.passwordForm.$reset();
                    
                    this.$toast.success(res.data, {position: 'bottom'});
                } catch (error) {
                    this.$toast.error(error.response.data.error, {position: 'bottom'});
                }
            }

            this.canInteract = true;
        },
        async deleteAccount() {
            if (!this.canInteract) return;
            this.canInteract = false;

            try {
                const config = this.createRequestConfig();
                
                await axios.post(`/api/v1/logout`, {}, config); 
    
                localStorage.removeItem('myoctober_backend_user_token');
                this.$emit('setIsLoggedIn', localStorage.getItem('myoctober_backend_user_token'));
    
                const res = await axios.post(`/api/v1/deleteUser`, {}, config);

                this.$toast.success(res.data, {position: 'bottom'});

                this.goTo('login');
            } catch (error) {
                this.$toast.error(error.response.data.error, {position: 'bottom'});

                this.canInteract = true;
            }
        },
        async logout() {
            if (!this.canInteract) return;
            this.canInteract = false;

            try {
                const res = await axios.post(`/api/v1/logout`, {}, this.createRequestConfig());
    
                this.$toast.success(res.data, {position: 'bottom'});
    
                localStorage.removeItem('myoctober_backend_user_token');
                this.$emit('setIsLoggedIn', localStorage.getItem('myoctober_backend_user_token'));
                this.goTo('login');
            } catch (error) {
                this.$toast.error(error.response.data.error, {position: 'bottom'});

                this.canInteract = true;
            }
        },

        getAccoundId() {
            const token = localStorage.getItem('myoctober_backend_user_token');
            const payload = token.split('.')[1];
            return JSON.parse(atob(payload)).user_id;
        },
        async setAccountData() {
            try {
                const accountId = this.getAccoundId()
                
                const { data: accountData } = await axios.post(`api/v1/getUser`, {}, this.createRequestConfig());
    
                this.accountId = accountId;
                
                this.profileForm.accountForm.name = accountData.name;
                this.profileForm.accountForm.email = accountData.email;
            } catch (error) {
                this.$toast.error(error.response.data.error, {position: 'bottom'});
            }
        },

        createRequestConfig() {
            return { headers: { Authorization: `Bearer ${localStorage.getItem('myoctober_backend_user_token')}` } };
        },

        goTo(path) {
            this.$router.replace({ path })
        },
    },

    async mounted() {
        await this.setAccountData();
        this.canInteract = true;
    },
}
</script>