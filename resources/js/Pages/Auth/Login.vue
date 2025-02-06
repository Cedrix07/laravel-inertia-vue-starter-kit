<script setup>
    import Container from '../../Components/Container.vue';
    import Title from '../../Components/Title.vue';
    import TextLink from '../../Components/TextLink.vue';
    import InputField from '../../Components/InputField.vue';   
    import PrimaryBtn from '../../Components/PrimaryBtn.vue';
    import ErrorMessages from '../../Components/ErrorMessages.vue';
    import SessionMessages from '../../Components/SessionMessages.vue';
    import {useForm} from '@inertiajs/vue3';
    import Checkbox from '../../Components/Checkbox.vue';


    const form = useForm({
        email: '',
        password:'',
        remember: null
    });

    defineProps({
        status: String
    })

    const submit = ()=> { 
        form.post(route('login'),{
            // on finished submit, these fields will reset
            onFinish: () => form.reset('password'),
        });
    }

</script>

<template>
    <Head title="- Login"/>
    <Container class="w-1/2">
        <div class="mb-8 text-center">
            <Title>Login your account</Title>
            <p>Need an account? <TextLink routeName="register" label="Register"/> </p>
        </div>
        <!-- Error messages -->
        <ErrorMessages :errors="form.errors"/>
        <!-- Session messages -->
        <SessionMessages :status="status"/>
        <form @submit.prevent="submit" class="space-y-6">
            <InputField label="Email" icon="at" v-model="form.email"/>
            <InputField label="Password" type="password" icon="key" v-model="form.password"/>
            <div class="flex items-center justify-between">
                <Checkbox name="checkbox" v-model="form.remember">Remember me</Checkbox>
                <TextLink routeName="password.request" label="Forgot password?"/>
            </div>
            <PrimaryBtn :disabled="form.processing">Login</PrimaryBtn>
        </form>
    </Container>
</template>