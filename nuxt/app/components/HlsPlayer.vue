<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Hls from 'hls.js'

const props = defineProps({
  src: { type: String, required: true }
})

const videoRef = ref(null)
let hls = null

const initPlayer = () => {
  if (!videoRef.value || !props.src) return

  // Safari / iOS
  if (videoRef.value.canPlayType('application/vnd.apple.mpegurl')) {
    videoRef.value.src = props.src
    videoRef.value.crossOrigin = 'use-credentials'
    videoRef.value.load()
    videoRef.value.play().catch(() => {})
    return
  }

  // Chrome / Firefox / Edge
  if (Hls.isSupported()) {
    hls = new Hls({
      enableWorker: true,
      lowLatencyMode: false,
      xhrSetup(xhr) {
        xhr.withCredentials = true
      }
    })

    hls.loadSource(props.src)
    hls.attachMedia(videoRef.value)
  }
}

onMounted(initPlayer)

watch(() => props.src, () => {
  if (hls) {
    hls.destroy()
    hls = null
  }
  initPlayer()
})

onBeforeUnmount(() => {
  if (hls) hls.destroy()
})
</script>

<template>
  <video
    ref="videoRef"
    controls
    playsinline
    preload="metadata"
    crossorigin="use-credentials"
    controlsList="nodownload noplaybackrate"
    disablePictureInPicture
    style="width:100%; max-height:70vh; background:black;"
  />
</template>
