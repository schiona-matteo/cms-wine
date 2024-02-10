<template>
    <div>
        <div class="flex flex-col space-y-4">
            <k-input class="col-span-4 lg:col-span-2" v-model="element.name" :validation="validations" field-name="name" field-title="Nome"></k-input>
            <k-input class="col-span-4 lg:col-span-2" v-model="element.email" :validation="validations" field-type="email" field-name="email" field-title="Email"></k-input>
            <k-input class="col-span-4 lg:col-span-2" v-model="element.password" :validation="validations" field-type="password" field-name="password" field-title="Password"></k-input>
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
    };
</script>
