<pagination 
    is="pagination"
    inline-template
    :links="{{ $links }}" 
    @page-change="onPageChange" 
    :total="{{ $total }}"
>
    <div v-if="visibleLinks.length > 1" class="mt-5 pb-4 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0 md:space-x-4">
        
        <!-- Total de resultados -->
        <div class="text-center md:text-left">
            <label class="text-gray-700 text-sm font-bold">
                <span class="text-gray-500 text-sm">@{{ total }}</span> Resultados
            </label>
        </div>

        <!-- Botones de paginación -->
        <div class="flex justify-center md:justify-end flex-wrap gap-2">

            <!-- Modo móvil: solo Anterior / Siguiente -->
            <template v-for="(link, index) in visibleLinks">
                <a
                    v-if="link.label.toLowerCase().includes('anterior') || link.label.toLowerCase().includes('previous')"
                    :key="'prev-' + index"
                    href="#"
                    class="md:hidden px-3 py-2 text-sm border rounded hover:bg-white text-gray-700"
                    @click.prevent="link.url && $emit('page-change', link.url)"
                    v-html="link.label"
                ></a>

                <a
                    v-if="link.label.toLowerCase().includes('siguiente') || link.label.toLowerCase().includes('next')"
                    :key="'next-' + index"
                    href="#"
                    class="md:hidden px-3 py-2 text-sm border rounded hover:bg-white text-gray-700"
                    @click.prevent="link.url && $emit('page-change', link.url)"
                    v-html="link.label"
                ></a>
            </template>

            <!-- Modo escritorio: toda la paginación -->
            <a
                v-for="(link, key) in visibleLinks"
                :key="'link-' + key"
                href="#"
                class="hidden md:inline-block px-3 py-2 text-sm border rounded transition"
                :class="{
                    'text-gray-400 cursor-not-allowed': !link.url,
                    'hover:bg-white text-gray-700': link.url,
                    'bg-white font-bold': link.active
                }"
                :aria-current="link.active ? 'page' : null"
                v-html="link.label"
                @click.prevent="link.url && $emit('page-change', link.url)"
            ></a>

        </div>
    </div>
</pagination>
