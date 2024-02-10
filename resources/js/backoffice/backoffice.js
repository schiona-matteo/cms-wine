import _ from "lodash";
window._ = _;

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import { createApp } from "vue";
const app = createApp({
    data() {
        return {};
    },
    methods: {
        showNotification(title, message, type, duration = 5000) {
            this.eventBus.emit("show-notification", {
                title: title,
                text: message,
                type: type,
                duration: 5000,
            });
        },
        doUpdate(url, payload, callbacks, errorCallbacks) {
            axios
                .post(url, payload)
                .then((response) => {
                    let notificationMessage = "Operazione completata";

                    if (typeof response.data.message != "undefined") {
                        notificationMessage = response.data.message;
                    }

                    this.$root.showNotification("Operazione completata", notificationMessage, "success");

                    callbacks(response);
                })
                .catch((err) => {
                    let errorMessage = "Errore durante la richiesta";

                    if (typeof err.response != "undefined" && typeof err.response.data.message != "undefined") {
                        errorMessage = err.response.data.message;
                    }

                    this.$root.showNotification("Ops", errorMessage, "danger");

                    errorCallbacks(err);
                });
        },
        doDelete(url, id, callbacks) {
            axios
                .post(url, {
                    model_id: id,
                })
                .then((response) => {
                    callbacks(response);
                });
        },
        openModal(modal, type, element) {
            modal[type].element = _.cloneDeep(element);
            modal[type].show = true;
            modal[type].sending = false;
        },
        closeModal(modal, type) {
            modal[type].show = false;
            modal[type].sending = false;
            modal[type].element = {};
        },
    },
});

const files = require.context("./", true, /\.vue$/i);
files.keys().map((key) => app.component(key.split("/").pop().split(".")[0], files(key).default));

import mitt from "mitt";
const emitter = mitt();

app.config.globalProperties.eventBus = emitter;

const dayjs = require("dayjs");
require("dayjs/locale/it");

import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
app.component("date-picker", Datepicker);

dayjs.locale("it");
app.mixin({
    methods: {
        __(key, lang = "it") {
            if (typeof this.$root[lang][key] !== "undefined") {
                return this.$root[lang][key];
            }

            return key;
        },
    },
    data() {
        return {
            dayjs,
        };
    },
});

app.provide("emitter", emitter);

const pinia = createPinia();
app.use(pinia);

app.mount("#app");
