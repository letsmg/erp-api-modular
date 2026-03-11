<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Save, ArrowLeft, Building2, Phone, MapPin } from 'lucide-vue-next';

const form = useForm({
    company_name: '',
    cnpj: '',
    state_registration: '',
    address: '',
    neighborhood: '',
    city: '',
    zip_code: '',
    contact_name_1: '',
    phone_1: '',
});

// Máscara de CNPJ (00.000.000/0000-00)
const maskCNPJ = (e) => {
    let v = e.target.value.replace(/\D/g, '');
    v = v.replace(/^(\dt{2})(\dt)/, '$1.$2');
    v = v.replace(/^(\dt{2})\.(\dt{3})(\dt)/, '$1.$2.$3');
    v = v.replace(/\.(\dt{3})(\dt)/, '.$1/$2');
    v = v.replace(/(\dt{4})(\dt)/, '$1-$2');
    form.cnpj = v.substring(0, 18);
};

// Máscara de CEP (00000-000)
const maskCEP = (e) => {
    let v = e.target.value.replace(/\D/g, '');
    v = v.replace(/^(\dt{5})(\dt)/, '$1-$2');
    form.zip_code = v.substring(0, 9);
};

const submit = () => {
    form.post(route('suppliers.store'));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Novo Fornecedor" />

        <div class="max-w-4xl mx-auto pb-10">
            <div class="mb-6 flex items-center justify-between">
                <Link :href="route('suppliers.index')" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center transition">
                    <ArrowLeft class="w-4 h-4 mr-1" /> Voltar
                </Link>
                <h2 class="text-xl font-bold text-gray-800">Cadastrar Fornecedor</h2>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Razão Social</label>
                        <input v-model="form.company_name" type="text" class="w-full rounded-lg border-gray-200" required />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">CNPJ</label>
                        <input :value="form.cnpj" @input="maskCNPJ" type="text" class="w-full rounded-lg border-gray-200" placeholder="00.000.000/0000-00" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Inscrição Estadual</label>
                        <input v-model="form.state_registration" type="text" class="w-full rounded-lg border-gray-200" />
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Endereço</label>
                            <input v-model="form.address" type="text" class="w-full rounded-lg border-gray-200" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">CEP</label>
                            <input :value="form.zip_code" @input="maskCEP" type="text" class="w-full rounded-lg border-gray-200" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cidade</label>
                        <input v-model="form.city" type="text" class="w-full rounded-lg border-gray-200" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bairro</label>
                        <input v-model="form.neighborhood" type="text" class="w-full rounded-lg border-gray-200" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nome do Contato</label>
                        <input v-model="form.contact_name_1" type="text" class="w-full rounded-lg border-gray-200" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Telefone</label>
                        <input v-model="form.phone_1" type="text" class="w-full rounded-lg border-gray-200" />
                    </div>
                </div>

                <div class="flex justify-end italic text-xs text-gray-400 mb-2">
                    * Todos os campos são salvos conforme a sua migration original.
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg">
                        <Save class="w-5 h-5" /> Salvar Fornecedor
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>