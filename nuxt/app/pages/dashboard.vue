<script setup>
  definePageMeta({
    middleware: 'auth'
  })
  
  const { user,logout,authLoaded } = useAuth()
  </script>
  
  <template>
    <div v-if="!authLoaded" class="page-center">Loading…</div>
  
    <div v-else class="dashboard">
      <header class="dashboard-header">
        <h1>Welcome, {{ user.name }}</h1>
        <!-- <button @click="logout">Logout</button> -->
      </header>
  
      <section class="dashboard-cards">
        <NuxtLink to="/experiments" class="card">
          <h3>Experiments</h3>
          <p>View all available experiments</p>
        </NuxtLink>
      </section>
    </div>
  </template>
  
  <style scoped>
  .dashboard { padding: 24px; }
  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .dashboard-cards {
  margin-top: 24px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
}
  .card {
    padding: 20px;
    border-radius: 10px;
    background: #f5f7fa;
    text-decoration: none;
    color: inherit;
    transition: 0.2s;
  }
  .card:hover { background: #e9edf3; }
  @media (max-width: 640px) {
  .dashboard-cards {
    grid-template-columns: 1fr;
  }
}
  </style>