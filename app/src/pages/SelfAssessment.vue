<template>
  <q-page class="q-pa-md flex flex-center">
    <div class="assessment-container">
      <q-toolbar class="bg-primary text-white rounded-borders">
        <q-btn flat round dense icon="arrow_back" @click="router.push('/')" />
        <q-toolbar-title>Selbsteinschätzung</q-toolbar-title>
      </q-toolbar>

      <!-- Fortschrittsbalken -->
      <q-linear-progress :value="progress" color="primary" class="q-mt-md" />
      <div class="text-center q-mt-sm">Frage {{ currentIndex + 1 }} von {{ questions.length }}</div>

      <!-- Frage-Karte -->
      <q-card class="q-mt-lg text-center question-card" v-if="currentQuestion">
        <q-card-section>
          <div class="text-h5">{{ currentQuestion.text }}</div>
        </q-card-section>
        <q-card-actions class="q-gutter-sm">
          <q-btn v-for="option in currentQuestion.options" :key="option"
                 :label="option" color="primary"
                 class="full-width"
                 @click="answerQuestion(option)"/>
        </q-card-actions>
      </q-card>

      <!-- Ergebnis-Anzeige -->
      <q-card v-if="showResults" class="q-mt-lg text-center result-card">
        <q-card-section>
          <div class="text-h5">Empfohlene Übungen</div>
          <div v-for="(exercise, index) in recommendedExercises" :key="index" class="q-mt-sm">
            <q-chip color="primary" text-color="white">{{ exercise }}</q-chip>
          </div>
        </q-card-section>
        <q-card-actions>
          <q-btn label="Weiter zu den Übungen" color="positive" class="full-width" @click="goToExercises" />
        </q-card-actions>
      </q-card>
    </div>
  </q-page>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const currentIndex = ref(0);
const answers = ref([]);
const showResults = ref(false);

// Fragenliste
const questions = ref([
  { text: "Bist du oft selbstkritisch?", options: ["Ja", "Nein"] },
  { text: "Belastet dich das stark?", options: ["Sehr stark", "Ziemlich", "Ein wenig", "Gar nicht"] },
  { text: "Reagierst du auf neue Situationen häufig mit Anspannung?", options: ["Ja", "Nein"] },
  { text: "Fühlst du dich oft erschöpft?", options: ["Täglich", "Oft", "Selten", "Nie"] },
]);

const progress = computed(() => currentIndex.value / questions.value.length);
const currentQuestion = computed(() => questions.value[currentIndex.value]);

const answerQuestion = (option) => {
  answers.value.push(option);
  if (currentIndex.value < questions.value.length - 1) {
    currentIndex.value++;
  } else {
    calculateResults();
  }
};

const calculateResults = () => {
  showResults.value = true;
  localStorage.setItem('selfAssessmentDone', 'true');
};

const goToExercises = () => {
  router.push('/exercise-categories');
};
</script>

<style scoped>
.assessment-container {
  max-width: 500px;
  text-align: center;
}

.question-card, .result-card {
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
</style>
