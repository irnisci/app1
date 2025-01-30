<template>
  <q-page padding class="journal-background">
<q-btn flat icon="arrow_back_ios"  no-caps @click="router.go(-1)"/>

    <!-- Header -->
    <div class="text-h4 text-center q-mb-md text-grey-9">Tagebuch</div>
    <div class="q-pa-md">
      <q-card class="custom-card">
        <q-card-section>
          <div class="text-h6">Neuer Eintrag</div>
          <q-input
            v-model="newEntry.text"
            type="textarea"
            label="Deine Gedanken"
            autogrow
            class="q-mt-sm"
          />
          <div class="q-mt-sm">
            <div class="text-subtitle2">Wie fühlst du dich?</div>
            <div class="row q-gutter-sm">
              <q-btn
                v-for="emotion in emotions"
                :key="emotion"
                round
                :icon="emotion"
                @click="selectEmotion(emotion)"
                :color="newEntry.emotion === emotion ? 'primary' : 'secondary'"
              />
            </div>
          </div>
          <q-btn
            label="Speichern"
            color="primary"
            class="full-width q-mt-md"
            @click="saveEntry"
          />
        </q-card-section>
      </q-card>
    </div>
    <!-- Eintragsliste -->
    <div class="q-pa-md">
      <q-list bordered class="rounded-borders">
        <q-item v-for="entry in entries" :key="entry.id" class="q-mb-sm">
          <q-item-section>
            <q-item-label class="text-h6">{{ formatDate(entry.created_at) }}</q-item-label>
            <q-item-label caption>{{ formatTime(entry.created_at) }}</q-item-label>
            <q-item-label class="q-mt-sm">{{ entry.entry }}</q-item-label>
            <q-item-label v-if="entry.emotion" class="q-mt-sm">
              <strong>Emotion:</strong> {{ entry.emotion }}
            </q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-btn color="accent" flat round icon="delete" @click="deleteEntry(entry.id)" />
          </q-item-section>
        </q-item>
      </q-list>
    </div>



  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRouter } from 'vue-router';

const router = useRouter()
const $q = useQuasar();
const entries = ref([]);
const newEntry = ref({
  text: '',
  emotion:""
});

const emotions = ref(['😊', '😢', '😡', '😴', '😍']);

const selectEmotion = (emotion) => {
  newEntry.value.emotion = emotion;
};


const loadEntries = async () => {
  try {
    const response = await api.get('/journals');
    entries.value = response.data;
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Fehler beim Laden der Einträge.',
      position: 'top'
    });
  }
};

// Neuen Eintrag speichern
const saveEntry = async () => {
  if (!newEntry.value.text.trim()) {
    $q.notify({
      type: 'negative',
      message: 'Bitte gib einen Text ein.',
      position: 'top'
    });
    return;
  }

  try {
    const response = await api.post('/journals', {
      entry: newEntry.value.text,
      emotion: newEntry.value.emotion,
    });
    entries.value.unshift(response.data.journal);
    newEntry.value.text = '';
    newEntry.value.emotion = '';
    $q.notify({
      type: 'positive',
      message: 'Eintrag gespeichert!',
      position: 'top'
    });
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Fehler beim Speichern des Eintrags.',
      position: 'top'
    });
  }
};

// Eintrag löschen
const deleteEntry = async (id) => {
  try {
    await api.delete(`/journals/${id}`);
    entries.value = entries.value.filter(entry => entry.id !== id);
    $q.notify({
      type: 'positive',
      message: 'Eintrag gelöscht!',
      position: 'top'
    });
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Fehler beim Löschen des Eintrags.',
      position: 'top'
    });
  }
};


const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString();
};

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString();
};


onMounted(() => {
  loadEntries();
});
</script>

<style scoped>
.journal-background {
  background-color: #f5f5f5;
  min-height: 100vh;
}

.custom-card {
  border-radius: 15px;
  background: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.q-item {
  background: white;
  border-radius: 10px;
  margin-bottom: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.q-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.text-h4 {
  color: #333;
}

.text-h6 {
  color: #444;
}

.text-subtitle2 {
  color: #666;
}
</style>
