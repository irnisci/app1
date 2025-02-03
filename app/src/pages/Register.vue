<template>
  <q-page padding class="row items-center justify-evenly register-background">
    <q-card class="q-pa-md" style="width: 90%; max-width: 400px; border-radius: 12px;">
      <q-card-section>
        <div class="text-h6 text-center text-primary">Registrieren</div>
      </q-card-section>
      <q-card-section>
        <q-form @submit.prevent="onRegister">
          <q-input
            outlined
            v-model="state.name"
            label="Name (optional)"
            class="q-mt-sm"
            :rules="[val => !val || val.length > 2 || 'Name muss mindestens 3 Zeichen haben']"
          />
          <q-input
            outlined
            v-model="state.email"
            type="email"
            label="Email"
            class="q-mt-sm"
            :rules="[val => !!val || 'Email ist erforderlich', val => /.+@.+\..+/.test(val) || 'Email muss gültig sein']"
          />
          <q-input
            outlined
            v-model="state.password"
            type="password"
            label="Passwort"
            class="q-mt-sm"
            :rules="[val => !!val || 'Passwort ist erforderlich', val => val.length >= 6 || 'Passwort muss mindestens 6 Zeichen haben']"
          />
          <q-input
            outlined
            v-model="state.passwordConfirm"
            type="password"
            label="Passwort bestätigen"
            class="q-mt-sm"
            :rules="[val => !!val || 'Passwortbestätigung ist erforderlich', val => val === state.password || 'Passwörter müssen übereinstimmen']"
          />
          <q-btn
            label="Registrieren"
            type="submit"
            color="primary"
            class="full-width q-mt-sm"
            :loading="loading"
          />
        </q-form>
        <div class="text-center q-mt-md">
          <p>Bereits ein Konto? <router-link to="/login" class="text-primary">Login</router-link></p>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';

const $q = useQuasar();
const loading = ref(false);

const state = reactive({
  name: '',
  email: '',
  password: '',
  passwordConfirm: ''
});

const onRegister = async () => {
  loading.value = true;
  try {
    const response = await api.post('/register', {
      name: state.name,
      email: state.email,
      password: state.password,
      password_confirmation: state.passwordConfirm
    });
    $q.notify({
      type: 'positive',
      message: 'Registrierung erfolgreich!',
      position: 'top'
    });
    console.log(response.data);
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Fehler bei der Registrierung. Bitte versuche es erneut.',
      position: 'top'
    });
    console.error(error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.register-background {
  background: url('assets/register-login.jpg') no-repeat center center;
  background-size: cover;
}

.q-card {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
}
</style>



