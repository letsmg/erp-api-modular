<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Truck, Plus, Trash2, Phone, MapPin, UserCheck } from 'lucide-vue-next';

defineProps({ 
    suppliers: Array 
});

const handleDelete = (supplier) => {
    if (confirm(`Deseja excluir permanentemente o fornecedor ${supplier.company_name}?`)) {
        router.delete(route('suppliers.destroy', supplier.id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Fornecedores" />

        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Fornecedores</h2>
                <p class="mt-1 text-sm text-gray-500">Gestão de parceiros e origens de suprimentos.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <Link :href="route('suppliers.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-md">
                    <Plus class="w-4 h-4 mr-2" /> Novo Fornecedor
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="supplier in suppliers" :key="supplier.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                            <Truck class="w-6 h-6" />
                        </div>
                        <button @click="handleDelete(supplier)" class="text-gray-300 hover:text-red-600 transition p-1">
                            <Trash2 class="w-5 h-5" />
                        </button>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 truncate" :title="supplier.company_name">
                        {{ supplier.company_name }}
                    </h3>
                    <div class="flex flex-col mt-1">
                        <span class="text-xs font-bold text-gray-400">CNPJ: {{ supplier.cnpj }}</span>
                        <span class="text-xs font-medium text-gray-400">IE: {{ supplier.state_registration }}</span>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-start text-sm text-gray-600">
                            <MapPin class="w-4 h-4 mr-2 text-indigo-500 flex-shrink-0 mt-0.5" />
                            <span class="truncate">{{ supplier.city }} - {{ supplier.neighborhood }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <UserCheck class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" />
                            <span class="font-medium">{{ supplier.contact_name_1 }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
                            <Phone class="w-4 h-4 mr-2 text-gray-400" />
                            <span class="font-bold text-indigo-600">{{ supplier.phone_1 }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">CEP: {{ supplier.zip_code }}</span>
                    <Link :href="route('suppliers.edit', supplier.id)" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        Ver Detalhes
                    </Link>
                </div>
            </div>
        </div>

        <div v-if="suppliers.length === 0" class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-100">
            <div class="bg-gray-50 p-4 rounded-full mb-4">
                <Truck class="w-12 h-12 text-gray-200" />
            </div>
            <p class="text-gray-400 font-medium">Nenhum fornecedor encontrado.</p>
        </div>
    </AuthenticatedLayout>
</template>