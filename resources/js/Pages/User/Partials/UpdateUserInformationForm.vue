<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import OptionManagerList from "@/Components/Options/OptionManagerList.vue";
import OptionPositionList from "@/Components/Options/OptionPositionList.vue";
import OptionRoleList from "@/Components/Options/OptionRoleList.vue";
import OptionSupervisorList from "@/Components/Options/OptionSupervisorList.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const user = usePage().props.user;
const loggedRole = usePage().props.loggedRole;

const form = useForm({
    name: user.name,
    email: user.email,
    role: user.role,
    position: user.position,
    supervisor: user.supervisor,
    manager: user.manager,
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

        <form @submit.prevent="form.patch(route('users.update', user))" class="mt-6 space-y-6">
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

            <OptionRoleList :form="form" v-model="form.role" />
            <OptionPositionList :form="form" v-model="form.position" />
            <OptionSupervisorList :form="form" v-model="form.supervisor" />
            <OptionManagerList :form="form" v-model="form.manager" />

            <div class="mt-4">
                <InputLabel for="born_date" value="Born Date" />

                <input id="born_date" type="date"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    v-model="form.born_date" required autofocus autocomplete="born_date" />
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
    </section>
</template>
