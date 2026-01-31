<script setup>
  definePageMeta({
    middleware: ['auth', 'admin']
  })
  
  const { user, logout } = useAuth()
  </script>
  
  <template>
    <div>
      <h1>Admin Panel</h1>
  
      <div v-if="user">
        <NuxtLink to="/dashboard">Dashboard</NuxtLink>
  
        <p><strong>Name:</strong> {{ user.name }}</p>
        <p><strong>Email:</strong> {{ user.email }}</p>
        <p><strong>Role:</strong> {{ user.role }}</p>
  
        <!-- ✅ Logout only when user exists -->
        <button type="button" @click="logout">Logout</button>
      </div>
  
      <div v-else>
        Loading...
      </div>
    </div>
  </template>
  