<template>
  <q-page padding class="breathing-background">
    <q-btn flat icon="arrow_back_ios" no-caps @click="router.go(-1)"/>
    <!-- Header -->
    <div class="text-h4 text-center q-mb-md text-white">Atemübungen</div>

    <!-- Übungsauswahl -->
    <div class="row justify-center q-mb-md">
      <q-select
        v-model="selectedExercise"
        :options="exercises"
        label="Wähle eine Atemübung"
        outlined
        class="col-12 col-md-6"
      />
    </div>

    <!-- Atemübung -->
    <div class="row justify-center q-mt-lg">
      <div class="col-12 col-md-6 text-center">
        <!-- Animation -->
        <div class="circle-container">
          <div class="circle" :style="circleStyle"></div>
        </div>

        <!-- Anweisungen -->
        <div class="text-h5 q-mt-md">{{ instruction }}</div>

        <!-- Timer -->
        <div class="text-h3 q-mt-md">{{ timer }}</div>

        <!-- Buttons -->
        <div class="q-mt-md">
          <q-btn
            v-if="!isRunning"
            label="Start"
            color="primary"
            class="q-mr-sm"
            @click="startBreathing"
          />
          <q-btn
            v-if="isRunning"
            label="Stop"
            color="negative"
            @click="stopBreathing"
          />
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import gsap from 'gsap';
import { useRouter } from 'vue-router';

const router = useRouter();
const $q = useQuasar();
const isRunning = ref(false);
const selectedExercise = ref('4-7-8'); // Standard-Übung
const phase = ref('inhale'); // Phasen: inhale, hold, exhale
const timer = ref(0);
const instruction = ref('Atme tief ein');
const circleSize = ref(100); // Größe des Kreises für die Animation

// Übungsoptionen
const exercises = [
  { label: '4-7-8-Atmung', value: '4-7-8' },
  { label: 'Box-Atmung', value: 'box' },
  { label: 'Tiefe Bauchatmung', value: 'deep' },
];

// Phasen der Atemübungen
const phases = {
  '4-7-8': {
    inhale: { duration: 4, instruction: 'Atme tief ein', size: 200 },
    hold: { duration: 7, instruction: 'Halte den Atem an', size: 200 },
    exhale: { duration: 8, instruction: 'Atme langsam aus', size: 100 },
  },
  box: {
    inhale: { duration: 4, instruction: 'Atme tief ein', size: 200 },
    hold: { duration: 4, instruction: 'Halte den Atem an', size: 200 },
    exhale: { duration: 4, instruction: 'Atme langsam aus', size: 100 },
    holdAfterExhale: { duration: 4, instruction: 'Halte den Atem an', size: 100 },
  },
  deep: {
    inhale: { duration: 5, instruction: 'Atme tief ein', size: 200 },
    exhale: { duration: 5, instruction: 'Atme langsam aus', size: 100 },
  },
};

// Timer-Interval
let interval;

// Starte die Atemübung
const startBreathing = () => {
  isRunning.value = true;
  nextPhase();
};

// Nächste Phase der Atemübung
const nextPhase = () => {
  if (!phases[selectedExercise.value] || !phases[selectedExercise.value][phase.value]) {
    console.error('Ungültige Übung oder Phase');
    return;
  }
  const currentPhase = phases[selectedExercise.value][phase.value];
  timer.value = currentPhase.duration;
  instruction.value = currentPhase.instruction;

  // Animation mit GSAP
  gsap.to('.circle', {
    width: currentPhase.size,
    height: currentPhase.size,
    duration: currentPhase.duration,
    ease: 'power1.inOut',
  });

  interval = setInterval(() => {
    timer.value--;

    if (timer.value <= 0) {
      clearInterval(interval);
      switch (phase.value) {
        case 'inhale':
          phase.value = 'hold';
          break;
        case 'hold':
          phase.value = selectedExercise.value === 'box' ? 'exhale' : 'exhale';
          break;
        case 'exhale':
          phase.value = selectedExercise.value === 'box' ? 'holdAfterExhale' : 'inhale';
          break;
        case 'holdAfterExhale':
          phase.value = 'inhale';
          break;
      }
      nextPhase();
    }
  }, 1000);
};

// Stoppe die Atemübung
const stopBreathing = () => {
  clearInterval(interval);
  isRunning.value = false;
  phase.value = 'inhale';
  timer.value = 0;
  instruction.value = 'Atme tief ein';
  circleSize.value = 100;
  gsap.to('.circle', { width: 100, height: 100, duration: 1 });
};

// Beende die Atemübung beim Verlassen der Seite
onUnmounted(() => {
  stopBreathing();
});

const circleStyle = computed(() => {
  return {
    width: `${circleSize.value}px`,
    height: `${circleSize.value}px`,
    transition: 'all 0.5s ease-in-out'
  };
});
</script>

<style scoped>
.breathing-background {
  /* background: linear-gradient(135deg, #89f7fe, #66a6ff); */
  background: var(--primary);
  min-height: 100vh;
}

.circle-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 300px;
}

.circle {
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.5);
}
</style>
