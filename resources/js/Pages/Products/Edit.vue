<template>
    <Head title="Modify a product" />

    <breeze-authenticated-layout>
        <template #header>
            <h2 class="h4 font-weight-bold">Modify a product</h2>
        </template>

        <Link :href="route('products.index')" class="btn btn-primary mb-2">
        Back
        </Link>

        <form @submit.prevent="submit">
            <div class="row">
                <div class="col-12 col-lg-6 offset-0 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-12 mt-3">
                                    <h2>
                                        {{ product.name }}
                                    </h2>
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <InputDate
                                        v-model="form.added_date"
                                        :inputId="'inputAddedDate'"
                                        :labelText="'Add date'"
                                        :formError="form.errors.added_date"
                                    />
                                </div>

                                <button type="submit" class="btn btn-primary mt-3" :disabled="form.processing">
                                    Modifier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, Link } from "@inertiajs/inertia-vue3";
import InputLabel from "@/Components/Form/InputLabel.vue";
import InputDate from "@/Components/Form/InputDate.vue";
import CategoryImage from "@/Components/CategoryImage.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        InputLabel,
        InputDate,
        CategoryImage,
    },

    props: ["product", "categories"],

    data() {
        return {
            form: useForm({
                id: this.product.id,
                name: this.product.name,
                added_date: this.product.pivot.added_date,
            }),
        };
    },

    methods: {
        submit() {
            this.$inertia.post(route('products.user_update'), this.form);
        },
    },
};
</script>
