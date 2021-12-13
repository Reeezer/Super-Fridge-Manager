<template>
    <img :class="category" :style="'height: 48px; border-radius: 8px; background-color: ' + getDarkColor(category) + ';'" :src="'/resources/images/' + category.toLowerCase() + '.png'">
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: ['category'],
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
            }
        }
    })
</script>
