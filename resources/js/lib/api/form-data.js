function isFileLike(value) {
    return value instanceof File || value instanceof Blob;
}

function appendValue(formData, key, value) {
    if (value === null || value === undefined) {
        formData.append(key, '');
        return;
    }

    if (isFileLike(value)) {
        formData.append(key, value);
        return;
    }

    if (Array.isArray(value)) {
        value.forEach((item, index) => appendValue(formData, `${key}[${index}]`, item));
        return;
    }

    if (value instanceof Date) {
        formData.append(key, value.toISOString());
        return;
    }

    if (typeof value === 'object') {
        Object.entries(value).forEach(([nestedKey, nestedValue]) => {
            appendValue(formData, `${key}[${nestedKey}]`, nestedValue);
        });
        return;
    }

    if (typeof value === 'boolean') {
        formData.append(key, value ? '1' : '0');
        return;
    }

    formData.append(key, String(value));
}

export function toFormData(payload) {
    const formData = new FormData();

    Object.entries(payload).forEach(([key, value]) => appendValue(formData, key, value));

    return formData;
}
