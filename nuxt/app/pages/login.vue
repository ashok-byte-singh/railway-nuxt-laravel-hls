<script setup>
  definePageMeta({
    layout: false,
    middleware: 'guest'
  })
  
  import { ref } from 'vue'
  const { login } = useAuth()
  
  const email = ref('')
  const password = ref('')
  const error = ref('')
  const loading = ref(false)
  
  const submit = async () => {
    error.value = ''
    loading.value = true
  
    try {
      await login(email.value, password.value)
      await navigateTo('/dashboard', { replace: true })
    } catch (e) {
      error.value =
        e?.data?.message ||
        e?.data?.errors?.email?.[0] ||
        'Invalid email or password'
    } finally {
      loading.value = false
    }
  }
  </script>
  
  <template>
    <div class="login-page">
      <div class="login-card">
        <h1>Virtual Lab Login</h1>
        <p class="subtitle">Sign in to access experiments</p>
  
        <form @submit.prevent="submit">
          <input
            v-model="email"
            type="email"
            placeholder="Email address"
            required
          />
  
          <input
            v-model="password"
            type="password"
            placeholder="Password"
            required
          />
  
          <button type="submit" :disabled="loading">
            {{ loading ? 'Signing in…' : 'Login' }}
          </button>
  
          <p v-if="error" class="error">{{ error }}</p>
        </form>
      </div>
    </div>
  </template>
  
  <style scoped>
  /* Page background */
  .login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1f2937, #111827);
    padding: 16px;
  }
  
  /* Card */
  .login-card {
    width: 100%;
    max-width: 380px;
    background: white;
    padding: 28px;
    border-radius: 14px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    text-align: center;
  }
  
  /* Title */
  h1 {
    font-size: 22px;
    margin-bottom: 4px;
  }
  
  .subtitle {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 24px;
  }
  
  /* Inputs */
  input {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 14px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 14px;
  }
  
  input:focus {
    outline: none;
    border-color: #2563eb;
  }
  
  /* Button */
  button {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: #2563eb;
    color: white;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
  }
  
  button:disabled {
    background: #93c5fd;
    cursor: not-allowed;
  }
  
  /* Error */
  .error {
    margin-top: 12px;
    color: #dc2626;
    font-size: 14px;
  }
  
  /* 📱 Mobile tweaks */
  @media (max-width: 480px) {
    .login-card {
      padding: 22px;
    }
  }
  </style>
  