<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const positions = usePage().props.positions.map((position, index) => ({
    id: index,
    name: position,
}));
const props = defineProps(["form"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="position" value="Position" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="position"
            v-model="form.position"
            required
        >
            <option value="null" disabled>Select Position</option>
            <option
                v-for="position in positions"
                :value="position.name"
                :key="position.id"
            >
                {{ position.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.position" />
    </div>
</template>
