<script setup>
    import Container from '../../Components/Container.vue';
    import Title from '../../Components/Title.vue';
    import InputField from '../../Components/InputField.vue';   
    import PrimaryBtn from '../../Components/PrimaryBtn.vue';
    import ErrorMessages from '../../Components/ErrorMessages.vue';

    import {useForm} from '@inertiajs/vue3';

    const props = defineProps({
        token: String,
        email: String
    })

    const form = useForm({
        email: props.email,
        password: '',
        password_confirmation: '',
        token: props.token
    });



    const submit = ()=> { 
        form.post(route('password.update'),{
            // on finished submit, these fields will reset
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    }

</script>

<template>
    <Head title="- Reset Password"/>
    <Container class="w-1/2">
        <div class="mb-8 text-center">
            <Title>Enter your new password</Title>
         
        </div>
        <!-- Error messages 13:42 -->
        <ErrorMessages :errors="form.errors"/>

        <form @submit.prevent="submit" class="space-y-6">
            <InputField label="Email" icon="at" v-model="form.email"/>
            <InputField label="Password" type="password" icon="key" v-model="form.password"/>
            <InputField label="Confirm password" type="password" icon="key" v-model="form.password_confirmation"/>
           
            <PrimaryBtn :disabled="form.processing">Reset Password</PrimaryBtn>
        </form>
    </Container>
</template> 