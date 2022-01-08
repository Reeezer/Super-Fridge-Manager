<template>
    <label :for="inputId" class=mb-1>{{ labelText }}</label>
    <input
        :value="getDate"
        @input="$emit('update:modelValue', $event.target.value)"
        type="date"
        class="form-control"
        :class="formError ? 'is-invalid' : '' + ' bg-light'"
        :id="inputId"
    required>
    <BreezeInputError :message="formError" />
</template>

<script>
import BreezeInputError from '@/Components/InputError.vue'

export default {
    components: {
        BreezeInputError,
    },

    props: ['modelValue', 'inputId', 'labelText', 'formError'],

    emits: ['update:modelValue'],

    computed:{
        getDate: function() {
            return new Date(this.modelValue).toISOString().substr(0, 10);
        }
    }
}
</script>

