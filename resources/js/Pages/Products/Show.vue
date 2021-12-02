<template>
    <Head title="Show a product" />

    <breeze-authenticated-layout>
        <template #header>
            <h2 class="h4 font-weight-bold">
                Show a product
            </h2>
        </template>

        <Link :href="route('products.index')" class="btn btn-primary mb-2">Back</Link>

        <div class="row">
            <div class="col-12 col-lg-6 offset-0 offset-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <strong>Name:</strong>
                                {{ product.name }}
                            </div>
                            <div class="form-group col-12 mt-3">
                                <strong>EAN code:</strong>
                                {{ product.ean_code }}
                            </div>
                            <div class="form-group col-12 mt-3">
                                <strong>Add date:</strong>
                                {{ new Date(product.created_at).toISOString().substr(0, 10) }}
                            </div>
                            <div class="form-group col-12 mt-3">
                                <strong>Category:</strong>
                                {{ product.category.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
    },
    props: ['product'],
    methods: {
        getHSL(category) {
            let colors = require('/resources/colors.json');
            let hsl;
            if (colors[category])
                hsl = colors[category];
            else
                hsl = colors['default'];
            return hsl;
        },
        getDarkColor(category) {
            let hsl = this.getHSL(category.toLowerCase());
            return 'hsl(' + hsl[0] + ',' + hsl[1] + '%,' + hsl[2] + '%)';
        },
        getPastelColor(category) {
           let hsl = this.getHSL(category.toLowerCase());
            return 'hsl(' + hsl[0] + ',' + hsl[1] + '%,' + (hsl[2]+20) + '%)';
        }
    }
}
</script>
