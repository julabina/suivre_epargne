<template>
    <div class="modal-container">
        <div class="modal">
            <input type="button" value="X" @click="toggle">
            111111111111
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        toggle: Function
    });
</script>