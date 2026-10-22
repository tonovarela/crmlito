import Pagination from './componentes/Pagination.js';
import FilterInput from './componentes/Filters/FilterInput.js';
import Loader from './componentes/Loader.js';
import FilterSelect from './componentes/Filters/FilterSelect.js';
import SortableHeader from './componentes/Home/Table/SortableHeader.js';

new Vue({
    el: '#elementosList',
    data: {
        title: 'Cargando...',
        elementos: {
            data: []
        },
        totalElementos: 0, // Total de posts para mostrar en la paginación
        paginationMeta: {}, // aquí va lo que devuelve paginate()
        searchQuery: '',
        debounceTimeout: null,
        orderBy: 'id',
        orderDirection: 'desc',
        loading: false,
        stopSecondSearch: false,
        searchUser: '',
        searchCategory: '',
        baseUrl: window.Laravel.baseUrl,
        ultimoFolio: null,
        role: window.role
    },
    components: {
        Pagination,
        FilterInput,
        Loader,
        FilterSelect,
        SortableHeader
    },
    created() {
        EventBus.$on('postCreated', () => this.getElementosPresupuestados());
        EventBus.$on('postUpdated', () => this.getElementosPresupuestados());
        EventBus.$on('postDeleted', () => this.getElementosPresupuestados());
        EventBus.$on('postCloned', () => this.getElementosPresupuestados());
    },
    beforeDestroy() {
        // Limpiar el event listener para evitar fugas de memoria
        EventBus.$off('postCreated', this.getElementosPresupuestados);
        EventBus.$off('postUpdated', this.getElementosPresupuestados);
        EventBus.$off('postDeleted', this.getElementosPresupuestados);
        EventBus.$off('postCloned', this.getElementosPresupuestados);
    },
    computed: {
        paginationLinks() {
            const links = [];
            const meta = this.paginationMeta;

            if (!meta || !meta.current_page) return links;

            // Prev
            links.push({
                url: meta.prev_page_url,
                label: '&laquo; Anterior',
                active: false
            });

            // Pages
            for (let i = 1; i <= meta.last_page; i++) {
                links.push({
                url: `${meta.path}?page=${i}`,
                label: `${i}`,
                active: i === meta.current_page
                });
            }

            // Next
            links.push({
                url: meta.next_page_url,
                label: 'Siguiente &raquo;',
                active: false
            });

            return links;
        },
    },
    methods: {
        getElementosPresupuestados: function (page = 1, applySearch = false) {
            this.loading = true;

            if (!applySearch) {
                page = new URL(window.location.href).searchParams.get('page') || 1;
            } else {
                let url = new URL(window.location.href);
                url.searchParams.delete('page');
                window.history.pushState({}, '', url);
                page = page || 1;
            }

            const filters = {
                searchQuery: localStorage.getItem('searchQuery') || this.searchQuery,
                orderBy: localStorage.getItem('orderBy') || this.orderBy,
                orderDirection: localStorage.getItem('orderDirection') || this.orderDirection
            };

            axios.get(`sorted?page=${page}`, {
                params: { 
                    sort_by: filters.orderBy,
                    order: filters.orderDirection,
                    search: filters.searchQuery,
                }
            })
            .then(res => {
                this.elementos = res.data.data.data;   // paginador completo
                this.totalElementos = res.data.data.total; // total de posts
                this.paginationMeta = res.data.data; // metadatos de paginación
                this.loading = false;
            })
            .catch(error => {
                console.error('Error al obtener posts:', error);
            });
        },
        search() {
            // if (this.stopSecondSearch) {
            //     return;
            // }

            // Guardamos las búsquedas en el historial con localStorage
            if (this.searchQuery) {
                localStorage.setItem('searchQuery', this.searchQuery);
            }

            localStorage.setItem('orderBy', this.orderBy);
            localStorage.setItem('orderDirection', this.orderDirection);

            clearTimeout(this.debounceTimeout);
                this.debounceTimeout = setTimeout(() => {
                this.getElementosPresupuestados(1, true);
            }, 500); // espera 500 ms después de escribir
        },
        onPageChange(url) {
            if (!url) return;
            const urlParam = new URL(window.location.href);
            const page = new URL(url).searchParams.get('page');

            if (page == 1) {
                urlParam.searchParams.delete('page');
                window.history.pushState({}, '', urlParam);
            } else {
                urlParam.searchParams.set('page', page);
                window.history.pushState({}, '', urlParam);
            }

            this.getElementosPresupuestados();
        },
        sortBy(field) {
            if (this.orderBy === field) {
                this.orderDirection = this.orderDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.orderBy = field;
                this.orderDirection = 'asc';
            }
            this.getElementosPresupuestados();
        },
        clearFilters() {
            this.searchQuery = '';
            this.orderBy = 'id';
            this.orderDirection = 'desc';
            this.stopSecondSearch = true; // Evita la segunda búsqueda
            this.searchUser = '';
            this.searchCategory = '';
            this.$nextTick(() => {
                if (this.$refs.filtroUsuario) {
                    this.$refs.filtroUsuario.$emit('clear');
                    this.stopSecondSearch = false; // Permite futuras búsquedas
                }
                if (this.$refs.filtroCategoria) {
                    this.$refs.filtroCategoria.$emit('clear');
                    this.stopSecondSearch = false; // Permite futuras búsquedas
                }
            });

            localStorage.removeItem('searchQuery');
            localStorage.removeItem('searchUser');
            localStorage.removeItem('searchCategory');
            localStorage.removeItem('orderBy');
            localStorage.removeItem('orderDirection');

            this.getElementosPresupuestados();
        },
        clonarElemento(id, nombreProyectoOrg, folioOrg) {
            const ultimoFolio = this.ultimoFolio || 0;

            Swal.fire({
                title: 'Clonar Elemento Presupuestado',
                input: 'text',
                html: `
                    <p>Nombre del Proyecto Original: ${nombreProyectoOrg || 'N/A'}</p>
                    <p>Folio Original: ${folioOrg || 'N/A'}</p>
                    <p>Nuevo Folio ${ultimoFolio}.</p>
                `,
                inputLabel: 'Nombre del Proyecto',
                inputPlaceholder: 'Ingrese el nombre del proyecto',
                inputValue: nombreProyectoOrg || '',
                showCancelButton: true,
                confirmButtonText: 'Clonar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) {
                        return 'El nombre del proyecto es obligatorio';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const nombreProyecto = result.value;
                    axios.post(`${this.baseUrl}/elementosp/clonar`, { id: id, nombre_proyecto: nombreProyecto })
                        .then(response => {
                            if (response.data.success) {
                                this.getElementosPresupuestados();
                                EventBus.$emit('postCloned');
                                const Msg = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                Msg.fire({
                                    icon: 'success',
                                    title: `Elemento presupuestado clonado con éxito. Nuevo folio: ${ultimoFolio}`,
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al clonar el elemento presupuestado:', error);
                        });
                }
            });
        },
        async obtenerUltimoFolioRegistrado() {
            try {
                const response = await axios.get(`${this.baseUrl}/elementosp/ultimo-folio`);
                if (response.data.success) {
                    this.ultimoFolio = response.data.folio;
                } else {
                    console.error('No se pudo obtener el último folio registrado.');
                }
            } catch (error) {
                console.error('Error al obtener el último folio registrado');
            }
        },
    },
    mounted: function() {
        this.clearFilters(); // Limpiar filtros al montar el componente
        this.getElementosPresupuestados();
        this.obtenerUltimoFolioRegistrado();
    },
});