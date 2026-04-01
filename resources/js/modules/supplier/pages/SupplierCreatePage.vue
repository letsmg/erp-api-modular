<script setup>
import AuthenticatedLayout from '@/shared/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Save, ArrowLeft, Building2, Phone, MapPin, Hash, Globe, User, XCircle, Mail, Map, Sparkles, X } from 'lucide-vue-next';
import { useSupplierForm } from '@/modules/supplier/composables/useSupplierForm';

const { form, states, loading, submit, handleCNPJ, handleCEP, filler, clearer } = useSupplierForm({ enableShortcuts: true });
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Novo Fornecedor" />
        <div v-if="loading" class="max-w-4xl mx-auto py-20 text-center text-sm font-bold uppercase tracking-widest text-gray-400">Carregando...</div>
        <div v-else class="max-w-4xl mx-auto pb-10">
            <div class="mb-6"><Link :href="route('suppliers.index')" class="text-sm font-bold text-pink-600 hover:text-pink-800 flex items-center transition"><ArrowLeft class="w-4 h-4 mr-1" /> Voltar</Link><h2 class="text-3xl font-black text-gray-800 tracking-tighter uppercase">Cadastrar Novo Fornecedor</h2></div>
            
            <!-- Atalhos -->
            <div class="mb-6 flex justify-center">
                <div class="inline-flex items-center gap-4 bg-slate-50 px-6 py-3 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-2">
                        <Sparkles class="w-4 h-4 text-pink-500" />
                        <span class="text-[11px] font-bold text-pink-600">CTRL+ALT+1</span>
                        <span class="text-[11px] text-gray-600">Popular</span>
                    </div>
                    <div class="w-px h-4 bg-gray-300"></div>
                    <div class="flex items-center gap-2">
                        <X class="w-4 h-4 text-red-500" />
                        <span class="text-[11px] font-bold text-red-600">CTRL+ALT+2</span>
                        <span class="text-[11px] text-gray-600">Limpar</span>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="mb-6 flex justify-center gap-4">
                <button type="button" @click="filler" class="bg-slate-600 hover:bg-slate-700 active:scale-95 active:shadow-lg text-white px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 transform cursor-pointer">
                    <Sparkles class="w-4 h-4" />
                    Popular Formulário
                </button>
                <button type="button" @click="clearer" class="bg-slate-600 hover:bg-slate-700 active:scale-95 active:shadow-lg text-white px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 transform cursor-pointer">
                    <X class="w-4 h-4" />
                    Limpar Formulário
                </button>
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

                <div class="flex items-center justify-between"><div class="italic text-[10px] text-gray-400 uppercase tracking-tighter">Atalhos: <span class="font-bold text-indigo-400 mx-1 underline">CTRL+ALT+1</span> Popular | <span class="font-bold text-red-400 mx-1 underline">CTRL+ALT+2</span> Limpar</div><button type="submit" :disabled="form.processing" class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 active:shadow-lg text-white px-12 py-4 rounded-xl font-bold flex items-center gap-2 transition-all duration-200 shadow-xl shadow-emerald-500/20 hover:shadow-xl disabled:opacity-50 disabled:scale-100 transform cursor-pointer"><Save class="w-5 h-5" /> {{ form.processing ? 'Cadastrando...' : 'Finalizar Cadastro' }}</button></div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
