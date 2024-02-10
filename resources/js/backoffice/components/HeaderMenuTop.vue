<template>
    <Disclosure as="nav" class="bg-white border-b border-gray-200" v-slot="{ open }">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="block lg:hidden h-6 w-auto" :src="logo.mobile" :alt="appName" />
                        <img class="hidden lg:block h-6 w-auto" :src="logo.desktop" :alt="appName" />
                    </div>
                    <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                        <a
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            :class="[
                                item.route_name == current ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium',
                            ]"
                            :aria-current="item.route_name == current ? 'page' : undefined">
                            {{ item.name }}
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Profile dropdown -->
                    <button type="button" class="ml-auto bg-white flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="logout">
                        <span class="sr-only">Chiudi sessione</span>
                        <ArrowRightOnRectangleIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <DisclosureButton class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Open main menu</span>
                        <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
            </div>
        </div>

        <DisclosurePanel class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <DisclosureButton
                    v-for="item in navigation"
                    :key="item.name"
                    as="a"
                    :href="item.href"
                    :class="[
                        item.route_name == current ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800',
                        'block pl-3 pr-4 py-2 border-l-4 text-base font-medium',
                    ]"
                    :aria-current="item.route_name == current ? 'page' : undefined">
                    {{ item.name }}
                </DisclosureButton>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div>
                        <div class="text-base font-medium text-gray-800">
                            {{ user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ user.email }}
                        </div>
                    </div>
                    <button type="button" class="ml-auto bg-white flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="logout">
                        <span class="sr-only">Chiudi sessione</span>
                        <ArrowRightOnRectangleIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script>
    import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
    import { ArrowRightOnRectangleIcon, Bars3Icon, XMarkIcon } from "@heroicons/vue/24/outline";
    export default {
        components: {
            Disclosure,
            DisclosureButton,
            DisclosurePanel,
            Menu,
            MenuButton,
            MenuItem,
            MenuItems,
            ArrowRightOnRectangleIcon,
            Bars3Icon,
            XMarkIcon,
        },
        props: {
            current: {
                type: String,
                default: "",
            },
            user: {
                type: Object,
                default: () => {
                    return {
                        name: "",
                        email: "",
                    };
                },
            },
        },
        data() {
            return {
                appName: "APP_NAME",
                logo: {
                    mobile: "/logo.svg",
                    desktop: "/logo.svg",
                },
                navigation: [
                    {
                        name: "Dashboard",
                        href: "/backoffice",
                        route_name: "backoffice.dashboard",
                    },
                    {
                        name: "Colori",
                        href: "/backoffice/colori",
                        route_name: "backoffice.colors",
                    },
                ],
            };
        },
        methods: {
            logout() {
                axios.post("/auth/logout").then((response) => {
                    window.location.replace("/auth/login");
                });
            },
        },
    };
</script>
