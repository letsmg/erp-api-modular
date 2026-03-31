import { computed, ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { router } from '@inertiajs/vue3';
import { deleteUser, resetUserPassword, toggleUserStatus } from '@/modules/user/services/user-api';

export function useUserIndex(initialData = {}) {
    const search = ref(initialData.initialFilters?.search || '');
    const users = ref(initialData.users || []);
    const meta = ref(initialData.meta || { current_page: 1, last_page: 1, per_page: 12, total: 0 });
    const loading = ref(false);
    const processingId = ref(null);

    const loadUsers = async (page = 1) => {
        loading.value = true;

        try {
            const response = await router.get(
                route('users.index'),
                {
                    page,
                    search: search.value || undefined,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        users.value = page.props.users || [];
                        meta.value = page.props.meta || meta.value;
                    },
                }
            );
        } finally {
            loading.value = false;
        }
    };

    const debouncedLoad = debounce(() => loadUsers(1), 300);

    watch(search, (value) => {
        if (value.length > 2 || value.length === 0) {
            debouncedLoad();
        }
    });

    const handleToggleStatus = async (user) => {
        processingId.value = user.id;

        try {
            // Optimistic update - update immediately
            const currentUser = users.value.find(u => u.id === user.id);
            if (currentUser) {
                currentUser.is_active = !currentUser.is_active;
            }

            await router.patch(
                route('users.toggle', user.id),
                {},
                {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // Check for updated user in flash data
                        const updated = page.props.flash?.user;
                        if (updated) {
                            users.value = users.value.map((item) => item.id === user.id ? updated : item);
                        }
                    },
                    onError: () => {
                        // Revert optimistic update on error
                        if (currentUser) {
                            currentUser.is_active = !currentUser.is_active;
                        }
                    }
                }
            );
        } finally {
            processingId.value = null;
        }
    };

    const handleResetPassword = async (userId) => {
        processingId.value = userId;

        try {
            await router.patch(
                route('users.reset', userId),
                {},
                {
                    onSuccess: () => {
                        // Password reset successful
                    }
                }
            );
        } finally {
            processingId.value = null;
        }
    };

    const handleDelete = async (userId) => {
        processingId.value = userId;

        try {
            await router.delete(
                route('users.destroy', userId),
                {
                    onSuccess: () => {
                        if (users.value.length === 1 && meta.value.current_page > 1) {
                            loadUsers(meta.value.current_page - 1);
                            return;
                        }
                        loadUsers(meta.value.current_page);
                    }
                }
            );
        } finally {
            processingId.value = null;
        }
    };

    const userCount = computed(() => users.value.length);

    return {
        search,
        users,
        meta,
        loading,
        processingId,
        userCount,
        loadUsers,
        handleToggleStatus,
        handleResetPassword,
        handleDelete,
    };
}
