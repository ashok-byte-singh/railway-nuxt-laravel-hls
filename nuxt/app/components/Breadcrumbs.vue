<script setup>
    import { computed } from 'vue'
    import { useRoute } from 'vue-router'
    
    const route = useRoute()
    
    /**
     * Breadcrumbs are built from route meta
     * Each page can optionally define:
     * meta: { breadcrumb: 'Title' }
     */
    const breadcrumbs = computed(() => {
      const crumbs = []
    
      if (route.path.startsWith('/dashboard')) {
        crumbs.push({ name: 'Dashboard', to: '/dashboard' })
      }
    
      if (route.path.startsWith('/experiments')) {
        crumbs.push({ name: 'Experiments', to: '/experiments' })
      }
    
      if (route.meta.breadcrumb) {
        crumbs.push({ name: route.meta.breadcrumb })
      }
    
      return crumbs
    })
    </script>
    
    <template>
      <nav class="breadcrumbs" v-if="breadcrumbs.length">
        <span v-for="(crumb, index) in breadcrumbs" :key="index">
          <NuxtLink
            v-if="crumb.to && index !== breadcrumbs.length - 1"
            :to="crumb.to"
          >
            {{ crumb.name }}
          </NuxtLink>
    
          <span v-else>{{ crumb.name }}</span>
    
          <span v-if="index < breadcrumbs.length - 1"> → </span>
        </span>
      </nav>
    </template>
    
    <style scoped>
        .breadcrumbs {
  font-size: 13px;
  margin-bottom: 12px;
  color: #6b7280;
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
}

.breadcrumbs span {
  white-space: nowrap;
}
    
    .breadcrumbs a {
      color: #2563eb;
      text-decoration: none;
    }
    
    .breadcrumbs a:hover {
      text-decoration: underline;
    }
    </style>
    