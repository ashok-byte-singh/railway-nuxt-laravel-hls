<!-- <script setup>
    import { ref, computed } from 'vue'

const search = ref('')
    definePageMeta({ middleware: 'auth' })
    
    const { getToken } = useAuth()  
  

    const { data: experiments, pending, error } = await useAsyncData(
      'experiments',
      () =>
        $fetch('/api/experiments', {
          headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${getToken()}`
        }}),
        
    )
    const filteredExperiments = computed(() => {
  if (!experiments.value) return []
  if (!search.value) return experiments.value

  return experiments.value.filter(exp =>
    exp.title.toLowerCase().includes(search.value.toLowerCase())
  )
})
    </script>
  -->
  <script setup>
    import { ref, computed } from 'vue'
    
    definePageMeta({ middleware: 'auth' })
    
    const search = ref('')
    
    const { data: experiments, pending, error } = await useAsyncData(
      'experiments',
      () =>
        $fetch('/api/experiments', {
          credentials: 'include', // 🔥 REQUIRED
          headers: {
            Accept: 'application/json'
          }
        }),
      { server: false } // 🔥 important for auth pages
    )
    
    const filteredExperiments = computed(() => {
      if (!experiments.value) return []
      if (!search.value) return experiments.value
    
      return experiments.value.filter(exp =>
        exp.title.toLowerCase().includes(search.value.toLowerCase())
      )
    })
    </script>
    
    <template>
        <div class="experiments">
          <h1>Experiments</h1>
          <input
  v-model="search"
  placeholder="Search experiments…"
  class="search"
/>
          <div v-if="pending">Loading experiments…</div>
            
       

          <div v-else class="grid">
            <NuxtLink
    v-for="exp in filteredExperiments"
    :key="exp.id"
    :to="`/experiments/${exp.id}`"
    class="experiment-card"
  >
    <h3>{{ exp.title }}</h3>
    <p>{{ exp.aim }}</p>
  </NuxtLink>
          </div>
        </div>
      </template>
      
      <style scoped>
      .experiments { padding: 24px; }
      .grid {
  margin-top: 24px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
}
      .experiment-card {
        padding: 20px;
        border-radius: 12px;
        background: white;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        color: inherit;
        transition: 0.2s;
      }
      .experiment-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      }
      .search {
  width: 100%;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  margin-bottom: 16px;
}
      /* 📱 Mobile */
@media (max-width: 640px) {
  .grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
}

      </style>