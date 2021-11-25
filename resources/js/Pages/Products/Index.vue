<template>
  <Head title="Produits" />

  <breeze-authenticated-layout>
    <template #header>
      <h2 class="h4 font-weight-bold">
        Produits
      </h2>
    </template>
    <!-- Page content here -->

    <h1 class="mb-3">Produits</h1>

    <div v-for="(product, index) in sortedArray" :key="product.id" v-bind:id="'div-bg'+index" class="d-flex align-items-center justify-content-between mb-1 p-2" style="border-radius: 12px; position: relative;">
        <div class="d-flex align-items-center">
            <div style="margin-right: 1rem;">
                <img v-bind:id="'img'+index" style="height: 48px; border-radius: 8px;" :src="'/images/' + product.category.name.toLowerCase() + '.png'">
            </div>
            <Link class="btn p-0" :href="route('products.show', product.id)">
                <div>{{product.name}}</div>
            </Link>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn p-0" style="margin-right: 1rem;" @click="destroy(product.id)">
                <i class="bi bi-x-lg"></i>
            </button>
            <Link class="btn p-0" style="margin-right: 1rem;" :href="route('products.edit', product.id)">
                <i class="bi bi-pencil"></i>
            </Link>
            <div class="expiration-date" style="margin-right: 0.5rem;" :style="[daysLeft(product.created_at, product.category.expiration_days) <= 3 ? {'color':'red'} : {}]">
                {{daysLeft(product.created_at, product.category.expiration_days)}} d
            </div>
        </div>
        <div v-if="daysLeft(product.created_at, product.category.expiration_days) <= 3" class="bg-danger expiration-icon" style="position: absolute; right:0; width: 10px; height: 48px; border-radius: 8px 0px 0px 8px;"></div>
    </div>

    <Pagination class="mt-3" :links="products.links" />
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import Pagination from '@/Components/Pagination.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        Head,
        Link,
    },
    props: [
        "products"
    ],
    methods: {
        destroy(id) {
            if (confirm('Are you sure you want to delete this product ?'))
                Inertia.delete(route('products.destroy', id));
        },
        daysLeft(created_at, expiration_days) { // TODO Duplicate method
            const nbDays = new Date() - new Date(created_at);
            return expiration_days - Math.floor(nbDays / (1000 * 3600 * 24));
        },
    },
    computed:{
        sortedArray: function() {
            function compare(a, b) {
                if (a < b)
                    return -1;
                if (a > b)
                    return 1;
                return 0;
            }

            function days(created_at, expiration_days) { // TODO Duplicate method
                const nbDays = new Date() - new Date(created_at);
                return expiration_days - Math.floor(nbDays / (1000 * 3600 * 24));
            }

            return this.products.data.sort((a, b) => compare(days(a.created_at, a.category.expiration_days), days(b.created_at, b.category.expiration_days)));
        }
    },
    mounted() {
        function rgbToHsl(r, g, b) { // https://gist.github.com/mjackson/5311256
            r /= 255, g /= 255, b /= 255;
            var max = Math.max(r, g, b), min = Math.min(r, g, b);
            var h, s, l = (max + min) / 2;

            if(max == min)
            {
                h = s = 0; // achromatic
            }
            else
            {
                var d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch(max)
                {
                    case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                    case g: h = (b - r) / d + 2; break;
                    case b: h = (r - g) / d + 4; break;
                }
                h /= 6;
            }
            return [h*360, s*100, l*100];
        }

        var i = 0;
        while (document.getElementById('img'+i))
        {
            const colorThief = new ColorThief();
            let img = document.getElementById('img'+i);
            let div = document.getElementById('div-bg'+i);

            img.addEventListener('load', function() {
                let color = colorThief.getColor(img);
                let hsl = rgbToHsl(color[0], color[1], color[2]);
                console.log(hsl)
                img.style.backgroundColor = 'hsl(' + hsl[0] + ',' + (hsl[1]-20) + '%, 65%)';
                div.style.backgroundColor = 'hsl(' + hsl[0] + ',' + (hsl[1]-20) + '%, 85%)';
            });
            i++;
        }
    }
}

</script>
