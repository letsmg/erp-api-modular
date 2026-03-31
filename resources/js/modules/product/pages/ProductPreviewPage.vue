<script setup>
import AuthenticatedLayout from '@/shared/layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ArrowLeft, Eye, Star, Package, Truck, DollarSign, Loader2, EyeOff, LayoutDashboard } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const isUpdating = ref(false);
const isAdmin = computed(() => page.props.auth?.user?.access_level === 1);

const toggleStatus = () => {
    if (!props.product || !route().has('products.toggle')) return;
    isUpdating.value = true;
    router.patch(route('products.toggle', props.product.id), {}, { 
        preserveScroll: true, 
        onFinish: () => isUpdating.value = false 
    });
};

const formatCurrency = (value) => {
    return Number(value || 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
};

const getImageUrl = (path) => {
    if (!path) return null;
    const cleanPath = path.startsWith('products/') ? path : `products/${path}`;
    return `/storage/${cleanPath}`;
};

const handleImageError = (event) => {
    console.error('Image failed to load:', event.target.src);
    event.target.src = 'https://placehold.co/600x400?text=Image+Not+Found';
};

const handleImageLoad = (event) => {
    console.log('Image loaded successfully:', event.target.src);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Preview: ${product.description}`" />

        <!-- Admin Bar -->
        <div v-if="isAdmin" class="bg-indigo-600 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                        <LayoutDashboard class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.2em] leading-none">Visualização de Admin</p>
                        <p class="text-[9px] font-medium opacity-80 uppercase tracking-widest mt-1">Aprovação e Status do Produto</p>
                    </div>
                </div>
                <button @click="toggleStatus" :disabled="isUpdating" class="bg-white text-indigo-600 px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg hover:bg-indigo-50">
                    <Loader2 v-if="isUpdating" class="w-3 h-3 animate-spin" />
                    <component v-else :is="product.is_active ? EyeOff : Eye" class="w-3.5 h-3.5" />
                    {{ product.is_active ? 'Bloquear Produto' : 'Aprovar Produto' }}
                </button>
            </div>
        </div>

        <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <Link :href="route('products.index')" class="flex items-center text-[10px] font-black uppercase text-gray-400 hover:text-indigo-600 transition mb-2 tracking-widest">
                        <ArrowLeft class="w-3 h-3 mr-1" /> Voltar ao estoque
                    </Link>
                    <h1 class="text-3xl font-black text-gray-800 tracking-tighter uppercase">Preview do Produto</h1>
                    <p class="text-gray-500 text-sm font-medium mt-1">Visualização dos dados do produto</p>
                </div>
                
                <div class="flex gap-3">
                    <Link :href="route('products.edit', product.id)" class="bg-indigo-600 text-white px-4 py-2 rounded-xl flex items-center gap-2 hover:bg-indigo-700 transition-all shadow-lg font-bold uppercase text-xs tracking-widest">
                        <Eye class="w-4 h-4" />
                        Editar Produto
                    </Link>
                </div>
            </div>

            <!-- Product Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info -->
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                            <Package class="w-5 h-5 text-indigo-600" />
                            Informações Básicas
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Descrição</label>
                                <p class="font-bold text-gray-800">{{ product.description }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Marca</label>
                                <p class="font-bold text-gray-800">{{ product.brand || '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Modelo</label>
                                <p class="font-bold text-gray-800">{{ product.model || '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Categoria</label>
                                <p class="font-bold text-gray-800">{{ product.category?.name || '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Fornecedor</label>
                                <p class="font-bold text-gray-800">{{ product.supplier?.company_name || '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Código de Barras</label>
                                <p class="font-bold text-gray-800">{{ product.barcode || '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Estoque</label>
                                <p class="font-bold" :class="product.stock_quantity > 10 ? 'text-green-600' : 'text-red-600'">
                                    {{ product.stock_quantity }} unidades
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Images - Copiado do layout da store -->
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                            <Package class="w-5 h-5 text-indigo-600" />
                            Imagens do Produto
                        </h2>
                        
                        <div v-if="product.images && product.images.length > 0" class="space-y-6">
                            <!-- Imagem principal -->
                            <div class="relative aspect-square bg-white rounded-[2.5rem] overflow-hidden flex items-center justify-center border border-gray-100 shadow-2xl shadow-gray-200/40 group">
                                <img 
                                    :src="getImageUrl(product.images[0].path)" 
                                    :alt="product.description"
                                    class="object-contain w-full h-full p-8 transition-all duration-700 animate-in fade-in zoom-in-95"
                                    @error="handleImageError"
                                    @load="handleImageLoad"
                                />
                            </div>
                            
                            <!-- Galeria de miniaturas -->
                            <div v-if="product.images.length > 1" class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide justify-center">
                                <div v-for="img in product.images" :key="img.id" class="w-16 h-16 shrink-0 rounded-2xl border-2 overflow-hidden bg-white p-1 transition-all cursor-pointer">
                                    <img :src="getImageUrl(img.path)" :alt="product.description" class="w-full h-full object-cover rounded-xl" />
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-center py-12 text-gray-400">
                            <Package class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                            <p class="font-black uppercase text-xs tracking-widest">Nenhuma imagem</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                            <Star class="w-5 h-5 text-indigo-600" />
                            Status
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Ativo</span>
                                <span :class="product.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                    {{ product.is_active ? 'Sim' : 'Não' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Destaque</span>
                                <span :class="product.is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                    {{ product.is_featured ? 'Sim' : 'Não' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                            <DollarSign class="w-5 h-5 text-indigo-600" />
                            Preços
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Preço de Custo</label>
                                <p class="font-bold text-gray-800">{{ formatCurrency(product.cost_price) }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Preço de Venda</label>
                                <p class="font-bold text-lg text-green-600">{{ formatCurrency(product.sale_price) }}</p>
                            </div>
                            
                            <div v-if="product.promo_price">
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Preço Promocional</label>
                                <p class="font-bold text-lg text-red-600">{{ formatCurrency(product.promo_price) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dimensions -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                            <Truck class="w-5 h-5 text-indigo-600" />
                            Dimensões
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Peso</span>
                                <span class="font-bold">{{ product.weight || '-' }} kg</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Largura</span>
                                <span class="font-bold">{{ product.width || '-' }} cm</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Altura</span>
                                <span class="font-bold">{{ product.height || '-' }} cm</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Comprimento</span>
                                <span class="font-bold">{{ product.length || '-' }} cm</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Frete Grátis</span>
                                <span :class="product.free_shipping ? 'text-green-600' : 'text-gray-400'" class="font-bold">
                                    {{ product.free_shipping ? 'Sim' : 'Não' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
img { backface-visibility: hidden; transform: translateZ(0); }
h1, h2, h3 { letter-spacing: -0.05em; }
</style>
