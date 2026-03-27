<script setup>
import StoreProductPage from '@/modules/store/pages/StoreProductPage.vue';

defineProps({
    productSlug: String,
});
</script>

<template>
    <StoreProductPage :product-slug="productSlug" />
</template>
