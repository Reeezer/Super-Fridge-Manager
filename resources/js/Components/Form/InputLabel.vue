<template>
    <label :for="inputId" class="mb-1">{{ labelText }}</label>
    <input
        :value="modelValue"
        v-on:input="$emit('update:modelValue', $event.target.value); onChange();"
        v-on:blur="onLoseFocus"
        v-on:click="onGainFocus"
        type="text"
        class="form-control"
        :class="formError ? 'is-invalid' : ''"
        :id="inputId"
    >
    <BreezeInputError :message="formError" />
</template>

<script>
import BreezeInputError from '@/Components/InputError.vue'

export default {
    components: {
        BreezeInputError,
    },

    props: ['modelValue', 'inputId', 'labelText', 'formError'],

    emits: ['update:modelValue', 'onChange', 'onLoseFocus', 'onGainFocus'],

    methods: {
        onChange(){
            this.$emit("onChange");
        },
        onLoseFocus(){
            this.$emit("onLoseFocus");
        },
        onGainFocus(){
            this.$emit("onGainFocus");
        }
    }
}
</script>

