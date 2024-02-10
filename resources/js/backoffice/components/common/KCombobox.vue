<template>
    <div>
        <label v-if="fieldTitle" :for="fieldName" :class="['block text-sm font-medium', hasError ? 'text-red-500' : 'text-gray-700']" v-text="fieldTitle"></label>
        <div :class="['mt-1', hasError ? 'relative rounded-md shadow-sm' : '']">
            <div class="flex space-x-2 items-center">
                <Combobox as="div" class="flex-1" v-model="selectedItem" @update:model-value="(item) => updateModel(item)" :disabled="fieldDisabled">
                    <div class="relative">
                        <ComboboxInput
                            :placeholder="fieldPlaceholder"
                            class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            @change="search($event.target.value)"
                            :display-value="inputResultValue" />
                        <ComboboxButton @click="clearSelected" v-if="modelValue != null && !fieldDisabled" class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                            <XMarkIcon class="h-5 w-5 hover:text-red-500" aria-hidden="true" />
                        </ComboboxButton>
                        <ComboboxButton v-else class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </ComboboxButton>

                        <ComboboxOptions v-if="availableItems.length > 0" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            <ComboboxOption v-for="item in availableItems" :key="item.id" :value="item" as="template" v-slot="{ active }" :disabled="item.disabled ? true : false">
                                <li :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-indigo-600 text-white' : 'text-gray-900']">
                                    <span :class="['block truncate']">
                                        <slot name="resultName" v-bind:row="item">
                                            <div class="flex space-x-2">
                                                <span v-if="item.id == null">[NUOVO]</span>
                                                <slot name="resultSlot" v-bind:row="item">
                                                    <span>
                                                        {{ item[props.resultKeyName] }}
                                                    </span>
                                                </slot>
                                            </div>
                                        </slot>
                                    </span>
                                </li>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
            </div>
            <p v-if="hasError" class="mt-1 text-left text-xs text-red-600" :id="props.fieldName + '-error'" v-text="getError"></p>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from "vue";
    import { ChevronUpDownIcon, XMarkIcon } from "@heroicons/vue/20/solid";
    import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from "@headlessui/vue";

    const emit = defineEmits(["selected", "update:modelValue", "resetValidation"]);
    const props = defineProps({
        fieldTitle: {
            type: String,
            default: "",
        },
        fieldName: String,
        fieldPlaceholder: String,
        inputResultValue: {
            type: Function,
            default: (item) => {
                return item.name;
            },
        },
        autoselectFirst: {
            type: Boolean,
            default: true,
        },
        createMissing: {
            type: Boolean,
            default: false,
        },
        resultKeyName: {
            type: String,
            default: "name",
        },
        searchUrl: String,
        modelValue: String | Number | Object,
        fieldDisabled: {
            type: Boolean,
            default: false,
        },
        validation: {
            type: Object,
            default: () => {
                return {};
            },
        },
        exclude: {
            type: Array,
            default: () => {
                return [];
            },
        },
        extraParams: {
            type: Object,
            default: () => {
                return {};
            },
        },
    });

    onMounted(() => {
        if (props.modelValue != null) {
            availableItems.value.push(props.modelValue);
            selectedItem.value = props.modelValue;
        }
    });

    const selectedItem = ref({});

    const availableItems = ref([]);

    function search(query) {
        axios
            .post(props.searchUrl, {
                take: 25,
                exclude: props.exclude,
                query,
                params: props.extraParams,
            })
            .then((response) => {
                availableItems.value = response.data.items;
                if (props.autoselectFirst) {
                    if (availableItems.value.length == 1) {
                        selectedItem.value = availableItems.value[0];
                        updateModel(availableItems.value[0]);
                    }
                }

                if (props.createMissing && query.length > 3) {
                    let perfectMath = false;
                    availableItems.value.forEach((item) => {
                        if (item.name == query) {
                            perfectMath = true;
                        }
                    });

                    if (!perfectMath) {
                        availableItems.value.push({
                            id: null,
                            create: true,
                            name: query,
                        });
                    }
                }
            });
    }

    function resetValidator() {
        emit("resetValidation", props.fieldName);
    }

    const hasError = computed(() => {
        return typeof props.validation[props.fieldName] != "undefined" && props.validation[props.fieldName] != null;
    });

    const getError = computed(() => {
        if (hasError.value == true) {
            return props.validation[props.fieldName][0];
        }

        return "";
    });

    function updateModel(item) {
        resetValidator();
        emit("selected", item);
    }

    function clearSelected() {
        resetValidator();
        selectedItem.value = {};
        emit("selected", null);
        emit("update:modelValue", null);
    }

    search("");
</script>
