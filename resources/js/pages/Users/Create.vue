<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { UserPlus, Save, ArrowLeft, Shield, Mail, Lock, User, Eye, EyeOff } from 'lucide-vue-next';
import { onMounted, onUnmounted } from 'vue';
import { fillFormData, clearFormData } from '@/lib/utils';

const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    access_level: 0,
    is_active: true,
});

// --- 1. DEFINA AS FUNÇÕES PRIMEIRO ---
const filler = () => fillFormData(form);
const clearer = () => clearFormData(form);

const submit = () => {
    form.post(route('users.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
onMounted(() => {
    window.addEventListener('magic-fill', filler);
    window.addEventListener('magic-clear', clearer); // Escuta o limpar
});

onUnmounted(() => {
    window.removeEventListener('magic-fill', filler);
    window.removeEventListener('magic-clear', clearer);
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Novo Usuário" />

        <div class="max-w-3xl mx-auto pb-12">
            <div class="mb-6">
                <Link :href="route('users.index')" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center">
                    <ArrowLeft class="w-4 h-4 mr-1" /> Voltar
                </Link>
                <h2 class="text-2xl font-bold text-gray-900 mt-2">Cadastrar Novo Usuário</h2>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nome Completo</label>
                            <div class="relative flex items-center">
                                <User class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input v-model="form.name" type="text" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500 transition" placeholder="Nome do usuário" required />
                            </div>
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">E-mail</label>
                            <div class="relative flex items-center">
                                <Mail class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input v-model="form.email" type="email" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500" required />
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Senha</label>
                            <div class="relative flex items-center">
                                <Lock class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" class="w-full pl-10 pr-10 rounded-lg border-gray-200 focus:ring-indigo-500" required />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 text-gray-400 hover:text-indigo-600">
                                    <Eye v-if="!showPassword" class="w-4 h-4" />
                                    <EyeOff v-else class="w-4 h-4" />
                                </button>
                            </div>
                            <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Confirmar Senha</label>
                            <div class="relative flex items-center">
                                <Lock class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password_confirmation" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500" required />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nível de Acesso</label>
                            <div class="relative flex items-center">
                                <Shield class="absolute left-3 w-4 h-4 text-gray-400" />
                                <select v-model="form.access_level" class="w-full pl-10 rounded-lg border-gray-200 text-sm">
                                    <option :value="0">Usuário Padrão</option>
                                    <option :value="1">Administrador</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-8 py-2 rounded-lg font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg">
                        <Save class="w-4 h-4" /> Salvar Usuário
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>