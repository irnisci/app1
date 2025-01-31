<template>
  <q-page class="q-pa-md flex flex-center">
    <div class="exercise-container">
      <!-- Header mit Zurück-Button -->
      <q-toolbar class="bg-primary text-white rounded-borders q-mb-md">
        <q-btn flat round dense icon="arrow_back" @click="router.go(-1)" />
        <q-toolbar-title>{{ exercise.title }}</q-toolbar-title>
      </q-toolbar>

      <!-- Übungsbeschreibung -->
      <q-card class="q-mb-md">
        <q-card-section>
          <div class="text-h6">Übungsbeschreibung</div>
          <div class="text-body1 q-mt-sm">{{ exercise.description }}</div>
        </q-card-section>
      </q-card>

      <!-- Kreisförmiger Timer -->
      <div class="q-mb-md text-center">
        <q-circular-progress
          :value="(30 - timer) / 1.2"
          size="150px"
          color="primary"
          show-value
          :thickness="0.2"
        >
          {{ timer }}
        </q-circular-progress>
      </div>

      <!-- Start- und Favoriten-Buttons -->
      <div class="q-gutter-md">
        <q-btn
          v-if="!isCompleted"
          label="Übung starten"
          color="primary"
          @click="startTimer"
          :disable="timerActive"
        />
        <q-btn
          v-else
          label="Erledigt ✅"
          color="green"
          disable
        />

        <q-btn
          :label="isFavorite ? 'Favorit entfernen' : 'Als Favorit markieren'"
          :color="isFavorite ? 'negative' : 'positive'"
          class="full-width"
          @click="toggleFavorite"
        />
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from 'boot/axios';

const router = useRouter();
const route = useRoute();
const exercise = ref({});
const timer = ref(30);
const timerActive = ref(false);
const isCompleted = ref(false);
const isFavorite = ref(false);
let countdown;

// Übung laden & Favoritenstatus für den Nutzer abrufen
const fetchExercise = async () => {
  try {
    const response = await api.get(`/exercise/${route.params.id}`);
    exercise.value = response.data.exercise;
    isFavorite.value = response.data.is_favorite;
    isCompleted.value = response.data.completed;
  } catch (error) {
    console.error('Fehler beim Laden der Übung:', error);
  }
};

// Timer starten
const startTimer = () => {
  timerActive.value = true;
  countdown = setInterval(() => {
    timer.value--;
    if (timer.value === 0) {
      clearInterval(countdown);
      markCompleted();
      redirectToChatbot();
    }
  }, 1000);
};

// Timer zurücksetzen, wenn Nutzer die Seite verlässt
onUnmounted(() => {
  clearInterval(countdown);
  timer.value = 120;
  timerActive.value = false;
});

// Übung als erledigt markieren
const markCompleted = async () => {
  try {
    await api.post(`/exercise/${exercise.value.id}/complete`);
    isCompleted.value = true;
    triggerVibration();
  } catch (error) {
    console.error('Fehler beim Markieren der Übung als erledigt:', error);
  }
};

// Vibrationsfeedback auslösen
const triggerVibration = () => {
  if ("vibrate" in navigator) {
    navigator.vibrate([200, 100, 200]); // 200ms Vibration, 100ms Pause, 200ms Vibration
  }
};

// Favoritenstatus ändern
const toggleFavorite = async () => {
  try {
    const response = await api.post(`/exercise/${exercise.value.id}/favorite`);
    isFavorite.value = response.data.is_favorite;
  } catch (error) {
    console.error('Fehler beim Favoritenstatus:', error);
  }
};
// Wenn Übung abgeschlossen ist, Chatbot starten
const redirectToChatbot = () => {
  router.push(`/exercise/${route.params.id}/chat`);
};
onMounted(fetchExercise);
</script>

<style scoped>
.exercise-container {
  max-width: 400px;
  text-align: center;
}

.q-toolbar {
  border-radius: 10px;
}

.text-h6 {
  font-weight: bold;
}

.q-btn {
  font-size: 1.1em;
}
</style>
