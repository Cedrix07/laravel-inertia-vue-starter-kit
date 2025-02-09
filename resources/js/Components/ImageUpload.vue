<script setup>
import {ref} from 'vue';

// custom event to pass it to the laravel backend
const emit = defineEmits(['image'])

const preview = ref(null);
const oversizedImage = ref(false);

const imageSelected = (e) => {
    preview.value = URL.createObjectURL(e.target.files[0]);
    oversizedImage.value = e.target.files[0].size > 3145728; // 3Mb
    emit('image', e.target.files[0]); // calling the custom event and pass the file to the backend
}

</script>
<template>
    <div>
        <span
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
            :class="{'!text-red-500' : oversizedImage}">
           {{ oversizedImage ? 'The selected image exceeds 3Mb' : 'Image (Max size 3Mb)' }}
        </span>
        <label
            for="image"
            class="block rounded-md mt-1 bg-slate-300 h-[140px] overflow-hidden cursor-pointer border-slate-300 border"
            :class="{'!border-red-500' : oversizedImage}">
            <img
                :src="preview ?? '/storage/images/listing/default.png'"
                alt="image"
                class="object-cover object-center h-full w-full">
        </label>

        <input @input="imageSelected" type="file" name="image" id="image" class="hidden">
    </div>
</template>

