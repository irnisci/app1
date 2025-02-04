<template>
  <q-page padding class="module-detail-background">
    <div class="back-button">
      <q-btn flat dense icon="arrow_back" @click="goBack" />
    </div>

    <!-- Header mit Bild & Favoriten -->
    <div class="module-header">
      <img :src="getModuleImage(module?.title)" class="module-image" alt="Modul Illustration" />
      <q-btn dense icon="star_border" class="favorite-button" @click="toggleFavorite" />
    </div>

    <!-- Modulbeschreibung -->
    <q-card class="module-card">
      <q-card-section>
        <div class="text-h5">{{ module?.title }}</div>
        <div class="text-subtitle2">{{ module?.description }}</div>
      </q-card-section>

      <!-- Liste der Lektionen mit Fortschrittsanzeige -->
      <q-card-section>
        <div class="text-bold">Lektion auswählen</div>
        <q-list separator>
          <q-item
            v-for="(lesson, index) in lessons"
            :key="lesson.id"
            clickable
            @click="goToLesson(lesson.id)"
            class="lesson-item"
          >
            <q-item-section avatar>
              <div class="lesson-number">{{ index + 1 }}</div>
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ lesson.title }}</q-item-label>
            </q-item-section>
            <q-item-section side>
              <q-icon v-if="completedLessons.includes(lesson.id)" name="check_circle" color="green" />
              <q-icon v-else name="lock" color="grey" />
            </q-item-section>
          </q-item>
        </q-list>
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
const module = ref(null);
const lessons = ref([]);
const completedLessons = ref([]);

const fetchModuleProgress = async () => {
  try {
    const response = await api.get(`/modules/${route.params.id}/progress`);
    lessons.value = response.data.lessons;
    completedLessons.value = response.data.completed;
  } catch (error) {
    console.error('Fehler beim Abrufen des Modul-Fortschritts:', error);
  }
};

const goBack = () => {
  router.push('/courses');
};

const goToLesson = (lessonId) => {
  router.push(`/lessons/${lessonId}`);
};

const getModuleImage = (title) => {
  if (!title) return "/default.jpg"; // Falls title undefined ist, Standardbild verwenden

  const images = {
    "Einführung in Stress": "/stress.avif",
  };

  // "Modul X: ..." entfernen, falls vorhanden
  const cleanTitle = title.replace(/Modul \d+: /, "").trim();

  return images[cleanTitle] || "/default.jpg";
};

onMounted(() => {
  fetchModuleProgress();
});
</script>

<style scoped>
.module-detail-background {
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
.module-header {
  position: relative;
  width: 100%;
  height: 180px;
  overflow: hidden;
}

.module-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

/* Favoriten-Button */
.favorite-button {
  position: absolute;
  top: 15px;
  right: 15px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
}

/* Modul-Karte */
.module-card {
  margin-top: -30px;
  background: white;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

/* Lektionen-Liste */
.lesson-item {
  border-radius: 10px;
  transition: background 0.3s ease-in-out;
}

.lesson-item:hover {
  background: rgba(255, 255, 255, 0.1);
}

/* Nummerierte Kreise für Lektionen */
.lesson-number {
  width: 30px;
  height: 30px;
  background: #ff6b6b;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  border-radius: 50%;
}
</style>
