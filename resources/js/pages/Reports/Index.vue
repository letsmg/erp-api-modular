<script setup>
import ReportIndexPage from '@/modules/report/pages/ReportIndexPage.vue';

defineProps({
    suppliers: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <ReportIndexPage :suppliers="suppliers" />
</template>
