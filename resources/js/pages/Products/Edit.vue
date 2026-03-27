<script setup>
import ProductEditPage from '@/modules/product/pages/ProductEditPage.vue';

defineProps({
    productId: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <ProductEditPage :product-id="productId" />
</template>
