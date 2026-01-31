<script setup>
    import Breadcrumbs from '~/components/Breadcrumbs.vue'
    const { user, authLoaded, logout } = useAuth()
    </script>
    
    <template>
      <div>
        <!-- NAVBAR -->
        <header v-if="authLoaded && user" class="navbar">
          <div class="left">
            <NuxtLink to="/dashboard" class="logo">
              Virtual Lab
            </NuxtLink>
    
            <nav class="nav-links">
              <NuxtLink to="/dashboard">Dashboard</NuxtLink>
              <NuxtLink to="/experiments">Experiments</NuxtLink>
            </nav>
          </div>
    
          <div class="right">
            <span class="user-name">{{ user.name }}</span>
            <button @click="logout">Logout</button>
          </div>
        </header>
    
        <!-- PAGE CONTENT -->
        <main class="page">
          
            <Breadcrumbs/>
          <slot />
        </main>
        <footer v-if="authLoaded && user" class="mobile-nav">
  <NuxtLink to="/dashboard">🏠</NuxtLink>
  <NuxtLink to="/experiments">🧪</NuxtLink>
  <button @click="logout">🚪</button>
</footer>
      </div>
    </template>
    
    <style scoped>
        .mobile-nav {
  display: none;
}

    .navbar {
  height: auto;
  padding: 12px 16px;
  background: #1f2937;
  color: rgba(255, 255, 255, 0.973);
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}
    
.left {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 16px;
}

.nav-links a {
  margin-right: 16px;
  color: #d1d5db;
  text-decoration: none;
}

.nav-links a.router-link-active {
  color: white;
  font-weight: 600;
}
.right {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 12px;
}
.logo {
  font-weight: bold;
  color: white;
  text-decoration: none;
}
    
    .user-name {
      font-size: 14px;
      color: #fcfdfdfa;
    }
    
    button {
      background: #ef4444;
      border: none;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background: #dc2626;
    }
    
    .page {
      padding: 24px;
    }
    /* 📱 Mobile only */
@media (max-width: 640px) {
  .mobile-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 56px;
    background: #1f2937;
    display: flex;
    justify-content: space-around;
    align-items: center;
    color: white;
  }

  .mobile-nav a,
  .mobile-nav button {
    color: white;
    background: none;
    border: none;
    font-size: 20px;
  }

  .page {
    padding-bottom: 72px; /* avoid overlap */
  }
}
    </style>
    