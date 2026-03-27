<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { getValidationErrors } from '@/lib/api/client';
import { forgotPassword } from '@/modules/auth/services/auth-api';

const status = ref('');
const form = ref({
    email: '',
    processing: false,
    errors: {},
});

const submit = async () => {
    form.value.processing = true;
    form.value.errors = {};

    try {
        await forgotPassword({ email: form.value.email });
        status.value = 'Link enviado com sucesso!';
    } catch (error) {
        form.value.errors = getValidationErrors(error);
    } finally {
        form.value.processing = false;
    }
};
</script>

<template>
    <Head title="Esqueci minha senha" />
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recuperar Senha</h2>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <label class="block font-medium text-sm text-gray-700">E-mail</label>
                    <input type="email" v-model="form.email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required autofocus>
                    <div v-if="form.errors.email" class="text-red-600 text-sm mt-2">{{ form.errors.email }}</div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <Link :href="route('login')" class="text-sm text-gray-600 hover:text-gray-900 underline decoration-gray-400 underline-offset-4">
                        Voltar para o Login
                    </Link>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.processing ? 'Enviando...' : 'Enviar Link' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
