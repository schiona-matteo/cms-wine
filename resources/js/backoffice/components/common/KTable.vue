<template>
    <div>
        <table class="min-w-full border-separate" style="border-spacing: 0">
            <thead class="bg-white">
                <tr>
                    <template v-for="(column, index) in columns" :key="column.key">
                        <template v-if="column.key != 'actions'">
                            <th scope="col" :class="[index == 0 ? 'py-3 pl-4 pr-3 sm:pl-6' : 'px-3 py-3.5', 'text-xs font-semibold uppercase tracking-wide text-gray-500', getValueOrDefault(column, 'headerAlign', 'text-left')]">
                                <div :class="['flex space-x-3', getValueOrDefault(column, 'sort', 'true') == 'true' ? 'cursor-pointer' : '']" @click="chooseSort(column)">
                                    <div>
                                        {{ getValueOrDefault(column, "title", column.key) }}
                                    </div>
                                    <div v-if="getValueOrDefault(column, 'sort', 'true') == 'true' && orderBy.column == column.key">
                                        <BarsArrowDownIcon class="w-5" v-if="orderBy.dir == 'desc'"></BarsArrowDownIcon>
                                        <BarsArrowUpIcon class="w-5" v-else></BarsArrowUpIcon>
                                    </div>
                                </div>
                            </th>
                        </template>
                        <template v-else>
                            <th scope="col" :class="['relative py-3 pl-3 pr-4 sm:pr-6', getValueOrDefault(column, 'headerAlign', 'text-center')]">
                                <button
                                    v-if="addButton"
                                    @click="$emit('addClicked')"
                                    class="inline-flex items-center px-2 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-grey-700 bg-white hover:text-white hover:bg-green-600">
                                    <plus-icon class="w-4 h-4"></plus-icon>
                                    <span>Aggiungi</span>
                                </button>
                            </th>
                        </template>
                    </template>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <template v-for="(row, idx) in elements" :key="row.id">
                    <tr>
                        <td
                            v-for="(column, columnIdx) in columns"
                            :key="column.key"
                            :class="[
                                idx !== elements.length - 1 ? 'border-b border-gray-200' : '',
                                columnIdx == 0
                                    ? 'py-4 pl-4 pr-3 text-chicago-600 sm:pl-6 lg:pl-8'
                                    : (columnIdx == columns.length - 1 ? 'py-4 pr-4 pl-3 sm:pr-6 lg:pr-8 space-x-2' : 'px-3 py-4 text-gray-500 sm:table-cell', 'whitespace-nowrap text-sm'),
                                getValueOrDefault(column, 'columnAlign', 'text-center'),
                                'font-light text-xs',
                            ]">
                            <slot :name="'column-' + column.key" v-bind:row="row">
                                <template v-if="column.key != 'actions'">
                                    {{ getFieldValue(row, column) }}
                                </template>
                                <template v-else>
                                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                        <button
                                            v-if="updateButton"
                                            @click="$emit('updateClicked', row)"
                                            class="-ml-px relative inline-flex items-center px-2.5 py-1.5 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-highlight focus:border-highlight">
                                            <pencil-icon class="w-4 h-4"></pencil-icon>
                                        </button>
                                        <button
                                            v-if="deleteButton"
                                            @click="$emit('deleteClicked', row)"
                                            class="-ml-px relative inline-flex items-center px-2.5 py-1.5 rounded-r-md border border-gray-300 bg-white text-sm font-medium focus:z-10 focus:outline-none focus:ring-0 hover:bg-red-500 hover:text-white text-gray-700">
                                            <trash-icon class="w-4 h-4"></trash-icon>
                                        </button>
                                    </span>
                                </template>
                            </slot>
                        </td>
                    </tr>
                    <slot :name="'after-tr'" v-bind:row="row"></slot>
                </template>
            </tbody>
            <tfoot>
                <tr>
                    <td :colspan="columns.length" class="border-t border-gray-200 bg-white text-sm pt-5">
                        <div v-if="results_count > 0" class="text-center flex flex-col space-y-2">
                            <div>{{ results_count }} risultati totali trovati.</div>
                            <div v-if="total_pages > 1">Pagina {{ page }} di {{ total_pages }}</div>
                            <div v-if="total_pages > 1">
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <button
                                        :disabled="page <= 1"
                                        @click="page-- && fetchData()"
                                        :class="page <= 1 ? 'bg-gray-200' : 'bg-white'"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                                    </button>
                                    <button
                                        :disabled="page >= total_pages"
                                        @click="page++ && fetchData()"
                                        :class="page >= total_pages ? 'bg-gray-200' : 'bg-white'"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                                    </button>
                                </nav>
                            </div>
                        </div>
                        <div v-else class="px-4 text-center">Nessun risultato trovato.</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
<script>
    import { debounce } from "vue-debounce";
    import { ChevronLeftIcon, ChevronRightIcon, PlusIcon, PencilIcon, TrashIcon, BarsArrowUpIcon, BarsArrowDownIcon } from "@heroicons/vue/24/solid";
    export default {
        components: {
            ChevronLeftIcon,
            ChevronRightIcon,
            PlusIcon,
            PencilIcon,
            TrashIcon,
            BarsArrowUpIcon,
            BarsArrowDownIcon,
        },
        props: {
            filters: {
                type: Object,
                default: () => {
                    return {};
                },
            },
            columns: {
                type: Array,
                default: () => {
                    return [
                        {
                            key: "column",
                            title: "Colonna",
                            sort: true,
                            headerAlign: "text-center",
                            columnAlign: "text-center",
                        },
                    ];
                },
            },
            addButton: {
                type: Boolean,
                default: true,
            },
            updateButton: {
                type: Boolean,
                default: true,
            },
            deleteButton: {
                type: Boolean,
                default: true,
            },
            fetchUrl: {
                type: String,
                default: "",
            },
            takeProp: {
                type: Number,
                default: 25,
            },
            orderProp: {
                type: Object,
                default: () => {
                    return {
                        column: "id",
                        dir: "desc",
                    };
                },
            },
        },
        data() {
            return {
                elements: [],
                orderBy: {
                    column: "id",
                    dir: "desc",
                },
                take: 1,
                page: 1,
                total_pages: 1,
                results_count: 0,
                firstDataLoaded: false,
                abortController: null,
            };
        },
        mounted() {
            this.take = this.takeProp;
            this.orderBy = this.orderProp;

            this.fetchData();
        },
        methods: {
            getFieldValue(row, column) {
                const splitted = column.key.split(".");

                switch (splitted.length) {
                    case 1:
                        return row[splitted[0]];
                        break;
                    case 2:
                        return row[splitted[0]][splitted[1]];
                        break;
                    case 3:
                        return row[splitted[0]][splitted[1]][splitted[2]];
                        break;
                }
            },
            getValueOrDefault(element, key, defaultValue) {
                if (typeof element[key] != "undefined") {
                    return element[key];
                }

                return defaultValue;
            },
            chooseSort(column) {
                if (this.getValueOrDefault(column, "sort", "true") == "true") {
                    if (this.orderBy.column == column.key) {
                        this.orderBy.dir = this.orderBy.dir == "asc" ? "desc" : "asc";
                    } else {
                        this.orderBy.column = column.key;
                        this.orderBy.dir = "desc";
                    }
                    this.fetchData();
                }
            },
            exposeData() {
                return this.elements;
            },
            fetchData(callback) {
                debounce(
                    () => {
                        if (this.abortController) {
                            this.abortController.abort();
                        }

                        this.abortController = new AbortController();

                        axios
                            .post(
                                this.fetchUrl,
                                {
                                    filters: this.filters,
                                    orderBy: this.orderBy,
                                    take: this.take,
                                    page: this.page,
                                },
                                { signal: this.abortController.signal }
                            )
                            .then((response) => {
                                this.firstDataLoaded = true;
                                this.elements = response.data.data;
                                this.take = response.data.take;
                                this.page = response.data.current;
                                this.total_pages = response.data.total_pages;
                                this.results_count = response.data.total;

                                if (typeof callback != "undefined") {
                                    callback(response);
                                }
                            });
                    },
                    this.firstDataLoaded ? 400 : 0
                )();
            },
        },
    };
</script>
