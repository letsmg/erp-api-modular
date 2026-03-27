import { ref } from 'vue';
import { buildProductReportUrl } from '@/modules/report/services/report-api';

export function useProductReport() {
    const form = ref({
        type: 'sintetico',
        supplier_id: '',
    });

    const generateReport = () => {
        const url = buildProductReportUrl(form.value);
        window.open(url, '_blank');
    };

    return {
        form,
        generateReport,
    };
}
