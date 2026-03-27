<script setup>
import StoreLayoutShared from '@/shared/layouts/StoreLayout.vue';

defineProps({
    searchTerm: String,
});

const emit = defineEmits(['update:searchTerm']);
</script>

<template>
    <StoreLayoutShared :search-term="searchTerm" @update:search-term="emit('update:searchTerm', $event)">
        <slot />
    </StoreLayoutShared>
</template>
