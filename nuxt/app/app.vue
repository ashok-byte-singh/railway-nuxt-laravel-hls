<script setup>
  // const { fetchUser } = useAuth()
  const { login } = useAuth()
  
  // onMounted(() => {
  //   fetchUser()
  // })
  </script>
  
  <template>
    <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
  </template>
  
<style>
  .page-enter-active,
  .page-leave-active {
    transition: all 0.4s;
  }
  .page-enter-from,
  .page-leave-to {
    opacity: 0;
    filter: blur(1rem);
  }
  .rotate-enter-active,
.rotate-leave-active {
  transition: all 0.4s ease;
}
  .rotate-enter-from,
.rotate-leave-to {
  opacity: 0;
  transform: rotate3d(1, 1, 1, 15deg);
}
  </style>