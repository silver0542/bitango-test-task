<template>
    <v-snackbar
        v-model="snackbar"
        :color="color"
        :timeout="timeout"
        top
        right
        @input="onSnackbarClose"
    >
        {{ message }}
        <v-btn text @click="closeSnackbar">Close</v-btn>
    </v-snackbar>
</template>

<script>
export default {
    name: "SuccessMessage",
    data() {
        return {
            snackbar: false,

            timeout: 6000,
        };
    },
    props: {
        color: {
            type: String,
            default: "success",
        },
        message: {
            type: String,
            default: "Registration successful! Welcome!",
        },
    },
    methods: {
        closeSnackbar() {
            this.snackbar = false;
            this.$emit("closed");
        },
        onSnackbarClose() {
            this.$emit("closed");
        },
    },
    mounted() {
        this.snackbar = true;
    },
    unmounted() {
        this.snackbar = false;
    },
};
</script>
