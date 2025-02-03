<template>
  <div class="breathing-container">
    <div class="wave"></div>
    <div class="loading-text">Tief einatmen… Bitte warten... Dein antwort ist sofort da </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
.breathing-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 150px;
}

/* 🌊 Atmende Welle */
.wave {
  width: 100px;
  height: 100px;
  background: radial-gradient(circle, rgba(41, 128, 185, 0.5) 0%, rgba(41, 128, 185, 0) 70%);
  border-radius: 50%;
  animation: breathing 3s infinite ease-in-out;
}

@keyframes breathing {
  0% { transform: scale(1); opacity: 0.7; }
  50% { transform: scale(1.5); opacity: 1; }
  100% { transform: scale(1); opacity: 0.7; }
}

/* Text */
.loading-text {
  margin-top: 10px;
  font-size: 1.1rem;
  font-weight: bold;
  color: #2980b9;
}
</style>
