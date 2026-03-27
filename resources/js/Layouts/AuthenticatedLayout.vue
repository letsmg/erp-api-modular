<script setup>
import AuthenticatedLayoutShared from '@/shared/layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayoutShared>
        <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
            <slot :name="slotName" v-bind="slotProps" />
        </template>
    </AuthenticatedLayoutShared>
</template>
