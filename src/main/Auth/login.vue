<template>
  <div class="flex items-center mt-20 justify-center">

    <!-- ==============================================>> Login Form -->
    <div class="background w-[330px] h-[550px] border px-2 rounded-lg">
      <div class="flex flex-col justify-center px-3">

        <div class="flex flex-col justify-center items-center">
          <p class="text-2xl text-blue-700 font-bold text-center mt-20 ">POS Restaurant Management</p>
          <p class="text-3xl text-black justify-start items-start mt-5">Login Page Now</p>
        </div>

        <!-- Sign in form -->
        <form class="mt-5 custom-form" @submit.prevent="login">
          <!-- Email field -->
          <div class="mb-3">
            <label for="email" class="block text-gray-700">Email</label>
            <input
              type="email"
              id="email"
              v-model="logInForm.email"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
            <div v-if="emailErrors.length > 0" class="text-red-500 text-sm mt-1">
              <span>{{ emailErrors[0] }}</span>
            </div>
          </div>

          <!-- Password field -->
          <div class="mb-6">
            <label for="password" class="block text-gray-700">Password</label>
            <div class="relative">
              <input
                type="password"
                id="password"
                v-model="logInForm.password"
                :type="showPassword ? 'text' : 'password'"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <button
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                @click="togglePasswordVisibility"
              >
                <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
              </button>
            </div>
            <div v-if="passwordErrors.length > 0" class="text-red-500 text-sm mt-1">
              <span>{{ passwordErrors[0] }}</span>
            </div>
          </div>

          <!-- Submit button -->
          <button
            class="w-full mt-6 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 disabled:opacity-50"
            :disabled="isLoading || logInForm.email === '' || logInForm.password === ''"
            type="submit"
            >

            <span v-if="!isLoading">Login</span>
            <span v-else class="flex justify-center"><Loader class="animate-spin w-5 h-5" /></span>

            
          </button>

          <!-- <Loader class="animate-spin text-black w-5 h-5" /> -->
        </form>
      </div>
    </div>

    <!-- ==============================================>> Login Banner -->
    <div class=" ">
      <!-- Background -->
      <img
        class="image h-[700px] w-[700px]"
        src="https://img.freepik.com/free-vector/privacy-policy-concept-illustration_114360-7853.jpg?size=626&ext=jpg&ga=GA1.1.1487890458.1702010638"
        alt="image"
      />
      <!-- Content -->
      <div class="z-10 w-full custom-position"></div>
    </div>
  </div>
</template>

<script>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  import { Loader } from 'lucide-vue-next';

export default {
  name: 'loginpage',
  
  components: {
    Loader,
  }, 

  setup() {

    const logInForm = ref({
      email: '',
      password: '',
    });

    const router = useRouter();
    const isLoading = ref(false);
    const showPassword = ref(false);
    const setLoader = ref();
    const emailErrors = ref([]);
    const passwordErrors = ref([]);

    const login = async () => {
      emailErrors.value = [];
      passwordErrors.value = [];

      if (!logInForm.value.email) {
        emailErrors.value.push('សូមបញ្ចូលEmail');
        return;
      }

      if (!logInForm.value.password) {
        passwordErrors.value.push('សូមបញ្ចូលលេខសម្ងាត់');
        return;
      }

      isLoading.value = true;

      try {
        const response = await axios.post('http://127.0.0.1:8002/api/auth/login', {
          email: logInForm.value.email,
          password: logInForm.value.password,
        });

        const data = response.data;

        if (data.access_token) {
          localStorage.setItem('accessToken', data.access_token);
          localStorage.setItem('user', JSON.stringify(data.user));
          router.push('/homepage');
        } else {
          emailErrors.value.push('Login failed. Please check your credentials.');
        }
      } catch (error) {
        console.error('Login error:', error);
        emailErrors.value.push('Login failed. Please check your credentials.');
      } finally {
        isLoading.value = false;
      }
    };

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value;
    };

    return {
      logInForm,
      isLoading,
      showPassword,
      emailErrors,
      passwordErrors,
      login,
      togglePasswordVisibility,
      Loader 
    };
  },
};
</script>


<style scoped>

</style>
