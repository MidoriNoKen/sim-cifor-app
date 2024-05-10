<script setup>
import { ref, defineProps } from 'vue';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(['form']);
const positions = ref(["Junior", "Senior", "Manager", "Director"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="position" value="Position" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="position" v-model="form.position" required>
            <option value=null disabled>Select Position</option>
            <option v-for="(position, index) in positions" :key="index" :value="position">{{ position }}</option>
        </select>
        <InputError class="mt-2" :message="form.errors.position" />
    </div>
</template>
