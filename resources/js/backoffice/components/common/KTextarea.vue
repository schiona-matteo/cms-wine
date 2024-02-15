<template>
    <div>
        <label v-if="fieldTitle" :for="fieldName" :class="['block text-sm font-medium', hasError ? 'text-red-500' : 'text-gray-700']" v-text="fieldTitle"></label>
        <div :class="['mt-1', hasError ? 'relative rounded-md shadow-sm' : '']">
            <textarea
                :disabled="fieldDisabled"
                :value="modelValue"
                @input="updateValue"
                :rows="fieldRows"
                :name="fieldName"
                :id="fieldName"
                :class="[
                    'block w-full sm:text-sm rounded-md',
                    hasError ? 'pr-10 border-red-600 text-red-900 placeholder-red-600 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'shadow-sm focus:ring-highlight focus:border-highlight border-gray-300',
                    fieldDisabled ? 'bg-gray-100' : '',
                ]"
                :placeholder="fieldPlaceholder"></textarea>
            <div v-if="hasError" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
            </div>
        </div>
        <p v-if="hasError" class="mt-1 text-left text-xs text-red-600" :id="fieldName + '-error'" v-text="getError"></p>
    </div>
</template>
<script>
    import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";
    export default {
        components: { ExclamationCircleIcon },
        props: {
            fieldRows: {
                type: Number,
                default: 4,
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
            modelValue: String,
            fieldPlaceholder: String,
            validation: {
                type: Object,
                default: () => {
                    return {};
                },
            },
        },
        methods: {
            updateValue(event) {
                this.$emit("update:modelValue", event.target.value);
                this.resetValidator();
            },
            resetValidator() {
                this.validation[this.fieldName] = undefined;
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
