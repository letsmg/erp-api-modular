<script setup>
import ProductIndexPage from '@/modules/product/pages/ProductIndexPage.vue';

defineProps({
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <ProductIndexPage :initial-filters="initialFilters" />
</template>
