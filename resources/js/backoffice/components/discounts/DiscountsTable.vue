<template>
    <div>
        <div class="bg-gray-50 rounded-md px-2 py-3 mb-5 border flex gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <div class="mt-1">
                    <input
                        v-model="filters.name"
                        @input="fetchData()"
                        type="text"
                        name="name"
                        id="name"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="Ricerca..." />
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1">
                    <input
                        v-model="filters.email"
                        @input="fetchData()"
                        type="email"
                        name="email"
                        id="email"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="Ricerca..." />
                </div>
            </div>
            <div>
                <label for="created_at" class="block text-sm font-medium text-gray-700">Data</label>
                <div class="mt-1">
                    <date-picker ref="dateFilterPicker" v-model="filters.created_at" format="dd/MM/yyyy" locale="it-IT" range autoApply :enableTimePicker="false" @closed="fetchData()" @cleared="fetchData()">
                        <template #dp-input="{ value }">
                            <div class="cursor-pointer">
                                <div class="dp__input_wrap rounded-lg">
                                    <div v-if="value == ''" class="mx-2 py-2">
                                        <calendar-icon class="w-5 h-5"></calendar-icon>
                                    </div>
                                    <div v-else-if="value.split(' - ').length == 2" class="text-center flex flex-col mr-9">
                                        <div class="text-sm" v-text="value.split(' - ')[0]"></div>
                                        <div class="text-sm" v-text="value.split(' - ')[1]"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </date-picker>
                </div>
            </div>
        </div>
        <k-table
            ref="table"
            :filters="filters"
            :columns="tableColumn"
            fetch-url="/backoffice/api/discounts"
            @addClicked="$root.openModal(modal, 'update', emptyModel)"
            @updateClicked="$root.openModal(modal, 'update', $event)"
            @deleteClicked="$root.openModal(modal, 'delete', $event)">
            <template v-slot:column-type="data"
                ><span :class="['inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ', types[data.row.type].class]">
                    {{ types[data.row.type].label }}
                </span></template
            >
            <template v-slot:column-usage_limit="data">{{ data.row.count }} / {{ data.row.usage_limit }}</template>
            <template v-slot:column-created_at="data">{{ dayjs(data.row.created_at).format("DD/MM/YYYY HH:mm") }}</template>
            <template v-slot:column-expires_at="data">
                <span v-if="data.row.expires_at">
                    {{ dayjs(data.row.expires_at).format("DD/MM/YYYY") }}
                </span>
                <span v-else>N/D</span>
            </template>
            <template v-slot:column-actions="data">
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    <a
                        href="#"
                        v-if="!['admin', 'operator'].includes(data.row.type)"
                        class="-ml-px relative inline-flex items-center px-2.5 py-1.5 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <information-circle-icon class="w-4 h-4"></information-circle-icon>
                    </a>
                    <button
                        @click="$root.openModal(modal, 'update', data.row)"
                        class="-ml-px relative inline-flex items-center px-2.5 py-1.5 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <pencil-icon class="w-4 h-4"></pencil-icon>
                    </button>
                    <button
                        v-if="!['admin'].includes(data.row.type)"
                        @click="$root.openModal(modal, 'delete', data.row)"
                        :disabled="data.row.variants_count > 0"
                        :class="[
                            '-ml-px relative inline-flex items-center px-2.5 py-1.5 rounded-r-md border border-gray-300 bg-white text-sm font-medium focus:z-10 focus:outline-none focus:ring-0',
                            data.row.variants_count > 0 ? 'hover:bg-gray-50 text-gray-400' : 'hover:bg-red-500 hover:text-white text-gray-700',
                        ]">
                        <trash-icon class="w-4 h-4"></trash-icon>
                    </button>
                </span>
            </template>
        </k-table>

        <update-modal
            v-if="modal.update.show"
            :element="modal.update.element"
            componentName="discount"
            :validations="validations"
            @closed="$root.closeModal(modal, 'update')"
            @confirmed="confirmModal('update')"
            :sending="modal.update.sending"
            title="Scheda utente"></update-modal>
        <delete-modal v-if="modal.delete.show" @closed="$root.closeModal(modal, 'delete')" @confirmed="confirmModal('delete')" :sending="modal.delete.sending"></delete-modal>
    </div>
</template>
<script>
    import { InformationCircleIcon, PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
    import { CalendarIcon } from "@heroicons/vue/24/outline";
    export default {
        components: { InformationCircleIcon, PencilIcon, TrashIcon, CalendarIcon },
        data() {
            return {
                tableColumn: [
                    { key: "id", title: "ID" },
                    { key: "type", title: "Tipologia" },
                    { key: "name", title: "Nome" },
                    { key: "code", title: "Codice" },
                    { key: "expires_at", title: "Scadenza" },
                    { key: "usage_limit", title: "Limite utilizzo" },
                    { key: "usage_limit_user", title: "Limite a utente" },
                    { key: "created_at", title: "Data creazione" },
                    { key: "actions", title: "Azioni" },
                ],
                types: {
                    percentage: { id: "percentage", label: "Importo percentuale", class: "bg-red-50 text-red-700 ring-red-600/20" },
                    fixed: { id: "fixed", label: "Importo fisso", class: "bg-yellow-50 text-yellow-700 ring-yellow-600/20" },
                    shipment: { id: "shipment", label: "Sconta spedizione", class: "bg-green-50 text-green-700 ring-green-600/20" },
                },
                modal: {
                    update: {
                        element: {},
                        show: false,
                        sending: false,
                    },
                    delete: {
                        element: {},
                        show: false,
                        sending: false,
                    },
                },
                emptyModel: {
                    id: null,
                    name: "",
                    code: "",
                    type: "percentage",
                    usage_limit: "",
                    usage_limit_user: "",
                    expires_at: null,
                    created_at: null,
                },
                filters: {
                    name: "",
                    code: "",
                    type: "",
                    expires_at: null,
                    created_at: null,
                },
                validations: {},
            };
        },
        created() {
            let uri = window.location.search.substring(1);
            let params = new URLSearchParams(uri);

            if (params.get("email") != null) {
                this.filters.email = params.get("email");
            }

            if (params.get("name") != null) {
                this.filters.name = params.get("name");
            }
        },
        methods: {
            fetchData() {
                this.$refs.table.fetchData();
            },
            confirmModal(type) {
                if (type == "delete") {
                    this.doDelete();
                } else {
                    this.doUpdate();
                }
            },
            doUpdate() {
                this.modal.update.sending = true;
                this.validations = {};

                this.$root.doUpdate(
                    "/backoffice/api/discount/patch-or-create",
                    this.modal.update.element,
                    (response) => {
                        this.modal.update.sending = false;
                        this.$root.$root.closeModal(this.modal, "update");
                        this.fetchData();
                    },
                    (err) => {
                        this.modal.update.sending = false;

                        if (typeof err.response != "undefined" && err.response.status == 422) {
                            this.validations = err.response.data.errors;
                        }
                    }
                );
            },
            doDelete() {
                this.modal.delete.sending = true;

                this.$root.doDelete(
                    "/backoffice/api/discount/delete",
                    this.modal.delete.element.id,
                    (response) => {
                        this.$root.$root.closeModal(this.modal, "delete");
                        this.fetchData();
                    },
                    (error) => {
                        this.$root.$root.closeModal(this.modal, "delete");
                        this.eventBus.emit("show-notification", {
                            title: "Errore",
                            text: "Impossibile eseguire questa operazione",
                            type: "danger",
                        });
                    }
                );
            },
        },
    };
</script>
