<script setup>
import { ref, defineProps } from 'vue';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(['form']);
const roles = ref(["Manager", "Admin", "Staff"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="role" value="Role" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="role" v-model="form.role" required>
            <option value=null disabled>Select Role</option>
            <option v-for="(role, index) in roles" :key="index" :value="role">{{ role }}</option>
        </select>
        <InputError class="mt-2" :message="form.errors.role" />
    </div>
</template>
