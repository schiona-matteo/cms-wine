<template>
    <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-gray-100">
        <button
            type="button"
            class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
            @click="openSideBar">
            <span class="sr-only">Open sidebar</span>
            <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>
    </div>
</template>
<script>
    import { Bars3Icon } from "@heroicons/vue/24/outline";
    export default {
        components: {
            Bars3Icon,
        },
        methods: {
            openSideBar() {
                this.eventBus.emit("sidebarOpen");
            },
        },
    };
</script>
