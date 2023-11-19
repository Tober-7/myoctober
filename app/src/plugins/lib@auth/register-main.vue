<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center w-2/5 p-8 bg-[#070707] border-2 rounded-3xl border-neutral-300">
        </div>
    </div>
</template>

<script>
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
            registerNewPassword: null,
            registerConfirmationPassword: null,

            message_required: 'This field is required',
            message_email: 'This field must be a valid e-mail',
            message_length: 'This field must be at least 8 characters long',
            message_samePassword: 'Confirmation password must be the same as new password',
        }
    },
    validations () {
        return {
            registerName: {required},
            registerEmail: {required, email},
            registerNewPassword: {required, minLength: minLength(8)},
            registerConfirmationPassword: {required, minLength: minLength(8), sameAs: sameAs(this.registerPassword)},
        }
    },
    methods: {
        login() {
            this.v$.$touch();
        },
        register() {

        },
    }
}
</script>