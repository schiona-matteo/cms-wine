<template>
    <Modal @close="$emit('closed')" @confirm="$emit('confirmed')" :sending="sending" classes="sm:max-w-2xl sm:w-full">
        <template v-slot:content> Confermi la rimozione del contenuto? </template>
    </Modal>
</template>
<script>
    export default {
        props: {
            sending: {
                type: Boolean,
                default: false,
            },
        },
    };
</script>
