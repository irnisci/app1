<template>
  <q-page class="q-pa-md">
    <q-btn flat @click="router.go(-1)" icon="arrow_back_ios"/>
    <div class="text-h5 text-center q-mb-md">Konto-Einstellungen</div>

    <q-card class="q-pa-md">
      <q-card-section>
        <div class="text-h6">Passwort ändern</div>
      </q-card-section>

      <q-card-section>
        <q-input v-model="oldPassword" label="Aktuelles Passwort" type="password" outlined />
        <q-input v-model="newPassword" label="Neues Passwort" type="password" outlined class="q-mt-md" />
        <q-input v-model="confirmPassword" label="Neues Passwort bestätigen" type="password" outlined class="q-mt-md" />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Passwort ändern" color="primary" @click="changePassword" />
      </q-card-actions>
    </q-card>
  </q-page>
</template>


<script setup>
import { ref } from 'vue';
import { api } from 'boot/axios';
import { useQuasar } from 'quasar';
import{useRouter} from 'vue-router'

const router = useRouter();
const $q = useQuasar();
const oldPassword = ref('');
const newPassword = ref('');
const confirmPassword = ref('');

const changePassword = async () => {
  if (newPassword.value !== confirmPassword.value) {
    $q.notify({ type: 'negative', message: 'Passwörter stimmen nicht überein!' });
    return;
  }

  try {
    await api.post('/change-password', {
      old_password: oldPassword.value,
      new_password: newPassword.value,
    });

    $q.notify({ type: 'positive', message: 'Passwort erfolgreich geändert!' });
    oldPassword.value = '';
    newPassword.value = '';
    confirmPassword.value = '';
  } catch (error) {
    $q.notify({ type: 'negative', message: error.response?.data?.message || 'Fehler beim Ändern des Passworts!' });
  }
};

</script>
