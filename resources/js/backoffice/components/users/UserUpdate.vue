<template>
    <div>
        <div class="flex flex-col space-y-4">
            <div class="flex space-x-4">
                <k-input class="flex-1" v-model="element.name" :validation="validations" field-name="name" field-title="Nome"></k-input>
                <k-input class="flex-1" v-model="element.email" :validation="validations" field-type="email" field-name="email" field-title="Email"></k-input>
            </div>
            <div class="flex space-x-4">
                <k-input class="flex-1" v-model="element.birthday" :validation="validations" field-type="date" field-name="birthday" field-title="Data di nascita"></k-input>
                <k-input class="flex-1" v-model="element.password" :validation="validations" field-type="password" field-name="password" field-title="Password"></k-input>
            </div>
            <div class="flex space-x-4">
                <k-select class="flex-1" v-model="element.type" :field-options="types" :validation="validations" field-name="type" field-title="Tipologia"></k-select>
                <k-select class="flex-1" v-model="element.source" :field-options="sources" :validation="validations" field-name="source" field-title="Origine"></k-select>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            element: {
                type: Object,
            },
            validations: {
                type: Object,
                default: () => {
                    return {};
                },
            },
        },
        data() {
            return {
                types: [
                    { id: "admin", name: "Amministratore" },
                    { id: "operator", name: "Operatore" },
                    { id: "user", name: "Utente" },
                    { id: "guest", name: "Ospite" },
                ],
                sources: [
                    { id: "sito", name: "Sito" },
                    { id: "visita", name: "Visita" },
                    { id: "negozio", name: "Negozio" },
                    { id: "manuale", name: "Manuale" },
                    { id: "altro", name: "Altro" },
                ],
            };
        },
    };
</script>
