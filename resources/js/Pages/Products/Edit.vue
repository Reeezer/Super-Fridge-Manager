<template>
    <Head title="Modify a product" />

    <breeze-authenticated-layout>
        <template #header>
            <h2 class="h4 font-weight-bold">
                Modify a product
            </h2>
        </template>

        <Link :href="route('products.index')" class="btn btn-primary mb-2">Back</Link>

        <form @submit.prevent="form.post(route('products.user_update', product))">
            <div class="row">
                <div class="col-12 col-lg-6 offset-0 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-12 mt-3">{{product.name}}</div>
                                <div class="form-group col-12 mt-3">
                                    <InputDate
                                            v-model="form.created_at"
                                            :inputId="'inputCreated_at'"
                                            :labelText="'Add date'"
                                            :formError="form.errors.created_at"
                                        />
                                </div>

                                <button type="submit" class="btn btn-primary mt-3" @click="notifyUpdate()" :disabled="form.processing">Modifier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm, Link } from '@inertiajs/inertia-vue3'
import InputLabel from '@/Components/Form/InputLabel.vue'
import InputDate from '@/Components/Form/InputDate.vue'
import CategoryImage from '@/Components/CategoryImage.vue'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        InputLabel,
        InputDate,
        CategoryImage
    },

    props: ['product', 'categories'],

    data() {
        return {
            form: useForm({
                name: this.product.name,
                category_id: this.product.category_id,
                ean_code: this.product.ean_code,
                created_at: this.product.created_at,
            })
        }
    },
    methods: {
        notifyUpdate() {
            window.localStorage.setItem('update', this.product.name); // to notify index of the update
        }
    }
}
</script>
