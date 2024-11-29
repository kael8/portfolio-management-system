<!-- resources/js/components/ExampleComponent.vue -->
<template>
    <div>
        <h1>Hello, this is a Vue component!</h1>
        <p>{{ message }}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: "This message is coming from Vue!",
        };
    },
};
</script>

<style scoped>
h1 {
    color: blue;
}
</style>
