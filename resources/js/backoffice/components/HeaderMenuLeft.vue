<template>
    <div>
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="relative z-40 md:hidden" @close="sidebarOpen = false">
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>

                <div class="fixed inset-0 flex z-40">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full">
                        <DialogPanel class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
                            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                                <div class="flex-shrink-0 flex items-center px-4">
                                    <img class="h-12 w-auto" :src="logo.mobile" :alt="appName" />
                                </div>
                                <nav class="mt-5 px-2 space-y-1">
                                    <HeaderMenuLeftNavigation v-for="(item, index) in navigation" :key="index" :item="item" :current="current" />
                                </nav>
                            </div>
                            <div class="flex-shrink-0 flex bg-gray-700 p-4">
                                <div class="ml-3">
                                    <p class="text-base font-medium text-white">
                                        {{ user.name }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-400 group-hover:text-gray-300">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="flex-shrink-0 w-14">
                        <!-- Force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex-1 flex flex-col min-h-0 bg-gray-800">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <img class="h-12 w-auto" :src="logo.desktop" :alt="appName" />
                    </div>
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        <HeaderMenuLeftNavigation v-for="(item, index) in navigation" :key="index" :item="item" :current="current" />
                    </nav>
                </div>
                <div class="flex-shrink-0 flex bg-gray-700 p-4">
                    <div class="flex-shrink-0 w-full group block">
                        <div class="flex flex-col space-y-8">
                            <div>
                                <p class="text-sm font-medium text-white">
                                    {{ user.name }}
                                </p>
                                <p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">
                                    {{ user.email }}
                                </p>
                                <div class="mt-5">
                                    <button class="text-sm font-medium text-white" @click="logout">Esci</button>
                                </div>
                            </div>
                            <div class="mx-auto">
                                <img class="h-8 w-auto fill-white color-white" src="/kiboko-logo-expanded.svg" alt="KIBOKO LOGO" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from "@headlessui/vue";
    import { HomeIcon, UserIcon, XMarkIcon, CogIcon } from "@heroicons/vue/24/outline";
    import HeaderMenuLeftNavigation from "./HeaderMenuLeftNavigation.vue";
    export default {
        components: {
            Dialog,
            DialogPanel,
            TransitionChild,
            TransitionRoot,
            XMarkIcon,
            HomeIcon,
            UserIcon,
            HeaderMenuLeftNavigation,
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
                        icon: HomeIcon,
                        route_name: "backoffice.dashboard",
                        hasChild: false,
                    },
                    {
                        name: "Prodotti",
                        href: "/backoffice/prodotti",
                        icon: UserIcon,
                        route_name: "backoffice.products",
                        hasChild: false,
                    },
                    {
                        name: "Sconti",
                        href: "/backoffice/sconti",
                        icon: UserIcon,
                        route_name: "backoffice.discounts",
                        hasChild: false,
                    },
                    {
                        name: "Utenti",
                        href: "/backoffice/utenti",
                        icon: UserIcon,
                        route_name: "backoffice.users",
                        hasChild: false,
                    },
                    {
                        name: "Impostazioni",
                        href: "/backoffice/impostazioni",
                        icon: CogIcon,
                        route_name: "backoffice.settings",
                        hasChild: true,
                        isOpen: false,
                        children: [
                            {
                                name: "Demo",
                                href: "/backoffice/impostazioni/demo",
                                route_name: "backoffice.settings.demo",
                                hasChild: false,
                            },
                        ],
                    },
                ],
                sidebarOpen: false,
            };
        },
        methods: {
            logout() {
                axios.post("/auth/logout").then((response) => {
                    window.location.replace("/auth/login");
                });
            },
        },
        mounted() {
            this.eventBus.on("sidebarOpen", () => {
                this.sidebarOpen = true;
            });
        },
    };
</script>
