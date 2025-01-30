<template>
  <q-page class="visual-background">
    <q-btn flat icon="arrow_back_ios" no-caps @click="router.go(-1)" />
    <div class="text-h4 text-center text-white q-mb-md">Fokus & Farbtherapie</div>

    <div class="row justify-center q-gutter-md">
      <!-- Fokus-Übung -->
      <div class="col-12 col-md-6 text-center">
        <q-card class="custom-card">
          <q-card-section class="card-content">
            <q-icon name="visibility" size="lg" class="q-mb-sm" />
            <div class="text-h6">Fokus-Übung</div>
            <div class="text-subtitle2">Folge dem Punkt mit deinen Augen</div>
            <q-btn label="Start" color="primary" @click="startFocusExercise" />
            <q-btn v-if="isFocusRunning" label="Stop" color="negative" @click="stopFocusExercise" class="q-ml-sm" />
          </q-card-section>
        </q-card>
        <div ref="focusPoint" class="focus-circle"></div>
      </div>

      <!-- Lichttherapie -->
      <div class="col-12 col-md-6 text-center">
        <q-card class="custom-card">
          <q-card-section class="card-content">
            <q-icon name="brightness_6" size="lg" class="q-mb-sm" />
            <div class="text-h6">Lichttherapie</div>
            <div class="text-subtitle2">Sanfte Farbwechsel zur Entspannung</div>
            <q-btn label="Start" color="secondary" @click="startColorTherapy" />
            <q-btn v-if="isColorRunning" label="Stop" color="negative" @click="stopColorTherapy" class="q-ml-sm" />
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Regler für Geschwindigkeit -->
    <div class="text-center q-mt-lg">
      <div class="text-white text-h6">Geschwindigkeit</div>
      <q-slider v-model="speed" :min="1" :max="10" :step="1" label color="primary" class="slider-style" />
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { gsap } from 'gsap';
import { useRouter } from "vue-router"

const router = useRouter();
const focusPoint = ref(null);
const isFocusRunning = ref(false);
const isColorRunning = ref(false);
const speed = ref(5);

// 🔵 Fokus-Übung: Punkt bewegt sich horizontal von links nach rechts
const startFocusExercise = () => {
  isFocusRunning.value = true;
  gsap.to(focusPoint.value, {
    x: window.innerWidth - 80, // Maximale Breite minus Abstand
    duration: 5 / speed.value, // Geschwindigkeit anpassbar
    repeat: -1, // Endlos wiederholen
    yoyo: true, // Rückwärtsbewegung
    ease: "power1.inOut"
  });
};

const stopFocusExercise = () => {
  isFocusRunning.value = false;
  gsap.killTweensOf(focusPoint.value);
};

// 🌈 Lichttherapie: Weicher Farbwechsel
const startColorTherapy = () => {
  isColorRunning.value = true;
  gsap.to('.visual-background', {
    background: "linear-gradient(135deg, #ffcc80, #42a5f5, #66bb6a, #ab47bc)",
    duration: 10 / speed.value,
    repeat: -1,
    yoyo: true,
    ease: "sine.inOut"
  });
};

const stopColorTherapy = () => {
  isColorRunning.value = false;
  gsap.killTweensOf('.visual-background');
};

// Beim Mounten Größe checken
onMounted(() => {
  window.addEventListener("resize", stopFocusExercise);
});

// Beim Verlassen Animation stoppen
onUnmounted(() => {
  stopFocusExercise();
  stopColorTherapy();
});
</script>

<style scoped>
.visual-background {
  background: rgb(238, 174, 202);
  background: linear-gradient(90deg, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transition: background-color 1s;
}

.custom-card {
  border-radius: 15px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  transition: transform 0.3s, box-shadow 0.3s;
}

.custom-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.card-content {
  text-align: center;
  color: white;
}

.focus-circle {
  width: 50px;
  height: 50px;
  background-color: white;
  border-radius: 50%;
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
}

.slider-style {
  max-width: 300px;
  margin: 20px auto;
}
</style>
