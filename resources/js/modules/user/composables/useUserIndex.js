import { computed, onMounted, ref } from 'vue';
import { deleteUser, fetchUsers, resetUserPassword, toggleUserStatus } from '@/modules/user/services/user-api';

export function useUserIndex() {
    const users = ref([]);
    const loading = ref(true);
    const processingId = ref(null);

    const loadUsers = async () => {
        loading.value = true;

        try {
            const response = await fetchUsers();
            users.value = response.data.data ?? [];
        } finally {
            loading.value = false;
        }
    };

    const handleToggleStatus = async (user) => {
        processingId.value = user.id;

        try {
            const response = await toggleUserStatus(user.id);
            const updated = response.data.data;
            users.value = users.value.map((item) => item.id === user.id ? updated : item);
        } finally {
            processingId.value = null;
        }
    };

    const handleResetPassword = async (userId) => {
        processingId.value = userId;

        try {
            await resetUserPassword(userId);
        } finally {
            processingId.value = null;
        }
    };

    const handleDelete = async (userId) => {
        processingId.value = userId;

        try {
            await deleteUser(userId);
            await loadUsers();
        } finally {
            processingId.value = null;
        }
    };

    const userCount = computed(() => users.value.length);

    onMounted(() => {
        loadUsers();
    });

    return {
        users,
        loading,
        processingId,
        userCount,
        loadUsers,
        handleToggleStatus,
        handleResetPassword,
        handleDelete,
    };
}
