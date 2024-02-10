<template>
    <div>
        <div aria-live="assertive" class="fixed flex-col inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-[9999]">
            <div class="w-full flex flex-col items-center sm:items-end">
                <div v-for="(notification, index) in notifications" :key="index">
                    <div
                        class="relative max-w-sm bg-white shadow-lg rounded pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden opacity-0 -right-[500px] mb-3 transition-all w-[360px] !duration-500 min-h-[78px]"
                        :class="{
                            '!opacity-100 !right-0': notification.shown,
                            '!h-0 !mb-0 -z-1 !min-h-0': !notification.shown,
                        }">
                        <div class="p-3 border-t-4" :class="'border-' + icons[notification.type].color">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <!-- Heroicon name: outline/check-circle -->
                                    <svg class="h-6 w-6" :class="'text-' + icons[notification.type].color" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="icons[notification.type].d" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-chicago-600 max-w-[88%] truncate">
                                        {{ notification.title }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ notification.text }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex">
                                    <button
                                        type="button"
                                        class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        @click="dismissNotifiation(notification.id)">
                                        <span class="sr-only">Close</span>
                                        <!-- Heroicon name: solid/x -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path
                                                fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- avoid tailwind purge -->
        <span class="text-green-400 text-red-400 text-amber-500 text-sky-400 border-green-400 border-red-400 border-amber-500 border-sky-400 hidden"></span>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                counter: 0,
                interval: null,
                notifications: [],
                icons: {
                    success: {
                        d: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
                        color: "green-400",
                    },
                    danger: {
                        d: "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z",
                        color: "red-400",
                    },
                    warning: {
                        d: "M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
                        color: "yellow-400",
                    },
                    info: {
                        d: "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
                        color: "sky-400",
                    },
                },
            };
        },
        mounted() {
            this.startInterval();
            this.eventBus.on("show-notification", (payload) => {
                this.counter++;

                this.notifications.push({
                    id: this.counter,
                    title: payload.title,
                    text: payload.text,
                    shown: false,
                    type: payload.type,
                    duration: payload.duration,
                    dismissed: false,
                });

                setTimeout(() => {
                    const index = this.notifications.findIndex((object) => {
                        return object.id === this.counter;
                    });
                    this.notifications[index].shown = true;
                }, 500);
            });

            // summon example

            /*
      this.eventBus.emit('show-notification', {
        title: 'Test',
        text: 'Lorem ipsum',
        type:'success',
        duration: 5000,
      })
      */
        },

        methods: {
            startInterval() {
                this.interval = setInterval(() => {
                    if (this.notifications.length === 0) {
                        return;
                    }

                    this.notifications.forEach((item, index) => {
                        if (item.duration > 0) {
                            item.duration -= 500;
                            return;
                        }

                        if (item.duration === 0) {
                            item.shown = false;
                            item.duration -= 500;
                        }
                    });
                }, 500);
            },

            dismissNotifiation(id) {
                const index = this.notifications.findIndex((object) => {
                    return object.id === id;
                });

                this.notifications[index].shown = false;
            },
        },
    };
</script>
