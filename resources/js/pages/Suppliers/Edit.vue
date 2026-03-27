<script setup>
import SupplierEditPage from '@/modules/supplier/pages/SupplierEditPage.vue';

defineProps({
    supplierId: Number,
});
</script>

<template>
    <SupplierEditPage :supplier-id="supplierId" />
</template>
