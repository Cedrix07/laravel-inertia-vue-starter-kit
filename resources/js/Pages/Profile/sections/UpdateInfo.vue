<script setup>
import Container from '../../../Components/Container.vue';
import Title from '../../../Components/Title.vue';
import InputField from '../../../Components/InputField.vue';
import PrimaryBtn from '../../../Components/PrimaryBtn.vue';
import ErrorMessages from '../../../Components/ErrorMessages.vue';
import SessionMessages from '../../../Components/SessionMessages.vue';
import { router, useForm } from '@inertiajs/vue3';

const props =defineProps({
        user: Object,
        status: String
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const submit = () => {
    form.patch(route('profile.info'));
}

const resendEmail = (e) =>{
    router.post(
        route('verification.send'),
        {},
        {
            onStart: () => e.target.disabled = true,
            onFinish: () => e.target.disabled = false,
        });
}
</script>

<template>
    <Container class="mb-6">
        <div class="mb-6">
            <Title>Update Information</Title>
            <p>Update your account's profile information and email address</p>
        </div>
        <ErrorMessages :errors="form.errors"/>
        <form @submit.prevent="submit" class="space-y-6">
            <InputField
                label="name"
                icon="id-badge"
                class="w-1/2"
                v-model="form.name"
            />
            <InputField
                label="email"
                icon="at"
                class="w-1/2"
                v-model="form.email"
            />
            <SessionMessages :status="status"/>
            <div v-if="user.email_verified_at === null" class="flex items-center gap-1">
                <p>Your email address is unverified.</p>

                <button class="text-indigo-500 font-medium underline dark:text-indigo-400 disabled:text-slate-400 disabled:cursor-wait
                " @click="resendEmail">Click here to re-send the verification email.</button>
            </div>
            <PrimaryBtn :disabled="form.processing">Save</PrimaryBtn>
        </form>
    </Container>
</template>
