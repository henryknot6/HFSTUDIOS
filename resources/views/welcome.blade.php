<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HFSTUDIOS - Acceso Exclusivo</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
        }
        
        [x-cloak] { display: none !important; }
        
        html { scroll-behavior: smooth; }
        
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        
        .marquee-content {
            animation: marquee 20s linear infinite;
        }
        
        .product-card:hover .hover-overlay {
            opacity: 1;
            transform: translateY(0);
        }
        
        .hover-overlay {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .grayscale-map {
            filter: grayscale(100%);
        }
    </style>
</head>
<body class="bg-white antialiased" x-data="app()" x-init="init()">
    
    <div 
        x-show="toast.show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="fixed top-6 right-6 z-[90] bg-black text-white px-6 py-4 shadow-2xl max-w-sm"
        x-cloak>
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="font-semibold text-sm" x-text="toast.message"></p>
        </div>
    </div>
    
    <div 
        x-show="productDetailOpen"
        x-cloak
        class="fixed inset-0 z-[85] overflow-y-auto">
        
        <div 
            @click="productDetailOpen = false"
            x-show="productDetailOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80"></div>
        
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div 
                x-show="productDetailOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.stop
                class="relative bg-white max-w-6xl w-full grid md:grid-cols-2 gap-0 shadow-2xl">
                
                <button 
                    @click="productDetailOpen = false"
                    class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center bg-white/90 hover:bg-white rounded-full text-black font-bold text-2xl shadow-lg">
                    ×
                </button>
                
                <div class="bg-[#F4F4F4] flex items-center justify-center p-8 min-h-[500px]">
                    <img 
                        :src="selectedProduct.image" 
                        :alt="selectedProduct.name"
                        class="w-full h-auto object-cover max-h-[600px]">
                </div>
                
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <span 
                        x-show="selectedProduct.badge"
                        :class="selectedProduct.badge && selectedProduct.badge.includes('PROMO') ? 'bg-black text-white' : 'bg-white text-black'"
                        class="inline-block text-[9px] font-bold uppercase tracking-wider px-2 py-1 mb-4 w-fit">
                        <span x-text="selectedProduct.badge"></span>
                    </span>
                    
                    <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight mb-3" x-text="selectedProduct.name"></h2>
                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-4" x-text="selectedProduct.color"></p>
                    
                    <div class="flex items-end gap-3 mb-6">
                        <p class="text-4xl font-black text-red-600" x-text="'$' + selectedProduct.price + ' MXN'"></p>
                        <template x-if="selectedProduct.originalPrice">
                            <p class="text-lg font-bold text-gray-400 line-through mb-1" x-text="'$' + selectedProduct.originalPrice + ' MXN'"></p>
                        </template>
                    </div>
                    
                    <p class="text-sm text-gray-700 leading-relaxed mb-8" x-text="selectedProduct.description"></p>
                    
                    <div class="space-y-4">
                        <button 
                            @click="addToCart(selectedProduct); productDetailOpen = false"
                            class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                            Agregar al Carrito
                        </button>
                        
                        <button 
                            @click="productDetailOpen = false"
                            class="w-full border-2 border-black text-black py-4 text-sm font-bold uppercase tracking-widest hover:bg-black hover:text-white transition">
                            Regresar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div 
        x-show="orderSuccessOpen"
        x-cloak
        class="fixed inset-0 z-[80] flex items-center justify-center p-4">
        
        <div 
            @click="orderSuccessOpen = false"
            x-show="orderSuccessOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/70"></div>
        
        <div 
            x-show="orderSuccessOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white max-w-md w-full p-12 text-center shadow-2xl">
            
            <div class="w-24 h-24 mx-auto mb-6 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-3xl font-black uppercase tracking-tight mb-3">¡Compra Confirmada!</h2>
            <p class="text-gray-600 mb-2">Gracias por tu compra</p>
            <p class="text-sm font-mono bg-gray-100 inline-block px-4 py-2 mb-8" x-text="orderNumber"></p>
            
            <button 
                @click="orderSuccessOpen = false; navigateTo('catalogo')"
                class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                Continuar Comprando
            </button>
        </div>
    </div>
    
    <div x-show="currentRoute !== 'inicio' && currentRoute !== 'admin'" class="bg-[#F0EBE0] overflow-hidden relative h-6">
        <div class="flex whitespace-nowrap marquee-content">
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                VENTA SS26: 20% DE DESCUENTO EN TODA LA TIENDA | © HF STUDIOS, 2026
            </span>
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                VENTA SS26: 20% DE DESCUENTO EN TODA LA TIENDA | © HF STUDIOS, 2026
            </span>
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                VENTA SS26: 20% DE DESCUENTO EN TODA LA TIENDA | © HF STUDIOS, 2026
            </span>
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                VENTA SS26: 20% DE DESCUENTO EN TODA LA TIENDA | © HF STUDIOS, 2026
            </span>
        </div>
    </div>
    
    <nav x-show="currentRoute !== 'inicio' && currentRoute !== 'admin'" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-screen-2xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="hidden md:flex items-center gap-8">
                    <button @click="navigateTo('catalogo')" class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">Catálogo</button>
                    <button @click="navigateTo('nosotros')" class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">Nosotros</button>
                    <button @click="navigateTo('contacto')" class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">Contacto</button>
                </div>
                
                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <button @click="navigateTo('catalogo')" class="text-2xl md:text-3xl font-black tracking-tighter">HFSTUDIOS</button>
                </div>
                
                <div class="hidden md:flex items-center gap-6">
                    <button 
                        x-show="!isLoggedIn"
                        @click="navigateTo('login')"
                        class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">
                        Iniciar sesión
                    </button>
                    
                    <button 
                        x-show="isLoggedIn && !isAdmin"
                        @click="navigateTo('perfil')"
                        class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">
                        Perfil
                    </button>
                    
                    <button 
                        x-show="isLoggedIn && isAdmin"
                        @click="navigateTo('admin')"
                        class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">
                        Admin Panel
                    </button>
                    
                    <button 
                        @click="cartOpen = true" 
                        class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition relative">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Carrito (<span x-text="cartCount"></span>)
                        <span x-show="cartCount > 0" class="absolute -top-1 -right-1 w-2 h-2 bg-black rounded-full animate-pulse"></span>
                    </button>
                </div>
                
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-sm font-bold">
                    <span x-show="!mobileMenuOpen">MENÚ</span>
                    <span x-show="mobileMenuOpen">CERRAR</span>
                </button>
            </div>
        </div>
    </nav>
    
    <div 
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black z-40 md:hidden"
        x-cloak>
        <div class="flex flex-col items-center justify-center h-full space-y-8 text-white">
            <button @click="navigateTo('catalogo'); mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">Catálogo</button>
            <button @click="navigateTo('nosotros'); mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">Nosotros</button>
            <button @click="navigateTo('contacto'); mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">Contacto</button>
            
            <button 
                x-show="!isLoggedIn"
                @click="navigateTo('login'); mobileMenuOpen = false" 
                class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">
                Iniciar sesión
            </button>
            
            <button 
                x-show="isLoggedIn && !isAdmin"
                @click="navigateTo('perfil'); mobileMenuOpen = false" 
                class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">
                Perfil
            </button>
            
            <button 
                x-show="isLoggedIn && isAdmin"
                @click="navigateTo('admin'); mobileMenuOpen = false" 
                class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">
                Admin Panel
            </button>
            
            <button @click="cartOpen = true; mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">
                Carrito (<span x-text="cartCount"></span>)
            </button>
        </div>
    </div>
    
    <div x-show="cartOpen" class="fixed inset-0 z-[60]" x-cloak>
        <div 
            @click="cartOpen = false"
            x-show="cartOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/70"></div>
        
        <div 
            x-show="cartOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col">
            
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-black uppercase tracking-tight">Tu Carrito</h2>
                <button @click="cartOpen = false" class="text-3xl font-bold hover:opacity-60">×</button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 custom-scroll">
                <template x-if="cart.length === 0">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-gray-500 text-sm uppercase tracking-wider mb-4 font-semibold">Tu carrito está vacío</p>
                        <button @click="cartOpen = false" class="text-xs font-bold uppercase tracking-wider underline hover:no-underline">Continuar Comprando</button>
                    </div>
                </template>
                
                <div class="space-y-6">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="flex gap-4 pb-6 border-b border-gray-200">
                            <img :src="item.image" :alt="item.name" class="w-24 h-32 object-cover bg-gray-100 flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold uppercase tracking-wide mb-1 truncate" x-text="item.name"></h3>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2" x-text="item.color"></p>
                                <p class="text-base font-bold text-red-600" x-text="'$' + item.price.toLocaleString() + ' MXN'"></p>
                            </div>
                            <button 
                                @click="removeFromCart(index)" 
                                class="text-xs text-gray-400 hover:text-red-600 font-semibold transition self-start">
                                Eliminar
                            </button>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="border-t border-gray-200 p-6 space-y-4 bg-gray-50" x-show="cart.length > 0">
                <div class="flex items-center justify-between text-lg font-bold">
                    <span class="uppercase tracking-wider">Subtotal</span>
                    <span class="text-red-600" x-text="'$' + cartTotal.toLocaleString() + ' MXN'"></span>
                </div>
                <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider text-right">Descuentos aplicados automáticos</p>
                <button 
                    @click="handleCheckout()"
                    :disabled="isCheckingOut"
                    class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition disabled:opacity-50">
                    <span x-text="isCheckingOut ? 'Procesando...' : 'Finalizar Compra'"></span>
                </button>
                <button @click="cartOpen = false" class="w-full text-sm font-bold uppercase tracking-wider underline hover:no-underline">
                    Continuar Comprando
                </button>
            </div>
        </div>
    </div>
    
    <div x-show="currentRoute === 'inicio'" x-cloak>
        <section class="relative h-screen overflow-hidden">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1531303435785-3c53454b31a0?q=80&w=2074&auto=format&fit=crop" 
                     alt="SS26 Drop" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/70 to-black/90"></div>
            </div>
            
            <div class="relative z-10 flex items-center justify-between px-6 md:px-12 py-6">
                <h1 class="text-2xl md:text-3xl font-black tracking-tighter text-white">HFSTUDIOS</h1>
                <button 
                    @click="navigateTo('login')"
                    class="bg-white hover:bg-gray-200 text-black px-6 py-2 text-sm font-bold uppercase tracking-wider transition">
                    Iniciar sesión
                </button>
            </div>
            
            <div class="relative z-10 flex flex-col items-center justify-center h-full px-6 pb-32">
                <div class="max-w-3xl text-center">
                    <h2 class="text-4xl md:text-6xl lg:text-7xl font-black uppercase tracking-tight text-white mb-6 leading-tight">
                        ACCESO EXCLUSIVO<br>AL DROP SS26.
                    </h2>
                    
                    <p class="text-lg md:text-xl text-gray-300 mb-12 font-medium">
                        Todo el catálogo con 20% de descuento por tiempo limitado.
                    </p>
                    
                    <form @submit.prevent="handleLandingSubmit()" novalidate class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
                        <input 
                            type="text" 
                            x-model="landingEmail"
                            placeholder="Tu Email"
                            class="flex-1 px-6 py-4 text-lg bg-white/90 border-2 border-white/20 focus:border-white focus:outline-none text-black">
                        
                        <button 
                            type="submit"
                            class="bg-black hover:bg-gray-800 text-white border-2 border-white px-8 py-4 text-lg font-black uppercase tracking-widest transition whitespace-nowrap">
                            Entrar a la tienda <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    
    <div x-show="currentRoute === 'nosotros'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-8">Sobre Nosotros</h1>
                
                <div class="space-y-6 text-gray-700 leading-relaxed mb-12">
                    <p class="text-xl font-semibold">
                        Fundada en 2026, HFSTUDIOS representa la intersección del lujo y la cultura urbana. 
                        Creamos piezas premium que honran el pasado mientras empujan los límites hacia el futuro.
                    </p>
                    
                    <h2 class="text-2xl font-black uppercase tracking-tight mt-12 mb-4">Nuestra Misión</h2>
                    <p>
                        Ofrecemos streetwear de alta calidad diseñado para individuos que se niegan a mezclarse con la multitud. 
                        Cada prenda es una declaración de autenticidad y estilo.
                    </p>
                    
                    <h2 class="text-2xl font-black uppercase tracking-tight mt-12 mb-4">Horario de Tienda</h2>
                    <div class="bg-[#F4F4F4] p-8 space-y-2">
                        <p class="text-lg font-semibold">Lunes a Sábado: 10:00 - 20:00</p>
                        <p class="text-lg font-semibold">Domingo: 12:00 - 18:00</p>
                    </div>
                </div>
                
                <div class="mt-12">
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-4">Nuestra Ubicación</h2>
                    <p class="text-lg font-semibold mb-4">Aguascalientes, México</p>
                    <div class="aspect-video rounded overflow-hidden border-2 border-gray-200">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d119237.80976866765!2d-102.36291868828125!3d21.88089!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8429ee8436a52d4f%3A0x4f8d5b8c3f3c5f7d!2sAguascalientes%2C%20Ags.%2C%20Mexico!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale-map">
                        </iframe>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <button @click="navigateTo('catalogo')" class="inline-block bg-black text-white px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Ver Colección
                    </button>
                </div>
            </div>
        </section>
    </div>
    
    <div x-show="currentRoute === 'contacto'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-8">Contacto</h1>
                
                <form @submit.prevent="handleContactSubmit()" novalidate class="space-y-6 mb-12">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Nombre</label>
                        <input 
                            type="text" 
                            x-model="contactForm.name"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="Tu nombre">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Email</label>
                        <input 
                            type="text" 
                            x-model="contactForm.email"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="tu@email.com">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Mensaje</label>
                        <textarea 
                            x-model="contactForm.message"
                            rows="5"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm resize-none"
                            placeholder="Tu mensaje..."></textarea>
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Enviar Mensaje
                    </button>
                </form>
                
                <div class="text-center">
                    <p class="text-xs font-bold uppercase tracking-wider mb-4 text-gray-600">Síguenos en</p>
                    <div class="flex justify-center gap-8">
                        <a href="#" class="text-4xl hover:opacity-60 transition" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-4xl hover:opacity-60 transition" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <div x-show="currentRoute === 'catalogo'" x-cloak>
        <div class="bg-black text-white p-4 text-center">
            <p class="text-xs md:text-sm font-black uppercase tracking-[0.2em] animate-pulse">🔥 TODA LA TIENDA TIENE 20% DE DESCUENTO APLICADO 🔥</p>
        </div>
        
        <section class="max-w-screen-2xl mx-auto px-6 py-12">
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight mb-2">Catálogo</h2>
                <p class="text-sm text-gray-600 uppercase tracking-wider mb-8">Nuestras Piezas Más Vendidas</p>
                
                <div class="mb-8">
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            @input="selectedCategory = 'All'"
                            placeholder="Buscar productos (ej. Hoodie, Negro, Básica)..."
                            class="w-full px-6 py-4 text-sm border-2 border-gray-200 focus:border-black focus:outline-none bg-white">
                        <i class="fas fa-search absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <button 
                        @click="selectedCategory = 'All'; searchQuery = ''"
                        :class="selectedCategory === 'All' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        Todos
                    </button>
                    <button 
                        @click="selectedCategory = 'Promociones'; searchQuery = ''"
                        :class="selectedCategory === 'Promociones' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-red-600 border border-red-600 hover:bg-red-50'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition flex items-center gap-2">
                        <i class="fas fa-tags"></i> Promociones
                    </button>
                    <button 
                        @click="selectedCategory = 'Hoodie'; searchQuery = ''"
                        :class="selectedCategory === 'Hoodie' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        Hoodies
                    </button>
                    <button 
                        @click="selectedCategory = 'T-Shirt'; searchQuery = ''"
                        :class="selectedCategory === 'T-Shirt' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        T-Shirts
                    </button>
                    <button 
                        @click="selectedCategory = 'Pants'; searchQuery = ''"
                        :class="selectedCategory === 'Pants' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        Pants
                    </button>
                    <button 
                        @click="selectedCategory = 'Accessory'; searchQuery = ''"
                        :class="selectedCategory === 'Accessory' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        Accesorios
                    </button>
                </div>
            </div>
            
            <template x-if="filteredProducts.length === 0">
                <div class="text-center py-20">
                    <svg class="mx-auto h-24 w-24 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="text-2xl font-black uppercase tracking-tight mb-2">No se encontraron resultados</h3>
                    <p class="text-gray-600 mb-6">
                        No hay productos que coincidan con "<span class="font-bold" x-text="searchQuery"></span>"
                    </p>
                    <button 
                        @click="searchQuery = ''; selectedCategory = 'All'"
                        class="inline-block bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Ver todos los productos
                    </button>
                </div>
            </template>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 gap-y-10" x-show="filteredProducts.length > 0">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="product-card group cursor-pointer" @click="viewProductDetail(product)">
                        <div class="relative bg-[#F4F4F4] overflow-hidden mb-4">
                            <span 
                                x-show="product.badge"
                                :class="product.badge && product.badge.includes('PROMO') ? 'bg-black text-white' : 'bg-white text-black'"
                                class="absolute top-3 left-3 text-[9px] font-bold uppercase tracking-wider px-2 py-1 z-10 shadow-md"
                                x-text="product.badge"></span>
                            <img 
                                :src="product.image" 
                                :alt="product.name"
                                class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500">
                            
                            <div class="hover-overlay absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-full px-6 py-3 shadow-lg">
                                <p class="text-xs font-bold uppercase">Ver Detalle</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wide mb-1 truncate" x-text="product.name"></h3>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2" x-text="product.color"></p>
                            
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-black text-red-600" x-text="'$' + product.price.toLocaleString() + ' MXN'"></p>
                                <template x-if="product.originalPrice">
                                    <p class="text-[10px] font-bold text-gray-400 line-through" x-text="'$' + product.originalPrice.toLocaleString()"></p>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </section>
    </div>
    
    <div x-show="currentRoute === 'registro'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-md mx-auto bg-white p-10 shadow-2xl border border-gray-100">
                <h1 class="text-3xl font-black uppercase tracking-tight mb-8 text-center">Crear Cuenta</h1>
                
                <form @submit.prevent="handleRegistro()" novalidate class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Nombre</label>
                        <input 
                            type="text" 
                            x-model="registroForm.name"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="Tu nombre">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Email</label>
                        <input 
                            type="text" 
                            x-model="registroForm.email"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="tu@email.com">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Contraseña</label>
                        <input 
                            type="password" 
                            x-model="registroForm.password"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="••••••••">
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Registrarme
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya tienes cuenta? 
                        <button @click="navigateTo('login')" class="font-bold underline hover:no-underline text-black">Inicia sesión</button>
                    </p>
                </div>
            </div>
        </section>
    </div>
    
    <div x-show="currentRoute === 'login'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-md mx-auto bg-white p-10 shadow-2xl border border-gray-100">
                <h1 class="text-3xl font-black uppercase tracking-tight mb-8 text-center">Iniciar Sesión</h1>
                
                <div class="flex border-b-2 border-gray-200 mb-8">
                    <button 
                        @click="loginRole = 'user'"
                        :class="loginRole === 'user' ? 'border-b-4 border-black font-black text-black' : 'text-gray-400 font-bold'"
                        class="flex-1 pb-3 text-sm uppercase tracking-widest transition">
                        Cliente
                    </button>
                    <button 
                        @click="loginRole = 'admin'"
                        :class="loginRole === 'admin' ? 'border-b-4 border-black font-black text-black' : 'text-gray-400 font-bold'"
                        class="flex-1 pb-3 text-sm uppercase tracking-widest transition">
                        Administrador
                    </button>
                </div>
                
                <form @submit.prevent="handleLogin()" novalidate class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Email</label>
                        <input 
                            type="text" 
                            x-model="loginForm.email"
                            :disabled="isLoggingIn"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm disabled:opacity-50"
                            placeholder="tu@email.com">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Contraseña</label>
                        <input 
                            type="password" 
                            x-model="loginForm.password"
                            :disabled="isLoggingIn"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm disabled:opacity-50"
                            placeholder="••••••••">
                    </div>
                    
                    <button 
                        type="submit"
                        :disabled="isLoggingIn"
                        class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition disabled:opacity-50 flex items-center justify-center gap-2">
                        <template x-if="isLoggingIn">
                            <svg class="w-5 h-5 spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="isLoggingIn ? 'Verificando...' : 'Entrar'"></span>
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes cuenta? 
                        <button @click="navigateTo('registro')" class="font-bold underline hover:no-underline text-black">Regístrate</button>
                    </p>
                </div>
            </div>
        </section>
    </div>
    
    <div x-show="currentRoute === 'perfil'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-8">Mi Perfil</h1>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 p-8 border border-gray-100">
                        <h2 class="text-xl font-black uppercase tracking-tight mb-6">Mis Datos</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Nombre</p>
                                <p class="text-lg font-semibold" x-text="user.name"></p>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Email</p>
                                <p class="text-lg font-semibold" x-text="user.email"></p>
                            </div>
                        </div>
                        <button @click="handleLogout()" class="mt-8 w-full bg-black text-white py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                            Cerrar Sesión
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 p-8 border border-gray-100">
                        <h2 class="text-xl font-black uppercase tracking-tight mb-6">Mis Compras</h2>
                        <p class="text-gray-500 text-sm italic font-semibold">(Próximamente)</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <div x-show="currentRoute === 'admin'" x-cloak class="min-h-screen bg-gray-50 flex flex-col md:flex-row">
        
        <aside class="w-full md:w-64 bg-white border-r border-gray-200 flex flex-col shrink-0">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-black uppercase tracking-tighter">HF. ADMIN</h2>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mt-1" x-text="'HOLA, ' + user.name"></p>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
                <button @click="adminTab = 'pedidos'" :class="adminTab === 'pedidos' ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-100'" class="w-full text-left px-4 py-3 text-xs font-bold uppercase tracking-widest transition rounded-sm">
                    <i class="fas fa-box w-5"></i> Pedidos
                </button>
                <button @click="adminTab = 'productos'" :class="adminTab === 'productos' ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-100'" class="w-full text-left px-4 py-3 text-xs font-bold uppercase tracking-widest transition rounded-sm">
                    <i class="fas fa-tags w-5"></i> Productos
                </button>
                <button @click="adminTab = 'clientes'" :class="adminTab === 'clientes' ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-100'" class="w-full text-left px-4 py-3 text-xs font-bold uppercase tracking-widest transition rounded-sm">
                    <i class="fas fa-users w-5"></i> Clientes
                </button>
                <button @click="adminTab = 'envios'" :class="adminTab === 'envios' ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-100'" class="w-full text-left px-4 py-3 text-xs font-bold uppercase tracking-widest transition rounded-sm">
                    <i class="fas fa-truck w-5"></i> Envíos
                </button>
                <button @click="adminTab = 'facturacion'" :class="adminTab === 'facturacion' ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-100'" class="w-full text-left px-4 py-3 text-xs font-bold uppercase tracking-widest transition rounded-sm">
                    <i class="fas fa-file-invoice-dollar w-5"></i> Facturación
                </button>
                <button @click="adminTab = 'promociones'" :class="adminTab === 'promociones' ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-100'" class="w-full text-left px-4 py-3 text-xs font-bold uppercase tracking-widest transition rounded-sm">
                    <i class="fas fa-percent w-5"></i> Promociones
                </button>
            </nav>
            
            <div class="p-4 border-t border-gray-200 space-y-2">
                <button @click="navigateTo('catalogo')" class="w-full bg-white border border-gray-300 text-black px-4 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-100 transition text-center">
                    Ver Tienda
                </button>
                <button @click="handleLogout()" class="w-full bg-red-600 text-white px-4 py-3 text-xs font-bold uppercase tracking-widest hover:bg-red-700 transition text-center">
                    Cerrar Sesión
                </button>
            </div>
        </aside>
        
        <main class="flex-1 p-6 md:p-10 overflow-y-auto">
            
            <div class="mb-8 pb-4 border-b border-gray-200">
                <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tight" x-text="adminTab"></h1>
            </div>
            
            <div x-show="adminTab === 'pedidos'" class="bg-white border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-xs">Órdenes Recientes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Orden ID</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <template x-for="order in mockOrders" :key="order.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold" x-text="order.id"></td>
                                    <td class="px-6 py-4 text-gray-500" x-text="order.date"></td>
                                    <td class="px-6 py-4" x-text="order.customer"></td>
                                    <td class="px-6 py-4 font-bold" x-text="'$' + order.total"></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest bg-yellow-100 text-yellow-800 rounded" x-text="order.status"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div x-show="adminTab === 'productos'" class="bg-white border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-xs">Inventario</h3>
                    <button class="bg-black text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        + Añadir Producto
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Producto</th>
                                <th class="px-6 py-4">Categoría</th>
                                <th class="px-6 py-4">Precio Oferta</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="product in allProducts" :key="product.id">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img :src="product.image" :alt="product.name" class="w-12 h-16 object-cover bg-gray-100 border border-gray-200">
                                            <div>
                                                <p class="text-sm font-bold uppercase" x-text="product.name"></p>
                                                <p class="text-[10px] text-gray-500 uppercase" x-text="product.color"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 bg-gray-100 border border-gray-200 rounded" x-text="product.category"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-red-600" x-text="'$' + product.price"></p>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <button class="text-xs font-bold text-blue-600 hover:underline uppercase tracking-wider">Editar</button>
                                        <button class="text-xs font-bold text-red-600 hover:underline uppercase tracking-wider">Eliminar</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div x-show="adminTab === 'clientes'" class="bg-white border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-xs">Directorio de Clientes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Nombre</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Pedidos Totales</th>
                                <th class="px-6 py-4">Valor de Vida (LTV)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <template x-for="customer in mockCustomers" :key="customer.email">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold uppercase" x-text="customer.name"></td>
                                    <td class="px-6 py-4 text-gray-500" x-text="customer.email"></td>
                                    <td class="px-6 py-4" x-text="customer.orders"></td>
                                    <td class="px-6 py-4 font-bold text-green-600" x-text="'$' + customer.ltv"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div x-show="adminTab === 'envios'" class="bg-white border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-xs">Rastreo de Paquetes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Orden ID</th>
                                <th class="px-6 py-4">Paquetería</th>
                                <th class="px-6 py-4">No. Guía</th>
                                <th class="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <template x-for="ship in mockShipping" :key="ship.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold" x-text="ship.id"></td>
                                    <td class="px-6 py-4 uppercase font-semibold" x-text="ship.courier"></td>
                                    <td class="px-6 py-4 font-mono text-gray-500" x-text="ship.tracking"></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest bg-blue-100 text-blue-800 rounded" x-text="ship.status"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div x-show="adminTab === 'facturacion'" class="bg-white border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-xs">Registro Contable</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Factura ID</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Monto</th>
                                <th class="px-6 py-4 text-right">Documento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <template x-for="inv in mockInvoices" :key="inv.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold" x-text="inv.id"></td>
                                    <td class="px-6 py-4 text-gray-500" x-text="inv.date"></td>
                                    <td class="px-6 py-4 uppercase" x-text="inv.client"></td>
                                    <td class="px-6 py-4 font-bold" x-text="'$' + inv.amount"></td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-[10px] font-bold uppercase tracking-widest text-black border border-black px-3 py-1 hover:bg-black hover:text-white transition">
                                            PDF <i class="fas fa-download ml-1"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="adminTab === 'promociones'" class="bg-white border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-xs">Gestión de Promociones</h3>
                    <button class="bg-black text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        + Nueva Promoción
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Código</th>
                                <th class="px-6 py-4">Descuento</th>
                                <th class="px-6 py-4">Vencimiento</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <template x-for="promo in mockPromotions" :key="promo.code">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-black tracking-widest" x-text="promo.code"></td>
                                    <td class="px-6 py-4 font-bold text-red-600" x-text="promo.discount"></td>
                                    <td class="px-6 py-4 text-gray-500" x-text="promo.expiry"></td>
                                    <td class="px-6 py-4">
                                        <span :class="promo.status === 'Activa' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600'" 
                                              class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest rounded" 
                                              x-text="promo.status"></span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <button class="text-xs font-bold text-blue-600 hover:underline uppercase tracking-wider">Editar</button>
                                        <button class="text-xs font-bold text-red-600 hover:underline uppercase tracking-wider">Desactivar</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </main>
    </div>
    
    <footer x-show="currentRoute !== 'inicio' && currentRoute !== 'admin'" class="bg-black text-white py-16">
        <div class="max-w-screen-2xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <h3 class="text-2xl font-black mb-4 tracking-tighter">HFSTUDIOS</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Streetwear premium para quienes se atreven a ser diferentes.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-4">Tienda</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><button @click="navigateTo('catalogo')" class="hover:text-white transition">Nuevos Ingresos</button></li>
                        <li><button @click="navigateTo('catalogo')" class="hover:text-white transition">Más Vendidos</button></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-4">Empresa</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><button @click="navigateTo('nosotros')" class="hover:text-white transition">Sobre Nosotros</button></li>
                        <li><button @click="navigateTo('contacto')" class="hover:text-white transition">Contacto</button></li>
                        <li><button @click="navigateTo('login')" class="hover:text-white transition">Admin Access</button></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-4">Newsletter</h4>
                    <p class="text-xs text-gray-400 mb-4">Suscríbete para ofertas exclusivas.</p>
                    <div class="flex">
                        <input 
                            type="text" 
                            placeholder="Tu email" 
                            class="flex-1 bg-white/10 border border-white/20 px-4 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-white">
                        <button class="bg-white text-black px-6 py-2 text-xs font-bold hover:bg-gray-200 transition">
                            →
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">© 2026 HF Studios Aguascalientes. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                currentRoute: 'inicio',
                landingEmail: '',
                searchQuery: '',
                
                // MOCK DATA FOR ADMIN TABS
                adminTab: 'pedidos',
                mockOrders: [
                    { id: 'ORD-8921', date: '08 Mar 2026', customer: 'Henry', total: 4380, status: 'Completado' },
                    { id: 'ORD-8922', date: '08 Mar 2026', customer: 'Alejandra', total: 1890, status: 'Pendiente' },
                    { id: 'ORD-8923', date: '07 Mar 2026', customer: 'Carlos G.', total: 2490, status: 'Enviado' }
                ],
                mockCustomers: [
                    { name: 'Henry', email: 'henry@example.com', orders: 4, ltv: 9500 },
                    { name: 'Alejandra', email: 'ale@example.com', orders: 1, ltv: 1890 },
                    { name: 'Carlos G.', email: 'carlos@example.com', orders: 2, ltv: 3400 }
                ],
                mockShipping: [
                    { id: 'ORD-8921', courier: 'Estafeta', tracking: 'ESTA88392010', status: 'Entregado' },
                    { id: 'ORD-8923', courier: 'DHL', tracking: 'DHL992837482', status: 'En Tránsito' }
                ],
                mockInvoices: [
                    { id: 'FAC-001', date: '08 Mar 2026', client: 'Henry', amount: 4380 },
                    { id: 'FAC-002', date: '07 Mar 2026', client: 'Carlos G.', amount: 2490 }
                ],
                mockPromotions: [
                    { code: 'SS26DROP', discount: '20%', expiry: '31 Mar 2026', status: 'Activa' },
                    { code: 'ENVIOFREE', discount: 'Envío', expiry: 'Indefinido', status: 'Activa' },
                    { code: 'WINTER25', discount: '30%', expiry: '01 Ene 2026', status: 'Inactiva' }
                ],
                
                // TODOS LOS PRODUCTOS CON PRECIO ORIGINAL Y PRECIO DE DESCUENTO (20%)
                allProducts: [
                    { id: 1, name: 'Hoodie Esencial', color: 'Gris Carbón', originalPrice: 1890, price: 1512, image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'Hoodie', description: 'Hoodie premium de alta calidad con ajuste relajado y diseño minimalista.' },
                    { id: 2, name: 'Tee Oversized', color: 'Blanco Hueso', originalPrice: 1190, price: 952, image: 'https://images.unsplash.com/photo-1622445275576-721325763afe?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'T-Shirt', description: 'Camiseta oversized de algodón 100% con corte holgado y cómodo.' },
                    { id: 3, name: 'Hoodie Gráfico', color: 'Negro Lavado', originalPrice: 2090, price: 1672, image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'Hoodie', description: 'Hoodie con gráficos exclusivos y acabado lavado para look desgastado.' },
                    { id: 4, name: 'Tee Vintage', color: 'Gris', originalPrice: 1390, price: 1112, image: 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'T-Shirt', description: 'Camiseta con lavado vintage y gráfico retro exclusivo.' },
                    { id: 5, name: 'Hoodie Premium', color: 'Negro', originalPrice: 2290, price: 1832, image: 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'Hoodie', description: 'Hoodie de peso pesado con construcción premium y detalles bordados.' },
                    { id: 6, name: 'Tee Básico', color: 'Blanco', originalPrice: 990, price: 792, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'T-Shirt', description: 'Camiseta básica de algodón peinado con ajuste regular.' },
                    { id: 7, name: 'Cargo Pants', color: 'Negro', originalPrice: 2490, price: 1992, image: 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'Pants', description: 'Pantalones cargo con múltiples bolsillos y tela resistente.' },
                    { id: 8, name: 'Beanie Premium', color: 'Negro', originalPrice: 690, price: 552, image: 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'Accessory', description: 'Gorro de punto premium con logo bordado.' },
                    { id: 9, name: 'Track Pants', color: 'Gris Pizarra', originalPrice: 1890, price: 1512, image: 'https://images.unsplash.com/photo-1603252109303-2751441dd157?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'Pants', description: 'Pantalones deportivos con ajuste cómodo y diseño moderno.' },
                    { id: 10, name: 'Baseball Cap', color: 'Negro', originalPrice: 890, price: 712, image: 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?q=80&w=800&auto=format&fit=crop', badge: 'PROMO -20%', category: 'Accessory', description: 'Gorra de béisbol con ajuste trasero y logo frontal.' }
                ],
                
                selectedCategory: 'All',
                selectedProduct: {},
                productDetailOpen: false,
                cart: [],
                cartOpen: false,
                mobileMenuOpen: false,
                orderSuccessOpen: false,
                isCheckingOut: false,
                orderNumber: '',
                
                isLoggedIn: false,
                isAdmin: false,
                loginRole: 'user',
                isLoggingIn: false,
                user: { name: '', email: '' },
                
                registroForm: { name: '', email: '', password: '' },
                loginForm: { email: '', password: '' },
                contactForm: { name: '', email: '', message: '' },
                
                toast: { show: false, message: '' },
                
                init() {
                    const savedCart = localStorage.getItem('hfstudios_cart');
                    if (savedCart) this.cart = JSON.parse(savedCart);
                    
                    const savedUser = localStorage.getItem('hfstudios_user');
                    if (savedUser) {
                        const userData = JSON.parse(savedUser);
                        this.user = userData.user;
                        this.isLoggedIn = userData.isLoggedIn;
                        this.isAdmin = userData.isAdmin;
                    }
                    
                    this.$watch('cart', value => {
                        localStorage.setItem('hfstudios_cart', JSON.stringify(value));
                    });
                },
                
                get filteredProducts() {
                    let products = this.allProducts;
                    
                    if (this.searchQuery.trim() !== '') {
                        const query = this.searchQuery.toLowerCase().trim();
                        products = products.filter(product => {
                            return (
                                product.name.toLowerCase().includes(query) ||
                                product.category.toLowerCase().includes(query) ||
                                product.color.toLowerCase().includes(query)
                            );
                        });
                    }
                    
                    // FILTRO DE PROMOCIONES ESPECIAL
                    if (this.searchQuery.trim() === '' && this.selectedCategory === 'Promociones') {
                        products = products.filter(p => p.badge && p.badge.includes('PROMO'));
                    } 
                    else if (this.searchQuery.trim() === '' && this.selectedCategory !== 'All') {
                        products = products.filter(p => p.category === this.selectedCategory);
                    }
                    
                    return products;
                },
                
                get cartCount() {
                    return this.cart.length;
                },
                
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + item.price, 0);
                },
                
                navigateTo(route) {
                    this.currentRoute = route;
                    window.scrollTo(0, 0);
                },
                
                showToast(message) {
                    this.toast.message = message;
                    this.toast.show = true;
                    setTimeout(() => this.toast.show = false, 3000);
                },
                
                handleLandingSubmit() {
                    this.navigateTo('catalogo');
                },
                
                viewProductDetail(product) {
                    this.selectedProduct = product;
                    this.productDetailOpen = true;
                },
                
                addToCart(product) {
                    this.cart.push({
                        id: Date.now(),
                        name: product.name,
                        color: product.color,
                        price: product.price,
                        image: product.image
                    });
                    this.showToast('Producto agregado al carrito');
                    this.cartOpen = true;
                },
                
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },
                
                async handleCheckout() {
                    if (this.cart.length === 0) return;
                    
                    this.isCheckingOut = true;
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    this.orderNumber = 'HF-' + Math.random().toString(36).substr(2, 9).toUpperCase();
                    this.cart = [];
                    this.isCheckingOut = false;
                    this.cartOpen = false;
                    this.orderSuccessOpen = true;
                },
                
                handleContactSubmit() {
                    this.showToast('Mensaje enviado (simulado)');
                    this.contactForm = { name: '', email: '', message: '' };
                },
                
                handleRegistro() {
                    this.showToast('Registro exitoso (simulado)');
                    this.registroForm = { name: '', email: '', password: '' };
                    this.navigateTo('login');
                },
                
                async handleLogin() {
                    this.isLoggingIn = true;
                    
                    // Simular carga de 1.5 segundos
                    await new Promise(resolve => setTimeout(resolve, 1500));
                    
                    this.user.name = this.loginForm.email || 'Henry'; // Usando contexto local
                    this.user.email = this.loginForm.email;
                    this.isLoggedIn = true;
                    
                    // Configurar permisos de administrador basados en el rol seleccionado
                    if (this.loginRole === 'admin') {
                        this.isAdmin = true;
                        this.showToast('Acceso de administrador concedido');
                        localStorage.setItem('hfstudios_user', JSON.stringify({
                            user: this.user,
                            isLoggedIn: true,
                            isAdmin: true
                        }));
                        this.navigateTo('admin');
                    } else {
                        this.isAdmin = false;
                        this.showToast('Sesión iniciada correctamente');
                        localStorage.setItem('hfstudios_user', JSON.stringify({
                            user: this.user,
                            isLoggedIn: true,
                            isAdmin: false
                        }));
                        this.navigateTo('perfil');
                    }
                    
                    this.isLoggingIn = false;
                    this.loginForm = { email: '', password: '' };
                    this.loginRole = 'user';
                },
                
                handleLogout() {
                    this.isLoggedIn = false;
                    this.isAdmin = false;
                    this.user = { name: '', email: '' };
                    localStorage.removeItem('hfstudios_user');
                    
                    // Resetear vista de Admin
                    this.adminTab = 'pedidos';
                    
                    this.showToast('Sesión cerrada exitosamente');
                    this.navigateTo('inicio');
                }
            }));
        });
    </script>
</body>
</html>