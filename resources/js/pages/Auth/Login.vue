<script setup>
import LoginPage from '@/modules/auth/pages/LoginPage.vue';

defineProps({
    errors: {
        type: Object,
        default: () => ({}),
    },
    userIp: String,
});
</script>

<template>
    <LoginPage :errors="errors" :user-ip="userIp" />
</template>
