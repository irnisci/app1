<template>
  <q-page padding class="lesson-detail-background">
    <div class="back-button">
      <q-btn flat dense icon="arrow_back" @click="goBack" />
    </div>

    <!-- Header mit Bild -->
    <div class="lesson-header">
      <img :src="getLessonImage(lesson?.title)" class="lesson-image" alt="Lektions Illustration" />
    </div>

    <!-- Lektionsdetails -->
    <q-card class="lesson-card">
      <q-card-section>
        <div class="text-h5">{{ lesson?.title }}</div>
        <div class="text-subtitle2">{{ lesson?.content }}</div>
      </q-card-section>

      <!-- Timer -->
      <q-card-section v-if="!completed">
        <div class="text-bold">Lektion läuft...</div>
        <q-linear-progress :value="progress" color="primary" class="q-mt-md" />
      </q-card-section>

      <!-- Buttons für Abschluss oder Wiederholen -->
      <q-card-section class="button-section">
        <q-btn v-if="!completed" label="Lektion abschließen" color="primary" @click="completeLesson" :disable="progress < 1" />
        <q-btn v-else label="Lektion wiederholen" color="orange" @click="restartLesson" />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from 'boot/axios';

const route = useRoute();
const router = useRouter();
const lesson = ref(null);
const progress = ref(0);
const completed = ref(false);

const fetchLesson = async () => {
  try {
    const response = await api.get(`/lessons/${route.params.id}/status`);
    lesson.value = response.data.lesson;
    completed.value = response.data.completed;
  } catch (error) {
    console.error('Fehler beim Abrufen der Lektion:', error);
  }
};

// Simulierter Timer (2 Minuten)
const startTimer = () => {
  const interval = setInterval(() => {
    if (progress.value < 1) {
      progress.value += 0.01;
    } else {
      clearInterval(interval);
    }
  }, 100);
};

// Lektion als abgeschlossen markieren
const completeLesson = async () => {
  try {
    await api.post(`/lessons/${lesson.value.id}/complete`);
    completed.value = true;
    $q.notify({ type: 'positive', message: 'Lektion abgeschlossen!' });
  } catch (error) {
    console.error('Fehler beim Abschließen der Lektion:', error);
    $q.notify({ type: 'negative', message: 'Fehler beim Abschließen!' });
  }
};

// Lektion erneut starten
const restartLesson = () => {
  completed.value = false;
  progress.value = 0;
  startTimer();
};

// Lektionsbild abrufen
const getLessonImage = (title) => {
  const images = {
    "Was ist Stress?": "/images/lessons/stress_intro.png",
  };
  return images[title] || "default.jpg";
};
const goBack = () => {
  router.go(-1);
}

onMounted(() => {
  fetchLesson();
  startTimer();
});
</script>

<style scoped>
.lesson-detail-background {
  background-color: var(--primary);
  min-height: 100vh;
  padding: 20px;
}

/* Zurück-Button */
.back-button {
  position: absolute;
  top: 15px;
  left: 15px;
}

/* Header mit Bild */
.lesson-header {
  width: 100%;
  height: 180px;
  overflow: hidden;
}

.lesson-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

/* Lektions-Karte */
.lesson-card {
  margin-top: -30px;
  background: white;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

/* Button */
.button-section {
  text-align: center;
  margin-top: 20px;
}
</style>
