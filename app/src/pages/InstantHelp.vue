<template>
  <q-page class="q-pa-md">
    <div class="text-h5 text-center q-mb-md">Sofort-Hilfe</div>

    <div class="row q-col-gutter-md">
      <q-card
        v-for="topic in helpTopics"
        :key="topic.label"
        class="help-card col-12 col-md-5"
        clickable
        v-ripple
        @click="selectTopic(topic)"
      >
        <q-img :src="topic.image" class="card-image" />
        <q-card-section>
          <div class="text-h6">{{ topic.label }}</div>
          <q-item-label caption>{{ topic.description }}</q-item-label>
        </q-card-section>
      </q-card>
    </div>

    <!-- Eingabe-Feld für KI-Abfrage -->
    <q-dialog v-model="showInput">
      <q-card class="q-pa-md">
        <q-card-section>
          <div class="text-h6">Beschreibe dein Gefühl</div>
          <q-input v-model="userInput" filled placeholder="Was beschäftigt dich?" />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Abbrechen" color="negative" v-close-popup />
          <q-btn label="Abfrage starten" color="primary" @click="sendToAI" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Lade-Animation mit atmender Welle während KI antwortet -->
    <q-dialog v-model="showLoading">
      <q-card class="q-pa-md text-center">
        <BreathingWaveLoader />
      </q-card>
    </q-dialog>


    <q-dialog v-model="showResponse">
  <q-card class="q-pa-md text-center">
    <!-- 🎨 Kreisförmige Illustration oben -->
    <div class="doctor-illustration">
      <q-img src="/doctor.jpg" contain />
    </div>

    <q-card-section>
      <div class="text-h6">Dein Tipp</div>
      <div class="q-mt-md">{{ aiResponse }}</div>
    </q-card-section>

    <q-card-actions align="right">
      <q-btn flat label="Schließen" color="negative" v-close-popup />
      <q-btn label="Speichern" color="primary" @click="saveTip" />
    </q-card-actions>
  </q-card>
</q-dialog>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { api } from 'boot/axios';
import BreathingWaveLoader from 'components/BreathingWaveLoader.vue';

const helpTopics = ref([
  { label: "Angst", image: "/angst.jpg", description: "Wenn du dich ängstlich fühlst und Beruhigung brauchst." },
  { label: "Panik", image: "/panik.jpg", description: "Soforthilfe bei Panikattacken und Kontrollverlust." },
  { label: "Stress", image: "/stress.avif", description: "Unter Druck? Hier sind Entspannungsmethoden für dich." },
  { label: "Einsamkeit", image: "/einsamkeit.jpg", description: "Wenn du dich allein fühlst und Unterstützung suchst." },
  { label: "Selbstzweifel", image: "/selbstzweifel.jpg", description: "Tipps, um dein Selbstvertrauen zu stärken." }
]);

const selectedTopic = ref(null);
const showInput = ref(false);
const showResponse = ref(false);
const userInput = ref('');
const aiResponse = ref('');
const showLoading = ref(false);

const selectTopic = (topic) => {
  selectedTopic.value = topic;
  showInput.value = true;
};

// const sendToAI = async () => {
//   const response = await api.post('/instant-help', {
//     topic: selectedTopic.value.label,
//     userInput: userInput.value
//   });

//   aiResponse.value = response.data.reply;
//   showInput.value = false;
//   showResponse.value = true;
// };

const sendToAI = async () => {
  showLoading.value = true; // Loader aktivieren

  try {
    const response = await api.post('/instant-help', {
      topic: selectedTopic.value.label,
      userInput: userInput.value
    });

    aiResponse.value = response.data.reply;
  } catch (error) {
    aiResponse.value = "Es gab ein Problem mit der KI. Bitte versuche es später erneut.";
  }

  showLoading.value = false; // Loader deaktivieren
  showInput.value = false;
  showResponse.value = true;
};

const saveTip = async () => {
  await api.post('/save-tips', {
    topic: selectedTopic.value.label,
    tip: aiResponse.value
  });

  showResponse.value = false;
};
</script>

<style scoped>
.help-card {
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
  margin-bottom: 16px; /* Abstand zwischen Karten */
}

.help-card:hover {
  transform: scale(1.03);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.card-image {
  height: 140px;
  object-fit: cover;
}

.doctor-illustration {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  overflow: hidden;
  margin: 0 auto 15px;
  border: 3px solid #4CAF50;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
