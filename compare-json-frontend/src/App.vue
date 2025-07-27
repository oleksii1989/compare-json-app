<template>
  <div class="section">
    <div class="container is-max-desktop">
      <div class="content has-text-centered">
        <h1 class="title is-2">Compare Product JSON</h1>
        <button class="button is-medium" :disabled="sending || sentFirst" @click="sendFirstPayload">
          Send Payload 1
        </button>
      </div>
      <div v-if="sentFirst" class="notification is-success is-light">Payload 1 sent!</div>
      <div v-if="timerActive" class="notification is-info is-light">
        Sending Payload 2 in {{ timer }} seconds
      </div>
      <div v-if="sentSecond" class="notification is-success is-light">Payload 2 sent!</div>
      <div v-if="loading" class="notification">Comparing payloads...</div>
      <div v-if="error" class="notification is-danger is-light">{{ error }}</div>
      <div v-if="diff" class="content">
        <h2 class="title is-3 has-text-centered">Differences</h2>
        <DiffViewer :diff="diff" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { payload1, payload2 } from './data/payloads'
import { ref } from 'vue'
import axios from 'axios'
import DiffViewer from './components/DiffViewer.vue'

const sending = ref(false)
const sentFirst = ref(false)
const sentSecond = ref(false)
const loading = ref(false)
const error = ref('')
const diff = ref(null)
const timer = ref(10)
const timerActive = ref(false)
let timerInterval = null

const sendFirstPayload = async () => {
  sending.value = true
  error.value = ''

  try {
    await axios.post('http://localhost:8000/api/payload1', payload1)
    sentFirst.value = true
    startTimer()
  } catch (e) {
    error.value = e.response?.data?.error || 'Failed to send payload 1'
  } finally {
    sending.value = false
  }
}

const startTimer = () => {
  timerActive.value = true
  timer.value = 10
  timerInterval = setInterval(() => {
    timer.value--

    if (timer.value <= 0) {
      clearInterval(timerInterval)
      sendSecondPayload()
      timerActive.value = false
    }
  }, 1000)
}

const sendSecondPayload = async () => {
  sending.value = true
  error.value = ''

  try {
    await axios.post('http://localhost:8000/api/payload2', payload2)
    sentSecond.value = true
    getDiff()
  } catch (e) {
    error.value = e.response?.data?.error || 'Failed to send payload 2'
  } finally {
    sending.value = false
  }
}

const getDiff = async () => {
  loading.value = true
  error.value = ''

  try {
    const res = await axios.get('http://localhost:8000/api/diff')
    diff.value = res.data.diff
  } catch (e) {
    error.value = e.response?.data?.error || 'Failed to get diff'
  } finally {
    loading.value = false
  }
}
</script>
