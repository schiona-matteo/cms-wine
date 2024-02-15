<template>
    <div>
        <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:px-6">
                <span v-if="isNew">Crea un nuovo prodotto</span>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-4 gap-6">
                    <k-select v-model="element.type" :field-options="types" :validation="validations" field-name="type" field-title="Tipologia"></k-select>
                    <k-select
                        v-model="element.available_for_order"
                        :field-options="[
                            {
                                id: true,
                                name: 'Sì',
                            },
                            {
                                id: false,
                                name: 'No',
                            },
                        ]"
                        :validation="validations"
                        field-name="available_for_order"
                        field-title="In vendita"></k-select>
                    <k-select
                        v-model="element.visibility"
                        :field-options="[
                            {
                                id: 'public',
                                name: 'Tutti',
                            },
                            {
                                id: 'logged_in',
                                name: 'Solo registrati',
                            },
                            {
                                id: 'only_manual',
                                name: 'Solo manuale',
                            },
                        ]"
                        :validation="validations"
                        field-name="visibility"
                        field-title="Visibilità"></k-select>
                    <k-select
                        v-model="element.is_virtual"
                        :field-options="[
                            {
                                id: true,
                                name: 'Sì',
                            },
                            {
                                id: false,
                                name: 'No',
                            },
                        ]"
                        :validation="validations"
                        field-name="is_virtual"
                        field-title="Prodotto virtuale"></k-select>
                    <k-input class="col-span-2" v-model="element.name" :validation="validations" field-name="name" field-title="Titolo"></k-input>
                    <k-input class="col-span-2" v-model="element.category" :validation="validations" field-name="category" field-title="Categoria"></k-input>
                </div>
                <div v-if="element.visibility != 'only_manual'" class="mt-4 divide-y divide-gray-200 overflow-hidden rounded-lg bg-gray-50 border border-gray-200 shadow" v-for="lang in langs" :key="lang.key">
                    <div class="px-4 py-2">{{ lang.name }}</div>
                    <div class="px-4 py-4">
                        <div class="flex space-x-4">
                            <k-textarea class="flex-1" v-model="element['subtitle_' + lang.key]" :validation="validations" :field-name="'subtitle_' + lang.key" field-title="Sottotitolo"></k-textarea>
                            <k-textarea class="flex-1" v-model="element['description_' + lang.key]" :validation="validations" :field-name="'description_' + lang.key" field-title="Descrizione"></k-textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:px-6">
                <span>Impostazioni avanzate</span>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex space-x-4 justify-between">
                    <k-input class="flex-1" v-model="element.limited_buy" :validation="validations" field-name="limited_buy" field-type="number" field-title="Limite massimo vendite"></k-input>
                    <k-input class="flex-1" v-model="element.limited_buy_for_user" :validation="validations" field-name="limited_buy_for_user" field-type="number" field-title="Limite massimo vendite / utente"></k-input>
                    <k-select
                        class="flex-1"
                        v-model="element.discountable"
                        :field-options="[
                            {
                                id: true,
                                name: 'Sì',
                            },
                            {
                                id: false,
                                name: 'No',
                            },
                        ]"
                        :validation="validations"
                        field-name="discountable"
                        field-title="Permetti sconto"></k-select>
                    <k-select
                        class="flex-1"
                        v-model="element.prevent_bankwire"
                        :field-options="[
                            {
                                id: true,
                                name: 'Sì',
                            },
                            {
                                id: false,
                                name: 'No',
                            },
                        ]"
                        :validation="validations"
                        field-name="prevent_bankwire"
                        field-title="Inibisci bonifico"></k-select>
                </div>
            </div>
        </div>
        <div class="mt-4 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:px-6">
                <span>Varianti</span>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Riferimento</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">In vendita</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Attributi</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Prezzo</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Azioni</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(variant, index) in variants" :key="index">
                            <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <div class="font-medium text-gray-900">{{ variant.code }}</div>
                            </td>
                            <td class="px-3 py-3.5 text-sm text-gray-500">{{ variant.available_for_order }}</td>
                            <td class="px-3 py-3.5 text-sm text-gray-500">
                                <!-- <div class="flex flex-col space-y-1 divide-y">
                                    <div v-for="(attribute, index) in variant.attributes" :key="index">
                                        <strong>{{ attribute.key.label }}:</strong> {{ attribute.value.label }}
                                    </div>
                                </div> -->
                                <div class="flex space-x-4">
                                    <span v-for="(attribute, index) in variant.attributes" :key="index" class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700"
                                        >{{ attribute.key.label }}: {{ attribute.value.label }}</span
                                    >
                                </div>
                            </td>
                            <td class="px-3 py-3.5 text-sm text-gray-500">{{ variant.price }}</td>
                            <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            product: {
                type: Object,
            },
            validations: {
                type: Object,
                default: () => {
                    return {};
                },
            },
        },
        computed: {
            isNew() {
                return this.element.id == null;
            },
        },
        data() {
            return {
                langs: [
                    { key: "it", name: "Italiano" },
                    { key: "en", name: "Inglese" },
                ],
                variants: [
                    {
                        code: "bottiglia-2024",
                        available_for_order: true,
                        attributes: [
                            {
                                key: { name: "format", label: "Formato" },
                                value: { name: "bottiglia", label: "Bottiglia" },
                            },
                            {
                                key: { name: "year", label: "Anno" },
                                value: { name: "2024", label: "2024" },
                            },
                        ],
                        price: 40,
                    },
                    {
                        code: "magnum-2024",
                        available_for_order: true,
                        attributes: [
                            {
                                key: { name: "format", label: "Formato" },
                                value: { name: "magnum", label: "Magnum" },
                            },
                            {
                                key: { name: "year", label: "Anno" },
                                value: { name: "2024", label: "2024" },
                            },
                        ],
                        price: 40,
                    },
                ],
                element: null,
                types: [
                    { id: "wine", name: "Vino" },
                    { id: "grappa", name: "Grappa" },
                    { id: "accessory", name: "Accessori" },
                    { id: "visit", name: "Visita" },
                ],
                visibilities: [
                    { id: "public", name: "A tutti" },
                    { id: "logged_in", name: "Solo registrati" },
                    { id: "only_manual", name: "Solo manuale" },
                ],
            };
        },
        created() {
            this.element = { ...this.product };
        },
    };
</script>
