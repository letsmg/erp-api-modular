<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Save, ArrowLeft, Shield, Mail, Lock, User, Eye, EyeOff, UserCheck } from 'lucide-vue-next';

const props = defineProps({ user: Object });
const showPassword = ref(false);

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    access_level: props.user.access_level,
    is_active: props.user.is_active,
});

const submit = () => {
    form.put(route('users.update', props.user.id));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Editar Usuário" />

        <div class="max-w-3xl mx-auto pb-12">
            <div class="mb-6 flex items-center justify-between">
                <Link :href="route('users.index')" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center">
                    <ArrowLeft class="w-4 h-4 mr-1" /> Voltar
                </Link>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">ID: #{{ user.id }}</span>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nome Completo</label>
                            <div class="relative flex items-center">
                                <User class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input v-model="form.name" type="text" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500 transition" />
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">E-mail</label>
                            <div class="relative flex items-center">
                                <Mail class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input v-model="form.email" type="email" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <div class="p-4 bg-amber-50 rounded-lg mb-4 border border-amber-100">
                                <p class="text-xs text-amber-700 font-medium">Deixe os campos de senha em branco caso não deseje alterá-la.</p>
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nova Senha</label>
                            <div class="relative flex items-center">
                                <Lock class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" class="w-full pl-10 pr-10 rounded-lg border-gray-200 focus:ring-indigo-500" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 text-gray-400">
                                    <Eye v-if="!showPassword" class="w-4 h-4" />
                                    <EyeOff v-else class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Confirmar Nova Senha</label>
                            <div class="relative flex items-center">
                                <Lock class="absolute left-3 w-4 h-4 text-gray-400" />
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password_confirmation" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500" />
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

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                            <div class="relative flex items-center">
                                <UserCheck class="absolute left-3 w-4 h-4 text-gray-400" />
                                <select v-model="form.is_active" class="w-full pl-10 rounded-lg border-gray-200 text-sm">
                                    <option :value="true">Ativo</option>
                                    <option :value="false">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-10 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg">
                        <Save class="w-5 h-5" /> Atualizar Dados
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>