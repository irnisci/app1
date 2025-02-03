<template>
  <q-page padding class="row items-center justify-evenly login-background">
    <q-card class="q-pa-md" style="width: 90%; max-width: 400px; border-radius: 12px;">
      <q-card-section>
        <div class="text-h6 text-center text-primary">Login</div>
      </q-card-section>
      <q-card-section>
        <q-form @submit.prevent="onLogin">
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
            :rules="[val => !!val || 'Passwort ist erforderlich']"
          />
          <q-btn
            label="Login"
            type="submit"
            color="primary"
            class="full-width q-mt-sm"
            :loading="loading"
          />
        </q-form>
        <div class="text-center q-mt-md">
          <p>Noch kein Konto? <router-link to="/register" class="text-primary">Registrieren</router-link></p>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRouter } from 'vue-router';

const $q = useQuasar();
const router = useRouter();
const loading = ref(false);

const state = reactive({
  email: '',
  password: ''
});

const onLogin = async () => {
  loading.value = true;
  try {
    const response = await api.post('/login', {
      email: state.email,
      password: state.password
    });
    $q.notify({
      type: 'positive',
      message: 'Login erfolgreich!',
      position: 'top'
    });
    localStorage.setItem('auth_token', response.data.token); // Token speichern
    router.push('/'); // Weiterleiten zur Startseite
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Fehler beim Login. Bitte überprüfe deine Anmeldedaten.',
      position: 'top'
    });
    console.error(error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-background {
  /* background: url('assets/register-login.jpg') no-repeat center center; */
  background :url('/doctor.jpg') no-repeat top center;
  background-size: cover;
}

.q-card {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
}
</style>
