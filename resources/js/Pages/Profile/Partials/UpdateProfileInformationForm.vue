<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

const user = usePage().props.user;
const loggedRole = usePage().props.loggedRole;

const form = useForm({
    name: user.name,
    email: user.email,
    position: user.position,
    supervisor_id: user.supervisor_id,
    manager_id: user.manager_id,
    born_date: user.born_date,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="position" value="Position" />

                <select
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    id="position" v-model="form.position" required autofocus autocomplete="position">
                    <option value="" disabled>Select Position</option>
                    <option value="Manager">Manager</option>
                    <option value="Supervisor">Supervisor</option>
                    <option value="Employee">Employee</option>
                </select>

                <InputError class="mt-2" :message="form.errors.position" />
            </div>

            <div v-if="loggedRole === 'Admin'">
                <div class="mt-4">
                    <InputLabel for="supervisor" value="Supervisor" />

                    <select
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        id="supervisor" v-model="form.supervisor_id" required autofocus autocomplete="supervisor_id">
                        <option value="" disabled>Select Position</option>
                        <option value="">Test</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.supervisor_id" />
                </div>

                <div class="mt-4">
                    <InputLabel for="manager" value="Manager" />

                    <select id="manager"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.manager_id" required autofocus autocomplete="manager_id">
                        <option value="" disabled>Select Position</option>
                        <option value="">Test</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.manager_id" />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="born_date" value="Born Date" />

                <input id="born_date" type="date"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    v-model="form.born_date" required autofocus autocomplete="born_date" />
                <InputError class="mt-2" :message="form.errors.born_date" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </div>
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
    </section>
</template>
