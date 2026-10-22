const Pagination = Vue.component('pagination', {
    props: {
        links: {
            type: Array,
            required: true
        },
        total: {
            type: Number,
            default: 0
        }
    },
    computed: {
        visibleLinks() {
            // Mostrar solo los enlaces de paginación visibles
            if (!this.links || this.links.length <= 7) return this.links;
            // links: todos los enlaces de paginación
            const links = this.links;
            // current: enlace activo (página actual)
            const current = links.find(l => l.active);
            // Si no hay enlace activo, no se puede calcular la paginación
            const currentPage = current ? parseInt(current.label) : 1;
            // Si no hay enlace activo, no se puede calcular la paginación
            const totalPages = links.filter(l => l.url !== null && !isNaN(l.label)).length;
            // Resultado final
            const result = [];
            // Función para identificar enlaces numéricos
            const isNumberLink = l => l.url !== null && !isNaN(l.label);

            // Separar prev y next
            const prev = links.find(l => l.label.includes('&laquo; Anterior'));
            const next = links.find(l => l.label.includes('Siguiente &raquo;'));

            const pages = links.filter(isNumberLink).map(l => ({
                ...l,
                page: parseInt(l.label)
            }));

            const firstPages = pages.slice(0, 3);
            const lastPages = pages.slice(-3);

            const start = Math.max(currentPage - 2, 4);
            const end = Math.min(currentPage + 2, totalPages - 3);
            const middlePages = pages.filter(p => p.page >= start && p.page <= end);

            // Construir resultado
            if (prev) result.push(prev);

            result.push(...firstPages);

            if (start > 4) {
                result.push({ label: '...', url: null });
            }

            result.push(...middlePages);

            if (end < totalPages - 3) {
                result.push({ label: '...', url: null });
            }

            result.push(...lastPages);

            if (next) result.push(next);

            return result;
        }
    }
});

export default Pagination;