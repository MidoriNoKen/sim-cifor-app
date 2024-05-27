<script>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

export default {
    props: {
        form: Object,
    },
    methods: {
        addDetail() {
            this.form.accommodation_details.push({
                name: "",
                quantity: 0,
                price: 0,
                description: "",
            });
        },
        removeDetail(index) {
            this.form.accommodation_details.splice(index, 1);
        },
    },
    components: {
        TextInput,
        InputLabel,
        InputError,
    },
};
</script>
<template>
    <div>
        <div
            v-for="(detail, index) in form.accommodation_details"
            :key="index"
            class="mt-4"
        >
            <InputLabel
                :for="'accommodation_name_' + index"
                value="Accommodation Name"
            />
            <TextInput
                :id="'accommodation_name_' + index"
                v-model="detail.name"
                type="text"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            />
            <InputError
                class="mt-2"
                :message="form.errors.accommodation_name_"
            />

            <InputLabel
                :for="'accommodation_quantity_' + index"
                value="Quantity"
                class="mt-2"
            />
            <input
                :id="'accommodation_quantity_' + index"
                v-model="detail.quantity"
                type="number"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            />
            <InputError
                class="mt-2"
                :message="form.errors.accommodation_quantity_"
            />

            <InputLabel
                :for="'accommodation_price_' + index"
                value="Price"
                class="mt-2"
            />
            <input
                :id="'accommodation_price_' + index"
                v-model="detail.price"
                type="number"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            />
            <InputError
                class="mt-2"
                :message="form.errors.accommodation_price_"
            />

            <InputLabel
                :for="'accommodation_description_' + index"
                value="Description"
                class="mt-2"
            />
            <textarea
                :id="'accommodation_description_' + index"
                v-model="detail.description"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            ></textarea>
            <InputError
                class="mt-2"
                :message="form.errors.accommodation_description_"
            />

            <button
                @click="removeDetail(index)"
                type="button"
                class="mt-2 text-red-500"
            >
                Remove
            </button>
        </div>

        <button @click="addDetail" type="button" class="mt-4 text-blue-500">
            Add Accommodation Detail
        </button>

        <InputError :message="form.errors.accommodation_details" class="mt-2" />
    </div>
</template>
