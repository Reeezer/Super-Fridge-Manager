<template>
    <Head title="Add a product" />

    <breeze-authenticated-layout>
        <template #header>
            <h2 class="h4 font-weight-bold">Add a product</h2>
        </template>

        <Link :href="route('products.index')" class="btn btn-primary mb-2">Back</Link>

        <div class="row">
            <div class="col-12 offset-0 offset-lg-3">
                <div id="camera"></div>
            </div>
        </div>

        <form @submit.prevent="form.post(route('products.store'))">
            <div class="row">
                <div class="col-12 col-lg-6 offset-0 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <InputLabel
                                        v-model="form.name"
                                        :inputId="'inputName'"
                                        :labelText="'Name'"
                                        :formError="form.errors.name"
                                    />
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <label class="mb-1">Category</label>
                                    <select
                                        v-model="form.category_id"
                                        class="form-control"
                                    >
                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.category_id">
                                        {{ form.errors.category_id }}
                                    </div>
                                </div>

                                <div class="form-group col-12 mt-3">
                                    <InputDate
                                        v-model="form.created_at"
                                        :inputId="'inputCreated_at'"
                                        :labelText="'Add date'"
                                        :formError="form.errors.created_at"
                                    />
                                </div>

                                <div class="form-group col-12 mt-3">
                                    <InputLabel
                                        v-model="form.ean_code"
                                        :inputId="'inputEan_code'"
                                        :labelText="'EAN code'"
                                        :formError="form.errors.ean_code"
                                    />
                                </div>

                                <div>
                                    <button
                                        type="button"
                                        name="btnPlaceOrder"
                                        v-on:click="greet"
                                        class="btn btn-primary mt-3"
                                        :disabled="form.processing"
                                    >
                                        Scanner
                                    </button>
                                </div>

                                <div>
                                    <button
                                        type="submit"
                                        class="btn btn-primary mt-3"
                                        :disabled="form.processing"
                                    >
                                        Ajouter
                                    </button>
                                </div>
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
import { Inertia } from '@inertiajs/inertia'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        InputLabel,
        InputDate,
    },

    props: ["categories", "final_name", "ean_code"],

    data() {
        return {
            form: useForm({
                name: this.final_name,
                category_id: null,
                ean_code: this.ean_code,
                created_at: new Date(),
            }),
        };
    },

    methods: {
        greet(event) {
            document.getElementById("camera").style.display = "block";

            Quagga.init(
                {
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        constraints: {
                            width: 640,
                            height: 480,
                            facingMode: "user", // or user
                        },

                        target: document.querySelector("#camera"),
                    },
                    decoder: {
                        readers: ["ean_reader"],
                    },
                    multiple: false
                },
                function (err) {
                    if (err) {
                        console.log(err);
                        return;
                    }
                    console.log("Initialization finished. Ready to start");
                    Quagga.start();
                }
            );

            Quagga.onDetected(function (data) {
                Quagga.stop();

                const options = {
                    method : 'post',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": document.head.querySelector("[name~=csrf-token][content]").content
                    },
                    credentials: "same-origin",
                    body : JSON.stringify({ ean_code : data.codeResult.code }),
                };

                fetch(route('products.fetch_remote'), options)
                    .then(response => response.json())
                    .then(result => {
                        if (result.final_name !== "")
                        {
                            document.getElementById("camera").style.display = "none";

                            let inputEan = document.getElementById("inputEan_code")
                            inputEan.value = data.codeResult.code;
                            inputEan.dispatchEvent(new Event('input'));

                            let inputName = document.getElementById("inputName")
                            inputName.value = result.final_name;
                            inputName.dispatchEvent(new Event('input'));
                        }
                        else
                        {
                            Quagga.start();
                        }
                    });
            });
        },
    },
};
</script>
