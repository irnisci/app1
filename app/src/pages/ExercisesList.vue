<template>
  <q-page class="q-pa-md">
    
    <!-- Header mit Zurück-Button -->
    <q-toolbar class="bg-primary text-white rounded-borders q-mb-md">
      <q-btn flat round dense icon="arrow_back" @click="router.go(-1)" />
      <q-toolbar-title>{{ categoryName }}</q-toolbar-title>
    </q-toolbar>

    <div class="text-h5 text-center q-mb-md">Wähle eine Übung</div>
    <div class="text-subtitle1 text-center q-mb-lg">
      Hier findest du alle Übungen aus dieser Kategorie.
    </div>

    <!-- Übungen-Kacheln -->
    <div class="q-gutter-md row justify-center">
      <q-card
        v-for="exercise in exercises"
        :key="exercise.id"
        class="exercise-card col-12 col-md-5"
        @click="startExercise(exercise.id)"
      >
        <q-card-section class="text-center">
          <q-icon name="fitness_center" color="primary" size="lg" />
          <div class="text-h6 q-mt-sm">{{ exercise.title }}</div>
          <div class="text-caption">Dauer: {{ exercise.duration }} Sekunden</div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from 'boot/axios';

const router = useRouter();
const route = useRoute();

const exercises = ref([]);
const categoryName = ref('');

const fetchExercises = async () => {
  try {
    const response = await api.get(`/category/${route.params.id}/exercises`);
    exercises.value = response.data;
    if (response.data.length > 0) {
      categoryName.value = response.data[0].category.name;
    }
  } catch (error) {
    console.error('Fehler beim Laden der Übungen:', error);
  }
};

const startExercise = (exerciseId) => {
  router.push(`/exercise/${exerciseId}`);
};

onMounted(fetchExercises);
</script>

<style scoped>
.q-toolbar {
  border-radius: 10px;
}

.exercise-card {
  border-radius: 10px;
  padding: 10px;
  text-align: center;
  cursor: pointer;
  transition: 0.3s;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
}

.exercise-card:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.text-h6 {
  font-weight: bold;
}
</style>
