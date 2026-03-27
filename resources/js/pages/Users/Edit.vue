<script setup>
import UserEditPage from '@/modules/user/pages/UserEditPage.vue';

defineProps({
    userId: Number,
});
</script>

<template>
    <UserEditPage :user-id="userId" />
</template>
