<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { usePage, useForm } from "@inertiajs/vue3";

const loggedRole = usePage().props.loggedRole;

const form = useForm({
    start_date: null,
    end_date: null,
    leave_type: null,
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Leave Application Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">Create new leave application.</p>
    </header>

    <form
        @submit.prevent="form.post(route('leaveApplications.store'))"
        class="mt-6 space-y-6"
    >
        <div class="mt-4">
            <InputLabel for="leave_type" value="Leave Type" />

            <select
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                id="leave_type"
                v-model="form.leave_type"
                required
            >
                <option value="" disabled>Pilih Alasan Izin</option>
                <option value="cuti">Cuti</option>
                <option value="sakit">Sakit</option>
                <option value="melahirkan">Melahirkan</option>
            </select>

            <InputError class="mt-2" :message="form.errors.leave_type" />
        </div>
        <div class="mt-4">
            <InputLabel for="start_date" value="Start Date" />

            <input
                id="start_date"
                type="date"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.start_date"
                required
            />
            <InputError class="mt-2" :message="form.errors.start_date" />
        </div>
        <div class="mt-4">
            <InputLabel for="end_date" value="End Date" />

            <input
                id="end_date"
                type="date"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.end_date"
                required
            />
            <InputError class="mt-2" :message="form.errors.end_date" />
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </p>
            </Transition>
        </div>
    </form>
</template>
