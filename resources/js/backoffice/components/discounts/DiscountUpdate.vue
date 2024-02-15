<template>
    <div>
        <div class="flex flex-col space-y-4">
            <div class="flex space-x-4">
                <k-input class="flex-1" v-model="element.name" :validation="validations" field-name="name" field-title="Nome"></k-input>
                <k-input class="flex-1" v-model="element.code" :validation="validations" field-name="code" field-title="Codice"></k-input>
            </div>
            <div class="flex space-x-4">
                <k-select class="flex-1" v-model="element.type" :field-options="types" :validation="validations" field-name="type" field-title="Tipologia"></k-select>
                <k-input class="flex-1" v-model="element.expires_at" :validation="validations" field-name="expires_at" field-type="date" field-title="Data di scadenza"></k-input>
            </div>
            <div class="flex space-x-4">
                <k-input class="flex-1" v-model="element.usage_limit" :validation="validations" field-type="number" field-name="usage_limit" field-title="Limite di utilizzo generale"></k-input>
                <k-input class="flex-1" v-model="element.usage_limit_user" :validation="validations" field-type="number" field-name="usage_limit_user" field-title="Limite di utilizzo ad utente"></k-input>
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
                    { id: "percentage", name: "Importo percentuale" },
                    { id: "fixed", name: "Importo fisso" },
                    { id: "shipment", name: "Sconta spedizione" },
                ],
            };
        },
    };
</script>
