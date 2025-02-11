<script setup>
import Container from '../../Components/Container.vue';
import Title from '../../Components/Title.vue';
import InputField from '../../Components/InputField.vue';
import TextArea from '../../Components/TextArea.vue';
import ImageUpload from '../../Components/ImageUpload.vue';
import ErrorMessages from '../../Components/ErrorMessages.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    desc: '',
    tags: '',
    email: '',
    link: '',
    image: '',
});

const submit = () =>{
    form.post(route('listing.store'));
}

</script>
<template>
   <Head title="- New Listing"/>
    <Container>
        <div class="mb-6">
            <Title>Create a new listing</Title>
        </div>
        <ErrorMessages :errors="form.errors"/>
        <form @submit.prevent="submit" class="grid grid-cols-2 gap-6">

            <div class="space-y-6">
                <InputField
                    label="Title"
                    icon="heading"
                    placeholder="My new listing"
                    v-model="form.title"
                />
                <InputField
                    label="tags (separated by commas)"
                    icon="tags"
                    placeholder="one, two, three"
                    v-model="form.tags"
                />
                <TextArea
                    label="Description"
                    icon="newspaper"
                    placeholder="Listing Description"
                    v-model="form.desc"
                />
            </div>
            <div class="space-y-6">
                <InputField
                    label="Email"
                    icon="at"
                    placeholder="example@gmail.com"
                    v-model="form.email"
                />
                <InputField
                    label="External Link"
                    icon="up-right-from-square"
                    placeholder="https://example.com"
                    v-model="form.link"
                />
                <ImageUpload @image="(e)=> form.image = e"/> <!-- Calling the custom event I created in the ImageUpload component -->
            </div>

            <div>
                <PrimaryBtn :disabled="form.processing">Create</PrimaryBtn>
            </div>
        </form>
    </Container>
</template>
