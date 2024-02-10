<!-- eslint-disable vue/no-mutating-props -->
<template>
    <div class="w-full">
        <label v-if="fieldTitle" :for="fieldName" :class="['block text-sm font-medium', hasError ? 'text-red-500' : 'text-gray-700']" v-text="fieldTitle"></label>
        <div :class="['mt-1', hasError ? 'relative rounded-md shadow-sm' : '']">
            <VueMultiselect
                :value="modelValue"
                :options="options"
                :searchable="searchable"
                :taggable="taggable"
                :multiple="multiple"
                :disabled="fieldDisabled"
                @search-change="asyncSearch"
                :custom-label="customLabel"
                :loading="isLoading"
                :close-on-select="true"
                :hide-selected="true"
                :internal-search="false"
                :placeholder="fieldPlaceholder"
                select-label="Seleziona"
                label="name"
                track-by="code"
                @select="selectedEvent"
                @remove="removedEvent"
                deselect-label="Rimuovi selezione">
                <template #noOptions> Scrivi almeno {{ minSearch }} lettere </template>
                <template #noResult> Nessun risultato trovato </template>
            </VueMultiselect>
            <div v-if="hasError" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
            </div>
        </div>
        <p v-if="hasError" class="mt-1 text-left text-xs text-red-600" :id="fieldName + '-error'" v-text="getError"></p>
    </div>
</template>
<script>
    import "vue-multiselect/dist/vue-multiselect.css";
    import VueMultiselect from "vue-multiselect";
    import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";
    export default {
        components: { ExclamationCircleIcon, VueMultiselect },
        props: {
            customLabel: {
                type: Function,
                default: (val) => {
                    return val.name;
                },
            },
            searchUrl: {
                type: String,
            },
            searchable: {
                type: Boolean,
                default: true,
            },
            minSearch: {
                type: Number,
                default: 3,
            },
            taggable: {
                type: Boolean,
                default: false,
            },
            multiple: {
                type: Boolean,
                default: false,
            },
            fieldTitle: {
                type: String,
                default: "",
            },
            fieldDisabled: {
                type: Boolean,
                default: false,
            },
            fieldName: String,
            modelValue: [String, Object, Boolean, Array],
            fieldPlaceholder: String,
            validation: {
                type: Object,
                default: () => {
                    return {};
                },
            },
            additionalParams: {
                type: Object,
                default: () => {
                    return null;
                },
            },
        },
        data() {
            return {
                model: null,
                options: [],
                isLoading: false,
            };
        },
        mounted() {
            if (typeof this.validation != "undefined" && typeof this.validation[this.fieldName] != "undefined") {
                this.validator = this.validation[this.fieldName];
            }

            if (this.modelValue != null) {
                this.model = this.modelValue;

                this.options.push(this.modelValue);
            }
        },
        methods: {
            selectedEvent(params) {
                this.model = params;
                // this.$emit("selected", params);
                // this.updateValue(params);
            },
            removedEvent(params) {
                // this.$emit("removed", params);
                // this.resetValidator();
            },
            asyncSearch(query) {
                if (query.length < this.minSearch) {
                    return;
                }

                this.isLoading = true;
                let el = {};

                if (this.additionalParams != null) {
                    el = this.additionalParams;
                }

                el.query = query.toLowerCase();

                axios
                    .post(this.searchUrl, el)
                    .then((response) => {
                        this.options = response.data;
                        this.isLoading = false;
                    })
                    .catch((err) => {
                        this.isLoading = false;
                    });
            },
            updateValue(event) {
                this.$emit("update:modelValue", event.value);
                this.resetValidator();
            },
            resetValidator() {
                this.$emit("resetValidation");
            },
        },
        computed: {
            hasError() {
                return typeof this.validation[this.fieldName] != "undefined";
            },
            getError() {
                if (this.hasError == true) {
                    return this.validation[this.fieldName][0];
                }

                return "";
            },
        },
    };
</script>
<style>
    div.multiselect__tags,
    div.multiselect__content-wrapper,
    ul.multiselect__content {
        border-color: rgb(209 213 219);
    }

    div.multiselect__tags input:focus {
        box-shadow: none;
    }
</style>
