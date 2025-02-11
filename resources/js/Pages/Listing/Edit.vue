<script setup>
import Container from '../../Components/Container.vue';
import Title from '../../Components/Title.vue';
import InputField from '../../Components/InputField.vue';
import TextArea from '../../Components/TextArea.vue';
import ImageUpload from '../../Components/ImageUpload.vue';
import ErrorMessages from '../../Components/ErrorMessages.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import { useForm } from '@inertiajs/vue3';
import { list } from 'postcss';

const props = defineProps({
    listing: Object
});

const form = useForm({
    title: props.listing.title,
    desc: props.listing.desc,
    tags: props.listing.tags,
    email: props.listing.email,
    link: props.listing.link,
    image: null,
    _method: 'PUT'
});

const submit = () =>{
    form.post(route('listing.update', props.listing.id)); // will override the method to put _method: 'PUT'
}

</script>
<template>
   <Head title="- Edit Listing"/>
    <Container>
        <div class="mb-6">
            <Title>Edit your listing</Title>
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

                <!-- Calling the custom event I created in the ImageUpload component -->
                <ImageUpload @image="(e)=> form.image = e"
                    :listingImage="listing.image"
                />
            </div>

            <div>
                <PrimaryBtn :disabled="form.processing">Update</PrimaryBtn>
            </div>
        </form>
    </Container>
</template>

defineProps({

})
