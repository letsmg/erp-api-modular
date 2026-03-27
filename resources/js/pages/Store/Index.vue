<script setup>
import StoreHomePage from '@/modules/store/pages/StoreHomePage.vue';

defineProps({
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <StoreHomePage :initial-filters="initialFilters" />
</template>
