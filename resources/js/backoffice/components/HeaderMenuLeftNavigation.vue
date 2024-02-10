<template>
    <div>
        <a
            :href="item.href"
            :class="[isCurrent && !item.hasChild ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'px-2 py-2 group flex items-center text-sm font-medium rounded-md']"
            @click.prevent="openItem()">
            <component v-if="typeof item.icon != 'undefined'" :is="item.icon" :class="[isCurrent ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
            {{ item.name }}
            <template v-if="item.hasChild">
                <component :is="isOpen ? 'ChevronDownIcon' : 'ChevronRightIcon'" class="ml-2 flex-shrink-0 h-4 w-4 text-white" aria-hidden="true" />
            </template>
        </a>
        <div v-if="item.hasChild" v-show="isOpen" class="flex flex-col mt-2 ml-4">
            <a
                v-for="child in item.children"
                :key="child.name"
                :href="child.href"
                :class="[isChildCurrent(child) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'group flex items-center px-4 py-2 text-sm font-medium rounded-md']">
                <span :class="[isChildCurrent(child) ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true">•</span>
                {{ child.name }}
            </a>
        </div>
    </div>
</template>
<script>
    import { ChevronRightIcon, ChevronDownIcon } from "@heroicons/vue/24/outline";
    export default {
        components: {
            ChevronRightIcon,
            ChevronDownIcon,
        },
        props: ["item", "current"],
        methods: {
            openItem() {
                if (this.item.hasChild) {
                    this.item.isOpen = !this.item.isOpen;
                } else {
                    location.href = this.item.href;
                }
            },
            isChildCurrent(child) {
                return child.route_name == this.current;
            },
        },
        computed: {
            isOpen() {
                return this.item.isOpen || this.isCurrentSubRoute;
            },
            isCurrent() {
                if (this.item.hasChild == false) {
                    return this.item.route_name == this.current;
                } else {
                    return this.isCurrentSubRoute;
                }
            },
            isCurrentSubRoute() {
                return this.current.includes(this.item.route_name);
            },
        },
    };
</script>
