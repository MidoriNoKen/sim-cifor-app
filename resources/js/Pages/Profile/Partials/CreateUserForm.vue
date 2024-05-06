<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

// const user = usePage().props.user;
// const loggedRole = usePage().props.loggedRole;

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    role: null,
    position: null,
    supervisor_id: null,
    manager_id: null,
    born_date: null,
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            User Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Create new account's profile information and email address.
        </p>
    </header>

    <form @submit.prevent="form.post(route('users.store'))" class="mt-6 space-y-6">
        <div>
            <InputLabel for="name" value="Name" />

            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
            <InputLabel for="email" value="Email" />

            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                autocomplete="username" />

            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div>
            <InputLabel for="password" value="New Password" />

            <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                class="mt-1 block w-full" autocomplete="new-password" />

            <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <div>
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" />

            <InputError :message="form.errors.password_confirmation" class="mt-2" />
        </div>

        <div class="mt-4">
            <InputLabel for="role" value="Role" />

            <select
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                id="role" v-model="form.role" required>
                <option value="" disabled>Select Role</option>
                <option value="Manager">Manager</option>
                <option value="Admin">Admin</option>
                <option value="Employee">Employee</option>
            </select>

            <InputError class="mt-2" :message="form.errors.role" />
        </div>

        <div class="mt-4">
            <InputLabel for="position_id" value="Position" />

            <select
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                id="position_id" v-model="form.position" required>
                <option value="" disabled>Select Position</option>
                <option value="Manager">Manager</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Employee">Employee</option>
            </select>

            <InputError class="mt-2" :message="form.errors.position" />
        </div>

        <div class="mt-4">
            <InputLabel for="supervisor_id" value="Supervisor" />

            <select
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                id="supervisor_id" v-model="form.supervisor_id">
                <option value="" disabled>Select Position</option>
                <option value="A" id="test1">Test</option>
                <option value="B" id="test2">Test</option>
            </select>

            <InputError class="mt-2" :message="form.errors.supervisor_id" />
        </div>

        <div class="mt-4">
            <InputLabel for="manager_id" value="Manager" />

            <select id="manager_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.manager_id">
                <option value="" disabled>Select Position</option>
                <option value="A" id="test1">Test</option>
                <option value="B" id="test2">Test</option>
            </select>

            <InputError class="mt-2" :message="form.errors.manager_id" />
        </div>

        <div class="mt-4">
            <InputLabel for="born_date" value="Born Date" />

            <input id="born_date" type="date"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.born_date" required />
            <InputError class="mt-2" :message="form.errors.born_date" />
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </p>
            </Transition>
        </div>
    </form>
</template>
