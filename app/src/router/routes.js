
// const routes = [
//   {
//     path: '/',
//     component: () => import('layouts/MainLayout.vue'),
//     children: [
//       { path: '', component: () => import('pages/IndexPage.vue') }
//     ]
//   },

//   {
//     path: '/register',
//     component: () => import('pages/Register.vue')
//   },

//   // Always leave this as last one,
//   // but you can also remove it
//   {
//     path: '/:catchAll(.*)*',
//     component: () => import('pages/ErrorNotFound.vue')
//   }
// ]

// export default routes
import{ createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue'), meta: { requiresAuth: true } }
    ]
  },
  {
    path: '/register',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Register.vue') }
    ]
  },
  {
    path: '/login',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Login.vue') }
    ]
  },
  {
    path: '/journal',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Journal.vue') }
    ]
  },
  {
    path: '/instant-help',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/InstantHelp.vue') }
    ]
  },

  {
    path: '/breathing',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Breathing.vue') }
    ]
  },
  {
    path: '/music',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Music.vue') }
    ]
  },
  {
    path: '/visual',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Visual.vue') }
    ]
  },

  {
    path: '/exercises',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Exercises.vue') }
    ]
  },
  {
    path: '/exercise-categories',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/ExerciseCategories.vue') }
    ]
  },

  {
    path: '/category/:id/exercises',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/ExercisesList.vue') }
    ]
  },

  {
    path: '/exercise/:id',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/ExerciseDetail.vue') }
    ]
  },
  {
    path: '/exercise/:id/chat',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/chat/SupportChat.vue') }
    ]
  },
  {
    path: '/self-assessment',
    component: () => import('layouts/SimpleLayout.vue'),
    children:[
      {path: '', component: () => import('pages/SelfAssessment.vue')}
    ]
  },
  // Always leave this as last one,
  // but you can also remove it

  //account routes
  {
    path: '/account',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
     { path: '',component: () => import('pages/Account.vue')}
    ]
  },
  {
    path: '/settings',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
     { path: '',component: () => import('pages/Settings.vue')}
    ]
  },
  {
    path: '/saved-tips',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
     { path: '',component: () => import('pages/SavedTips.vue')}
    ]
  },

  //Courses
  {
    path: '/courses',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Courses.vue') }
    ]
  },
  {
    path: '/courses/:id',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/CourseDetail.vue') }
    ]
  },
  //module
  {
    path: '/modules/:id',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/ModuleDetail.vue') }
    ]
  },
  //lesson
  {
    path: '/lessons/:id',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/LessonDetail.vue') }
    ]
  },

  //TEST ALLE MODULE
  {
    path: '/courses/:id/modules',
    component: () => import('layouts/SimpleLayout.vue'),
    children: [
      { path: '', component: () => import('pages/ModuleOverview.vue') }
    ]
  },

  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('auth_token');
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default routes;
