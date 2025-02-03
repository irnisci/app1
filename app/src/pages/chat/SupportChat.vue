<template>
  <q-page class="q-pa-md flex flex-center">
    <div class="chat-container">
      <q-toolbar class="bg-primary text-white rounded-borders">
        <q-btn flat round dense icon="arrow_back" @click="router.go(-1)" />
        <q-toolbar-title>KI-Unterstützung</q-toolbar-title>
      </q-toolbar>

      <!-- Chatverlauf -->
      <q-chat-message
        v-for="(msg, index) in messages"
        :key="index"
        :name="msg.sender"
        :text="[msg.text]"
        :sent="msg.sent"
        :bg-color="msg.sent ? 'primary' : 'grey-3'"
        :text-color="msg.sent ? 'white' : 'black'"
      />

      <!-- Eingabefeld -->
      <div class="q-mt-md">
        <q-input
          v-model="userMessage"
          outlined
          placeholder="Schreibe deine Gedanken..."
          @keyup.enter="sendMessage"
        />
        <q-btn label="Senden" color="primary" class="full-width q-mt-sm" @click="sendMessage" />
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from 'boot/axios';

const router = useRouter();
const route = useRoute();
const userMessage = ref('');
const messages = ref([
  { sender: 'KI', text: 'Wie fühlst du dich nach der Übung?', sent: false },
]);

const sendMessage = async () => {
  if (!userMessage.value.trim()) return;

  // Nutzer-Nachricht zur Chat-Historie hinzufügen
  messages.value.push({ sender: 'Du', text: userMessage.value, sent: true });

  try {
    const response = await api.post('/chat', {
      message: userMessage.value,
      exercise_id: route.params.id,
      chat_history: messages.value.map(msg => ({
        role: msg.sent ? 'user' : 'assistant',
        content: msg.text
      }))
    });

    // KI-Antwort speichern
    messages.value.push({ sender: 'Ki-Doc ', text: response.data.reply, sent: false });
  } catch (error) {
    console.error('Fehler bei der KI-Antwort:', error);
  }

  userMessage.value = '';
};

// Begrüßungsnachricht der KI basierend auf der Übung
onMounted(() => {
  setTimeout(() => {
    messages.value.push({ sender: 'KI-Doc', text: 'Falls die Übung schwer war, das ist okay. Magst du darüber sprechen?', sent: false });
  }, 1000);
});
</script>

<style scoped>
.chat-container {
  max-width: 400px;
  width: 100%;
}

.q-toolbar {
  border-radius: 10px;
}
</style>
