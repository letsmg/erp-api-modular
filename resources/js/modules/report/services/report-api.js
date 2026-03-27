export function buildProductReportUrl(params = {}) {
    const search = new URLSearchParams();

    if (params.type) {
        search.set('type', params.type);
    }

    if (params.supplier_id) {
        search.set('supplier_id', params.supplier_id);
    }

    const url = route('api.reports.products');
    const query = search.toString();

    return query ? `${url}?${query}` : url;
}
