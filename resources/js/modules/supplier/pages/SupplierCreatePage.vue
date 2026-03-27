<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Save, ArrowLeft, Building2, Phone, MapPin, Hash, Globe, User, XCircle, Mail, Map } from 'lucide-vue-next';
import { useSupplierForm } from '@/modules/supplier/composables/useSupplierForm';

const { form, states, loading, submit, handleCNPJ, handleCEP } = useSupplierForm({ enableShortcuts: true });
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Novo Fornecedor" />
        <div v-if="loading" class="max-w-4xl mx-auto py-20 text-center text-sm font-bold uppercase tracking-widest text-gray-400">Carregando...</div>
        <div v-else class="max-w-4xl mx-auto pb-10">
            <div class="mb-6 flex items-center justify-between">
                <Link :href="route('suppliers.index')" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center transition"><ArrowLeft class="w-4 h-4 mr-1" /> Voltar</Link>
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">Cadastrar Novo Fornecedor</h2>
            </div>

            <Transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="-translate-y-2 opacity-0" enter-to-class="translate-y-0 opacity-100">
                <div v-if="Object.keys(form.errors).length" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                    <div class="flex items-center mb-2"><XCircle class="w-5 h-5 text-red-500 mr-2" /><span class="text-sm font-black text-red-800 uppercase tracking-tighter">Ops! Verifique os campos:</span></div>
                    <ul class="list-disc list-inside"><li v-for="(error, field) in form.errors" :key="field" class="text-xs text-red-600 font-bold uppercase tracking-tight">{{ error }}</li></ul>
                </div>
            </Transition>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase mb-6 tracking-widest flex items-center gap-2"><Building2 class="w-4 h-4" /> Informacoes Juridicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Razao Social</label><div class="relative flex items-center"><Building2 class="absolute left-3 w-4 h-4 text-gray-400" /><input v-model="form.company_name" type="text" :class="{'border-red-500 bg-red-50': form.errors.company_name}" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500" required /></div></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">CNPJ</label><div class="relative flex items-center"><Hash class="absolute left-3 w-4 h-4 text-gray-400" /><input :value="form.cnpj" @input="handleCNPJ" type="text" :class="{'border-red-500 bg-red-50': form.errors.cnpj}" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500" placeholder="00.000.000/0000-00" required /></div></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Inscricao Estadual</label><div class="relative flex items-center"><Globe class="absolute left-3 w-4 h-4 text-gray-400" /><input v-model="form.state_registration" type="text" :class="{'border-red-500 bg-red-50': form.errors.state_registration}" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500" required /></div></div>
                    </div>
                </div>

                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase mb-6 tracking-widest flex items-center gap-2"><MapPin class="w-4 h-4" /> Endereco e Localizacao</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Logradouro</label><input v-model="form.address" type="text" :class="{'border-red-500 bg-red-50': form.errors.address}" class="w-full rounded-lg border-gray-200 focus:ring-indigo-500" required /></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">CEP</label><input :value="form.zip_code" @input="handleCEP" type="text" :class="{'border-red-500 bg-red-50': form.errors.zip_code}" class="w-full rounded-lg border-gray-200 focus:ring-indigo-500 text-center" placeholder="00000-000" required /></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bairro</label><input v-model="form.neighborhood" type="text" :class="{'border-red-500 bg-red-50': form.errors.neighborhood}" class="w-full rounded-lg border-gray-200 focus:ring-indigo-500" required /></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cidade</label><input v-model="form.city" type="text" :class="{'border-red-500 bg-red-50': form.errors.city}" class="w-full rounded-lg border-gray-200 focus:ring-indigo-500" required /></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Estado (UF)</label><div class="relative flex items-center"><Map class="absolute left-3 w-4 h-4 text-gray-400" /><select v-model="form.state" :class="{'border-red-500 bg-red-50': form.errors.state}" class="w-full pl-10 rounded-lg border-gray-200 focus:ring-indigo-500 appearance-none bg-white" required><option value="" disabled>Selecione...</option><option v-for="uf in states" :key="uf" :value="uf">{{ uf }}</option></select></div></div>
                    </div>
                </div>

                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase mb-6 tracking-widest flex items-center gap-2"><Phone class="w-4 h-4" /> Contatos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4 p-4 bg-indigo-50/30 rounded-lg border border-indigo-100">
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest">Contato Principal</label>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1">Nome</label><div class="relative flex items-center"><User class="absolute left-3 w-4 h-4 text-gray-400" /><input v-model="form.contact_name_1" type="text" class="w-full pl-10 border-gray-200 rounded-lg text-sm" required /></div></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1">Telefone</label><div class="relative flex items-center"><Phone class="absolute left-3 w-4 h-4 text-gray-400" /><input v-model="form.phone_1" type="text" class="w-full pl-10 border-gray-200 rounded-lg text-sm" required /></div></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1">E-mail</label><div class="relative flex items-center"><Mail class="absolute left-3 w-4 h-4 text-gray-400" /><input v-model="form.email" type="email" :class="{'border-red-500 bg-red-50': form.errors.email}" class="w-full pl-10 border-gray-200 rounded-lg text-sm" placeholder="exemplo@empresa.com" required /></div></div>
                        </div>
                        <div class="space-y-4 p-4 bg-gray-50 rounded-lg border border-gray-100 border-dashed">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Contato Secundario (Opcional)</label>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1">Nome</label><div class="relative flex items-center"><User class="absolute left-3 w-4 h-4 text-gray-300" /><input v-model="form.contact_name_2" type="text" class="w-full pl-10 border-gray-200 rounded-lg text-sm" /></div></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1">Telefone</label><div class="relative flex items-center"><Phone class="absolute left-3 w-4 h-4 text-gray-300" /><input v-model="form.phone_2" type="text" class="w-full pl-10 border-gray-200 rounded-lg text-sm" /></div></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between"><div class="italic text-[10px] text-gray-400 uppercase tracking-tighter">Atalhos: <span class="font-bold text-indigo-400 mx-1 underline">CTRL+SHIFT+P</span> Popular | <span class="font-bold text-red-400 mx-1 underline">CTRL+SHIFT+L</span> Limpar</div><button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-12 py-4 rounded-xl font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/20 disabled:opacity-50"><Save class="w-5 h-5" /> {{ form.processing ? 'Cadastrando...' : 'Finalizar Cadastro' }}</button></div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
