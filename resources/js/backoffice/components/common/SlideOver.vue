<template>
    <TransitionRoot as="template" :show="open" :class="open && '!pointer-events-auto !opacity-100'" class="transition-all opacity-0 pointer-events-none">
        <Dialog as="div" class="relative z-30" @close="close()">
            <div class="fixed inset-0" />

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden bg-transparent transition" :class="showBg && '!bg-black/20'">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <TransitionChild
                            as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-700"
                            enter-from="translate-x-full"
                            enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-from="translate-x-0"
                            leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <div class="flex h-full flex-col overflow-y-scroll bg-white py-6">
                                    <slot name="header">
                                        <div class="px-4 sm:px-6">
                                            <div class="flex items-start justify-between">
                                                <div class="ml-3 flex h-7 items-center">
                                                    <button type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-highlight focus:ring-offset-2" @click="close()">
                                                        <span class="sr-only">Close panel</span>
                                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </slot>
                                    <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                        <slot></slot>
                                    </div>
                                    <slot name="footer"> </slot>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
    import { ref } from "vue";
    import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from "@headlessui/vue";
    import { XMarkIcon } from "@heroicons/vue/24/outline";

    const open = ref(true);

    const emit = defineEmits(["close"]);

    const showBg = ref(false);

    onMounted(() => {
        open.value = true;
        setTimeout(() => {
            showBg.value = true;
        });
    });

    function close() {
        showBg.value = false;
        open.value = false;

        setTimeout(() => {
            emit("close", {});
        }, 500);
    }
</script>
