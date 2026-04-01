import { computed, onMounted, onUnmounted, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { clearFormData, fillFormData } from '@/lib/utils';
import { getValidationErrors } from '@/lib/api/client';

function createBaseForm() {
    return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        access_level: 0,
        is_active: true,
        errors: {},
        processing: false,
    };
}

function applyUser(form, user) {
    form.name = user.name || '';
    form.email = user.email || '';
    form.password = '';
    form.password_confirmation = '';
    form.access_level = Number(user.access_level ?? 0);
    form.is_active = Boolean(user.is_active ?? true);
    form.errors = {};
}

export function useUserForm(options = {}) {
    const { userId = null, enableShortcuts = false } = options;
    const form = reactive(createBaseForm());
    form.data = () => {
        const { errors, processing, data, clearErrors, ...payload } = form;
        return payload;
    };
    form.clearErrors = () => {
        form.errors = {};
    };

    const loading = ref(Boolean(userId));
    const showPassword = ref(false);

    const submit = async () => {
        form.processing = true;
        form.errors = {};

        try {
            const payload = form.data();

            if (!payload.password) {
                delete payload.password;
                delete payload.password_confirmation;
            }

            if (userId) {
                await router.put(
                    route('users.update', userId),
                    payload,
                    {
                        onSuccess: () => {
                            router.visit(route('users.index'));
                        }
                    }
                );
            } else {
                await router.post(
                    route('users.store'),
                    payload,
                    {
                        onSuccess: () => {
                            router.visit(route('users.index'));
                        }
                    }
                );
            }
        } catch (error) {
            form.errors = getValidationErrors(error);
        } finally {
            form.processing = false;
        }
    };

    const filler = () => fillFormData(form);
    const clearer = () => {
        clearFormData(form);
        form.errors = {};
        form.access_level = 0;
        form.is_active = true;
    };

    onMounted(async () => {
        if (userId) {
            // Para edição, precisamos buscar os dados via API
            const { fetchUser } = await import('@/modules/user/services/user-api');
            const response = await fetchUser(userId);
            applyUser(form, response.data.data);
            loading.value = false;
        }

        if (enableShortcuts) {
            window.addEventListener('magic-fill', filler);
            window.addEventListener('magic-clear', clearer);
        }
    });

    onUnmounted(() => {
        if (enableShortcuts) {
            window.removeEventListener('magic-fill', filler);
            window.removeEventListener('magic-clear', clearer);
        }
    });

    const formTitle = computed(() => userId ? 'Editar Usuario' : 'Novo Usuario');

    return {
        form,
        loading,
        showPassword,
        formTitle,
        submit,
        filler,
        clearer,
    };
}

