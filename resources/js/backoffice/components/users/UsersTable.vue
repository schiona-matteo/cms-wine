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
            fetch-url="/backoffice/api/users"
            @addClicked="$root.openModal(modal, 'update', emptyModel)"
            @updateClicked="$root.openModal(modal, 'update', $event)"
            @deleteClicked="$root.openModal(modal, 'delete', $event)">
            <template v-slot:column-created_at="data">{{ dayjs(data.row.created_at).format("DD/MM/YYYY HH:mm") }}</template>
            <template v-slot:column-actions="data">
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    <button
                        @click="$root.openModal(modal, 'update', data.row)"
                        class="-ml-px relative inline-flex items-center px-2.5 py-1.5 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <pencil-icon class="w-4 h-4"></pencil-icon>
                    </button>
                    <button
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
            componentName="user"
            :validations="validations"
            @closed="$root.closeModal(modal, 'update')"
            @confirmed="confirmModal('update')"
            :sending="modal.update.sending"
            title="Scheda utente"></update-modal>
        <delete-modal v-if="modal.delete.show" @closed="$root.closeModal(modal, 'delete')" @confirmed="confirmModal('delete')" :sending="modal.delete.sending"></delete-modal>
    </div>
</template>
<script>
    import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
    import { CalendarIcon } from "@heroicons/vue/24/outline";
    export default {
        components: { PencilIcon, TrashIcon, CalendarIcon },
        data() {
            return {
                tableColumn: [
                    { key: "id", title: "ID" },
                    { key: "name", title: "Nome" },
                    { key: "email", title: "Email" },
                    { key: "created_at", title: "Data inserimento" },
                    { key: "actions", title: "Azioni" },
                ],
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
                    email: "",
                    name: "",
                    password: "",
                },
                filters: {
                    name: "",
                    email: "",
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
                    "/backoffice/api/user/patch-or-create",
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
                    "/backoffice/api/user/delete",
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
