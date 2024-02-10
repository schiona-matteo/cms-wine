<template>
    <div>
        <label v-if="fieldTitle" :for="fieldName" :class="['block text-sm font-medium', hasError ? 'text-red-500' : 'text-gray-700']" v-text="fieldTitle"></label>
        <select
            :value="modelValue"
            @change="updateValue"
            :name="fieldName"
            :id="fieldName"
            :disabled="fieldDisabled"
            :class="[
                'mt-1 block w-full pl-3 pr-10 py-1.5 text-base sm:text-sm rounded-md',
                hasError ? 'border-red-600 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-400',
            ]">
            <option v-for="(op, index) in fieldOptions" :key="index" :value="op.id" v-text="op.name" :disabled="op.disabled ? true : false"></option>
        </select>

        <p v-if="hasError" class="mt-1 text-xs text-red-600" :id="fieldName + '-error'" v-text="getError"></p>
    </div>
</template>
<script>
    import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";
    export default {
        components: { ExclamationCircleIcon },
        props: {
            fieldTitle: {
                type: String,
                default: "",
            },
            fieldName: String,
            modelValue: [String, Number, Boolean],
            fieldDisabled: {
                type: Boolean,
                default: false,
            },
            fieldOptions: Array,
            validation: {
                type: Object,
                default: () => {
                    return {};
                },
            },
            fieldCaster: {
                type: Function,
                default: (val) => {
                    return val;
                },
            },
        },
        methods: {
            updateValue(event) {
                this.$emit("update:modelValue", this.fieldCaster(event.target.value));
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
