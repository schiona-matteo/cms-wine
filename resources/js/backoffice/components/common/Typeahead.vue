<template>
    <div :id="elementId" v-if="elementId != ''" class="w-full" @click="closeOther">
        <div @click="hideMenu" v-if="filteredList.length > 0 && showSearchItems == true" class="fixed inset-0 z-10 w-100 h-100"></div>
        <label v-if="title != null" class="block text-sm font-medium text-gray-700" v-text="title"></label>
        <div class="z-20 mt-1 relative rounded-md shadow-sm">
            <input
                @click="showMenu"
                type="text"
                :class="classProps"
                :placeholder="placeholder"
                aria-label="Search"
                v-model="search"
                @input="showSearchItems = true"
                :autocomplete="'search-' + elementId"
                role="presentation"
                ref="searchBox" />
            <div v-if="showClean" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="cleanElement">
                <XMarkIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </div>
        </div>
        <aside v-if="filteredList.length > 0 && showSearchItems == true" class="absolute z-20 flex flex-col items-start w-64 bg-white border rounded-md shadow-md mt-1" role="menu" aria-labelledby="menu-heading">
            <ul class="flex flex-col w-full max-h-64 overflow-y-auto">
                <li
                    class="px-2 py-3 space-x-2 hover:bg-blue-600 hover:text-white focus:bg-blue-600 focus:text-white focus:outline-none"
                    v-for="(item, index) in filteredList"
                    :key="index"
                    @click="
                        selectSearchItem(item);
                        showSearchItems = false;
                    ">
                    <div class="flex">
                        <component v-if="typeof item.icon != 'undefined'" :is="item.icon" :class="[item.name == selected ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                        <span>{{ item.name }}</span>
                    </div>
                </li>
            </ul>
        </aside>
    </div>
</template>

<script>
    import { XMarkIcon } from "@heroicons/vue/24/solid";

    export default {
        components: { XMarkIcon },
        props: {
            maxResults: {
                type: Number,
                default: 10,
            },
            title: {
                type: String,
                default: null,
            },
            lists: {
                type: Array,
                default: [],
            },
            ignoredList: {
                type: Array,
                default: [],
            },
            clearInputWhenClicked: {
                type: Boolean,
                default: false,
            },
            showMenuOnClear: {
                type: Boolean,
                default: true,
            },
            placeholder: {
                type: String,
                default: "Search here...",
            },
            initValue: {
                type: [String, Number],
                default: "",
            },
            inputClass: {
                type: Array,
                default: ["shadow-sm", "focus:ring-highlight", "focus:border-highlight", "block", "w-full", "sm:text-sm", "border-gray-300", "rounded-md"],
            },
        },
        data() {
            return {
                focus: false,
                search: "",
                selectedItem: "",
                showSearchItems: false,
                isMouseOverList: false,
                searchItemList: this.lists,
                elementId: "",
            };
        },
        computed: {
            filteredList() {
                let c = 0;
                return this.searchItemList.filter((item) => {
                    if (c < this.maxResults && item.name.toLowerCase().includes(this.search.toLowerCase()) && !this.checkIgnoreListItem(item.id)) {
                        c++;
                        return true;
                    }
                    return false;
                });
            },

            classProps() {
                return [...this.inputClass, this.showClean ? "pr-10" : ""];
            },

            showClean() {
                return this.search.length > 0 || this.selectedItem != "";
            },
        },
        methods: {
            closeOther() {
                this.eventBus.emit("typehead.open", this.elementId);
            },
            cleanElement() {
                this.search = "";
                this.selectedItem = "";
                this.showSearchItems = false;
                this.$emit("selected", null);
                if (this.showMenuOnClear) {
                    this.showMenu();
                }
            },
            selectSearchItem(item) {
                this.search = item.name;
                this.selectedItem = item.name;
                this.showSearchItems = false;
                this.focus = false;
                this.$emit("selected", item);
                if (this.clearInputWhenClicked) {
                    this.search = "";
                }
            },

            checkIgnoreListItem(itemId) {
                if (this.ignoredList.length > 0) {
                    const result = this.ignoredList.some((ignoreListItem) => {
                        return ignoreListItem == itemId;
                    });
                    return result;
                }
                return false;
            },

            hideMenu() {
                if (this.showSearchItems == true) {
                    this.showSearchItems = false;
                }
            },

            showMenu() {
                if (this.showSearchItems == false) {
                    this.showSearchItems = true;
                }
            },
        },

        created() {
            this.elementId = Math.random()
                .toString(36)
                .replace(/[^a-z]+/g, "");
            if (this.initValue != "") {
                const selected = this.lists.filter((item) => {
                    if (item.id == this.initValue) {
                        return true;
                    }
                    return false;
                });

                if (selected.length > 0) {
                    this.selectedItem = selected[0].name;
                    this.search = selected[0].name;
                }
            }
        },

        mounted() {
            this.eventBus.on("typehead.open", (payload) => {
                if (payload != this.elementId && this.showSearchItems) {
                    this.hideMenu();
                }
            });
        },
    };
</script>
