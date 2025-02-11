<script setup>
    import DeleteAccount from './sections/DeleteAccount.vue';
import UpdateInfo from './sections/UpdateInfo.vue';
    import UpdatePassword from './sections/UpdatePassword.vue';
    defineProps({
        user: Object,
        status: String
    })
</script>
<template>
    <Head title="- Profile" />
    <UpdateInfo :user="user" :status="status"/>
    <UpdatePassword />
    <DeleteAccount />
</template>

