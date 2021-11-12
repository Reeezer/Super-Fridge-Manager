<template>
    <Head title="Modifier un produit" />

    <breeze-authenticated-layout>
        <template #header>
            <h2 class="h4 font-weight-bold">
                Modifier un produit
            </h2>
        </template>

        <Link :href="route('products.index')" class="btn btn-primary mb-2">Retour</Link>

        <form @submit.prevent="form.put(route('products.update', product))">
            <div class="row">
                <div class="col-12 col-lg-6 offset-0 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <InputLabel
                                        v-model="form.name"
                                        :inputId="'inputName'"
                                        :labelText="'Nom'"
                                        :formError="form.errors.name"
                                    />
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <label for="exampleFormControlSelect1">Catégorie</label> <!-- TODO changer nom -->
                                    <select v-model="form.category_id" class="form-control" id="exampleFormControlSelect1">
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{category.name}}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.category_id">{{form.errors.category_id}}</div>
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <InputLabel
                                            v-model="form.ean_code"
                                            :inputId="'inputEan_code'"
                                            :labelText="'Code EAN'"
                                            :formError="form.errors.ean_code"
                                        />
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <InputDate
                                            v-model="form.created_at"
                                            :inputId="'inputCreated_at'"
                                            :labelText="'Date d\'ajout'"
                                            :formError="form.errors.created_at"
                                        />
                                </div>

                                <button type="submit" class="btn btn-primary mt-3" :disabled="form.processing">Modifier</button>
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

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        InputLabel,
        InputDate
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
    }
}
</script>
