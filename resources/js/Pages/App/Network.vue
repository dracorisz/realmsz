<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
  users: Object,
});

const parseDate = (date) => {
  const d = new Date(date);
  return d.toLocaleString();
};
</script>

<template>
  <AppLayout title="Network">
    <div class="h-full flex items-center justify-center w-full gap-2 text-white/70">
      <span>Module under development</span>
      <span class="animate-pulse">_</span>
    </div>
    <!-- <div class="mx-auto flex w-full flex-col text-white">
      <table v-if="users.length > 0" class="table table-auto border-collapse border-spacing-0 rounded-b-lg bg-white/10 p-2 text-right text-sm">
        <thead>
          <tr class="p-2">
            <th class="p-3">Id</th>
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Role</th>
            <th class="p-3">Created At</th>
            <th class="p-3">Updated At</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="item in users" :key="item.id">
            <tr class="p-2">
              <td class="p-3">{{ item.id }}</td>
              <td class="p-3">{{ item.name }}</td>
              <td class="p-3">{{ item.email }}</td>
              <td class="p-3 capitalize">{{ item.role }}</td>
              <td class="p-3">{{ parseDate(item.created_at) }}</td>
              <td class="p-3">{{ parseDate(item.updated_at) }}</td>
            </tr>
          </template>
        </tbody>
      </table>
      <template v-else>
        <p class="mb-10 text-center">No items found.</p>
      </template>
    </div> -->
  </AppLayout>
</template>

