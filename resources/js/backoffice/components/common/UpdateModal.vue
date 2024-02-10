<template>
    <Modal @close="$emit('closed')" @confirm="$emit('confirmed')" :sending="sending" :classes="modalSize">
        <template v-slot:title>
            <div class="uppercase font-bold flex items-center">
                {{ title }}
            </div>
        </template>
        <template v-slot:content>
            <component :is="component" :element="element" :validations="validations" />
        </template>
    </Modal>
</template>
<script>
    import { markRaw } from "vue";
    import UserUpdate from "../users/UserUpdate.vue";

    export default {
        props: {
            modalSize: {
                type: String,
                default: "sm:max-w-2xl sm:w-full",
            },
            title: {
                type: String,
                default: "",
            },
            sending: {
                type: Boolean,
                default: false,
            },
            element: {
                type: Object,
                default: () => {
                    return {};
                },
            },
            validations: {
                type: Object,
                default: () => {
                    return {};
                },
            },
            componentName: {
                type: String,
                default: "",
            },
        },
        data() {
            return {
                components: {
                    user: markRaw(UserUpdate),
                },
            };
        },
        computed: {
            component() {
                return this.components[this.componentName];
            },
        },
    };
</script>
