<template>
    <div>
        <label v-if="fieldTitle" :for="fieldName" :class="['block text-sm font-medium', hasError ? 'text-red-500' : 'text-gray-700']" v-text="fieldTitle"></label>
        <div :class="['mt-1', hasError ? 'relative rounded-md shadow-sm' : '']">
            <input
                :disabled="fieldDisabled"
                :value="modelValue"
                :type="fieldType"
                :name="fieldName"
                :id="fieldName"
                :maxlength="fieldMax"
                @input="updateValue"
                :class="[
                    inputWidth,
                    'block sm:text-sm rounded-md',
                    textDirection ? textDirection : '',
                    hasError ? 'pr-10 border-red-500 text-red-800 placeholder-red-500 focus:outline-none focus:ring-0 focus:border-red-500' : 'focus:ring-0 focus:border-gray-400 border-gray-300',
                    fieldDisabled ? 'bg-gray-100' : '',
                    fieldSize ? fieldSize : '',
                ]"
                :placeholder="fieldPlaceholder" />
            <div v-if="hasError" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
            </div>
        </div>
        <p v-if="hasError" class="mt-1 text-left text-xs text-red-600" :id="fieldName + '-error'" v-text="getError"></p>
    </div>
</template>
<script setup>
    import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";
    import { computed, ref } from "vue";

    const emit = defineEmits(["update:modelValue"]);

    const props = defineProps({
        inputWidth: {
            type: String,
            default: "w-full py-1.5",
        },
        fieldTitle: {
            type: String,
            default: "",
        },
        fieldType: {
            type: String,
            default: "text",
        },
        textDirection: {
            type: String,
            default: "",
        },
        fieldMin: {
            type: [String, Number],
            default: "",
        },
        fieldMax: {
            type: [String, Number],
            default: "",
        },
        fieldName: String,
        modelValue: [String, Number],
        fieldPlaceholder: String,
        fieldDisabled: {
            type: Boolean,
            default: false,
        },
        fieldSize: {
            type: String,
            default: "",
        },
        validation: {
            type: Object,
            default: () => {
                return {};
            },
        },
    });

    const validator = ref({});

    const hasError = computed(() => {
        return typeof props.validation[props.fieldName] != "undefined";
    });

    const getError = computed(() => {
        if (hasError == true) {
            return props.validation[props.fieldName][0];
        }

        return "";
    });

    function updateValue(event) {
        emit("update:modelValue", event.target.value);
        resetValidator();
    }

    function resetValidator() {
        props.validation[props.fieldName] = undefined;
    }

    if (typeof props.validation[props.fieldName] != "undefined") {
        validator.value = props.validation[props.fieldName];
    }
</script>
