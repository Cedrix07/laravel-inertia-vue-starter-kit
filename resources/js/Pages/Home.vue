<script setup>
import Card from '../Components/Card.vue';
import PaginationLinks from '../Components/PaginationLinks.vue';
import InputField from '../Components/InputField.vue';
import { router, useForm } from '@inertiajs/vue3';

const params = route().params;

const props = defineProps({
    listings: Object,
    searchTerm: String
});

// To get the name of the user id
const username = params.user_id ? props.listings.data.find(listing => listing.user_id === Number(params.user_id)).user.name : null;
const form = useForm({
    search: props.searchTerm
});

const search = () =>{
    router.get(route('home'), {
        search: form.search,
         user_id: params.user_id,
         tag: params.tag
    });
}


</script>

<template>
    <Head title="- Latest Listings" />
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <Link v-if="params.tag" :href="route('home', {... params, tag: null})"
                class="px-2 py-1 rounded-md bg-indigo-500 text-white flex items-center gap-2"
            >
                {{ params.tag }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
            <Link v-if="params.search" :href="route('home', {... params, search: null})"
                class="px-2 py-1 rounded-md bg-indigo-500 text-white flex items-center gap-2"
            >
                {{ params.search }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
            <Link v-if="params.user_id" :href="route('home', {... params, user_id: null})"
                class="px-2 py-1 rounded-md bg-indigo-500 text-white flex items-center gap-2"
            >
                {{ username }}
                <i class="fa-solid fa-xmark"></i>
            </Link>

        </div>

        <div class="w-1/4">
            <form @submit.prevent="search">
                <InputField
                    type="search"
                    label=""
                    icon="magnifying-glass"
                    placeholder="Search..."
                    v-model="form.search"
                />
            </form>
        </div>
    </div>
    <div v-if="Object.keys(listings.data).length">
        <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1  gap-4">
            <div v-for="listing in listings.data" :key="listing.id">
              <Card :listing="listing" />
            </div>
        </div>
        <div class="mt-8">
            <PaginationLinks :paginator="listings" />
        </div>
    </div>
    <div v-else>
        There are no Listings
    </div>
</template>
