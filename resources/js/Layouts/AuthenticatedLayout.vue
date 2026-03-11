<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    Package, 
    Truck, 
    ShoppingCart, 
    LogOut, 
    CheckCircle2,
    ChevronRight
} from 'lucide-vue-next';
import { ref, watch } from 'vue';

const page = usePage();
const user = page.props.auth.user;

// Lógica de Notificação (Toast) com Proteção "Optional Chaining"
const showToast = ref(false);
const toastMessage = ref('');

watch(() => page.props.flash?.message, (newMessage) => {
    if (newMessage) {
        toastMessage.value = newMessage;
        showToast.value = true;
        
        // Timer para fechar o toast automaticamente
        setTimeout(() => {
            showToast.value = false;
        }, 4000);
    }
}, { immediate: true });

// Função auxiliar para marcar link ativo
const isUrl = (url) => {
    return page.url === url || page.url.startsWith(url + '/');
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <aside class="w-64 bg-slate-900 text-white hidden md:flex flex-col fixed h-full shadow-xl">
            <div class="p-6 text-2xl font-bold border-b border-slate-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center text-sm">Z</div>
                ERP <span class="text-blue-400">Zenite</span>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2">Principal</p>
                
                <Link :href="route('dashboard')" 
                    :class="[isUrl('/dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white']"
                    class="flex items-center justify-between p-3 rounded-lg transition group">
                    <div class="flex items-center space-x-3">
                        <LayoutDashboard class="w-5 h-5" />
                        <span class="font-medium text-sm">Dashboard</span>
                    </div>
                    <ChevronRight v-if="isUrl('/dashboard')" class="w-4 h-4" />
                </Link>

                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2 mt-6">Cadastros</p>

                <Link :href="route('users.index')" 
                    :class="[isUrl('/users') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white']"
                    class="flex items-center justify-between p-3 rounded-lg transition group">
                    <div class="flex items-center space-x-3">
                        <Users class="w-5 h-5" />
                        <span class="font-medium text-sm">Usuários</span>
                    </div>
                </Link>

                <Link href="/suppliers" 
                    :class="[isUrl('/suppliers') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white']"
                    class="flex items-center justify-between p-3 rounded-lg transition group">
                    <div class="flex items-center space-x-3">
                        <Truck class="w-5 h-5" />
                        <span class="font-medium text-sm">Fornecedores</span>
                    </div>
                </Link>

                <Link href="/products" 
                    :class="[isUrl('/products') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white']"
                    class="flex items-center justify-between p-3 rounded-lg transition group">
                    <div class="flex items-center space-x-3">
                        <Package class="w-5 h-5" />
                        <span class="font-medium text-sm">Produtos</span>
                    </div>
                </Link>

                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2 mt-6">Operacional</p>

                <Link href="/sales" 
                    :class="[isUrl('/sales') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white']"
                    class="flex items-center justify-between p-3 rounded-lg transition group">
                    <div class="flex items-center space-x-3">
                        <ShoppingCart class="w-5 h-5" />
                        <span class="font-medium text-sm">Vendas</span>
                    </div>
                </Link>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <Link :href="route('logout')" method="post" as="button" 
                    class="flex items-center space-x-3 p-3 w-full text-red-400 hover:bg-red-500/10 rounded-lg transition text-left group">
                    <LogOut class="w-5 h-5 group-hover:scale-110 transition" />
                    <span class="font-medium text-sm">Sair do Sistema</span>
                </Link>
            </div>
        </aside>

        <div class="flex-1 flex flex-col md:ml-64">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 sticky top-0 z-10 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <h1 class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                        {{ $page.component.includes('/') ? $page.component.split('/')[0] : 'Sistema' }}
                    </h1>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block border-r pr-4 border-gray-100">
                        <p class="text-sm font-bold text-gray-900 leading-none">{{ user.name }}</p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-tighter">
                            {{ user.access_level === 1 ? 'Administrador' : 'Operador' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center rounded-full font-bold shadow-md">
                        {{ user.name[0] }}
                    </div>
                </div>
            </header>

            <main class="p-8 flex-1">
                <slot />
            </main>

            <footer class="py-4 px-8 bg-white border-t text-center text-[11px] text-gray-400 font-bold uppercase tracking-widest">
                &copy; 2026 ERP Zenite - Tecnologia em Gestão
            </footer>
        </div>

        <Transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="showToast" class="fixed top-6 right-6 z-[100] flex items-center bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl border-l-4 border-green-500">
                <div class="bg-green-500/20 p-2 rounded-full mr-4 text-green-500">
                    <CheckCircle2 class="w-5 h-5" />
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Sucesso</p>
                    <p class="text-sm font-medium">{{ toastMessage }}</p>
                </div>
            </div>
        </Transition>
    </div>
</template>