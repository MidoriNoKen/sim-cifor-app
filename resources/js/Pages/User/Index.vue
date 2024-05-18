<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";

const users = usePage().props.users;

const showUser = (user) => {
    router.get(`/users/${user.id}`);
};

const createUser = () => {
    router.get(`/users/create`);
};

const editUser = (user) => {
    router.get(`/users/${user.id}/edit`, user);
};

const deleteUser = (user) => {
    if (confirm("Are you sure you want to delete this user?")) {
        router.delete(`/users/${user.id}`);
    }
};
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User List
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="table-responsive">
                        <table class="table align-middle text-center">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Position</th>
                                    <th>Supervisor</th>
                                    <th>Manager</th>
                                    <th>Born Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.role }}</td>
                                    <td>{{ user.position }}</td>
                                    <td>{{ user.supervisor }}</td>
                                    <td>{{ user.manager }}</td>
                                    <td>{{ user.born_date }}</td>
                                    <td>
                                        <button
                                            @click="showUser(user)"
                                            class="btn btn-primary hover-background btn-sm m-1"
                                            style="color: white"
                                        >
                                            Show
                                        </button>
                                        <button
                                            @click="editUser(user)"
                                            class="btn btn-warning hover-background btn-sm m-1"
                                            style="color: white"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteUser(user)"
                                            class="btn btn-danger hover-background btn-sm m-1"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <button
                                @click="createUser()"
                                class="btn btn-primary hover-background btn-sm m-1"
                                style="color: white"
                            >
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
