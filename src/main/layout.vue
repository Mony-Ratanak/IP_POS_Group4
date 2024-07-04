<template>
    <main class="flex h-screen overflow-hidden w-screen">
      <div class="w-[300px] bg-[#1F1D2B] flex items-center flex-col">

        <div class="mt-5 mb-5 ml-3 flex items-center">
          <img src="../assets/icons/Logo.png" alt="logo" class="h-[56px] w-[56px]">
          <h1 class="text-2xl pl-4 text-[#EA7C69] font-bold">Hero Restaurant</h1>
        </div>
  
        <!-- <RouterLink to="home" class="h-[48px] w-[230px] bg-red-500 flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
          <HomeIcon class="text-white" />
          <p class="text-xl font-medium text-white ml-3">Home</p>
        </RouterLink>
  
        <RouterLink to="customer" class="h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center text-[#EA7C69] rounded-[15px]">
          <Users class="" />
          <p class="text-xl font-medium ml-3">Customer</p>
        </RouterLink> -->
        <template v-if="role === 'Admin'">

          <RouterLink :to="{ path: '/homepage' }" :class="{ 'bg-red-500 text-white': $route.path === '/homepage', 'text-red-500': $route.path !== '/homepage' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <HomeIcon class="h-6 w-6" />
              <p class="link-text ml-3 text-xl font-medium">Home</p>
          </RouterLink>

          <!-- <RouterLink :to="{ path: '/productItem' }" :class="{ 'bg-red-500 text-white': $route.path === '/productItem', 'text-red-500': $route.path !== '/productItem' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <Boxes class="h-6 w-6 " />
              <p class="link-text ml-3 text-xl font-medium">ProductItem</p>
          </RouterLink> -->

          <div @click="toggleProductMenu"  class=" h-[48px] w-[230px] flex  text-red-600  justify-between pl-5 cursor-pointer items-center rounded-[15px]">
            <div class="flex">
              <Boxes class="h-6 w-6" />
             <p class="link-text ml-3 text-xl font-medium">Store</p>
            </div>
            
            <ChevronDown></ChevronDown>
          </div>
          
          <div v-if="productMenuOpen" class="ml-5">
            <RouterLink :to="{ path: '/productItem' }" :class="{ 'bg-red-500 text-white': $route.path === '/productItem', 'text-red-500': $route.path !== '/productItem' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <Package class="h-6 w-6" />
              <p class="link-text ml-3 text-xl font-medium">Product</p>
            </RouterLink>

            <RouterLink :to="{ path: '/productType' }" :class="{ 'bg-red-500 text-white': $route.path === '/productType', 'text-red-500': $route.path !== '/productType' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <Shapes class="h-6 w-6" />
              <p class="link-text ml-3 text-xl font-medium">ProductType</p>
            </RouterLink>
          </div>

          <RouterLink :to="{ path: '/order' }" :class="{ 'bg-red-500 text-white': $route.path === '/order', 'text-red-500': $route.path !== '/order' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <ShoppingCart class="h-6 w-6 " />
              <p class="link-text ml-3 text-xl font-medium">Order</p>
          </RouterLink>     
          
          <RouterLink :to="{ path: '/orderDetails' }" :class="{ 'bg-red-500 text-white': $route.path === '/orderDetails', 'text-red-500': $route.path !== '/orderDetails' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <ClipboardList class="h-6 w-6 " />
              <p class="link-text ml-3 text-xl font-medium">Order Details</p>
          </RouterLink>  

          <RouterLink :to="{ path: '/user' }" :class="{ 'bg-red-500 text-white': $route.path === '/user', 'text-red-500': $route.path !== '/user' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <Users class="h-6 w-6 " />
              <p class="link-text ml-3 text-xl font-medium">User</p>
          </RouterLink>

          <RouterLink :to="{ path: '/userAccount' }" :class="{ 'bg-red-500 text-white': $route.path === '/userAccount', 'text-red-500': $route.path !== '/userAccount' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
              <UserCog class="h-6 w-6 " />
              <p class="link-text ml-3 text-xl font-medium">User Account</p>
          </RouterLink>

        </template>

        <!-- Staff Links -->
        <template v-else-if="role === 'Staff'">

          <RouterLink :to="{ path: '/order' }" :class="{ 'bg-red-500 text-white': $route.path === '/order', 'text-red-500': $route.path !== '/order' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
            <ShoppingCart class="h-6 w-6" />
            <p class="link-text ml-3 text-xl font-medium">Order</p>
          </RouterLink>

          <RouterLink :to="{ path: '/userAccount' }" :class="{ 'bg-red-500 text-white': $route.path === '/userAccount', 'text-red-500': $route.path !== '/userAccount' }" class="router-link h-[48px] w-[230px] flex justify-start pl-5 cursor-pointer items-center rounded-[15px]">
            <UserCog class="h-6 w-6" />
            <p class="link-text ml-3 text-xl font-medium">User Account</p>
          </RouterLink>

        </template>
      </div>

      <section class="h-screen w-screen bg-[#252836] ">
        <div class="bg-[#252836] drop-shadow-md w-full  h-[56px] overflow-hidden flex items-center justify-between pl-[24px] pr-[12px]">

          <h1 class="text-red-500 font-bold text-xl">{{ role }}</h1>

          <div class="flex items-center justify-center gap-4 text-white">
            <SunMoon />
            <BellDot />
            <Expand />
            <div>
              <DropdownMenu class=" ">

                <DropdownMenuTrigger>
                  <button @click="toggleMenu" class="relative">
                    <span class="relative">
                      <img v-if="user && user.avatar" class="w-8 h-8 rounded-full" :src="file + user.avatar" alt="User Avatar">
                      <User class="w-8 h-8" v-else />
                    </span>
                  </button>              
                </DropdownMenuTrigger>

                <DropdownMenuContent class="bg-[#252836] ">
                  <!-- <DropdownMenuLabel>My Account</DropdownMenuLabel> -->
                  <DropdownMenuSeparator />
                    <DropdownMenuItem class=" hover:bg-gray-500">
                      <button @click="myProfile" class="w-full text-left py-2 px-4 flex items-center">
                        <RefreshCcw class="mr-2 text-[#EA7C69] " />
                        <span class="text-md font-bold text-white">{{ user?.name }}</span>
                      </button>
                    </DropdownMenuItem>
                    <DropdownMenuItem class=" hover:bg-gray-500">
                      <button @click="myProfile" class="w-full text-left py-2 px-4 flex items-center">
                        <UserCircle class="mr-2 text-[#EA7C69] " />
                        <span class="text-md font-bold text-white">{{ translations.profile }}</span>
                      </button> 
                    </DropdownMenuItem>

                    <DropdownMenuItem class=" hover:bg-gray-500">
                      <button @click="logout" class="w-full text-left py-2 px-4 flex items-center">
                        <LogOut class="mr-2 text-[#EA7C69] " />
                        <span class="text-md font-bold text-white">{{ translations.logout }}</span>
                      </button>
                    </DropdownMenuItem>

                </DropdownMenuContent>
              </DropdownMenu>
            </div>
          </div>
        </div>
        <RouterView></RouterView>
      </section>
    </main>
</template>
  
  <script>
  import { ref, onMounted, watch } from 'vue';
  import { useRouter } from 'vue-router';
  import axiosClient from "@/service/GlobalApi";

  import { 
    BellDot, Expand, HomeIcon, LogOut, 
    RefreshCcw, SunMoon, User, UserCircle, 
    Users,  Boxes,  ClipboardList,  UserCog, ShoppingCart,ChevronDown, Package, Shapes } from 'lucide-vue-next';

  import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
  } from '@/components/ui/dropdown-menu';

  export default {

    name: 'TestComponent',
    components: {
      HomeIcon,Users,Expand,BellDot,SunMoon,User,UserCircle,LogOut,RefreshCcw,Boxes,ClipboardList,UserCog,ShoppingCart,ChevronDown,Package,Shapes,
      DropdownMenu,DropdownMenuContent, DropdownMenuItem,DropdownMenuLabel,DropdownMenuSeparator,DropdownMenuTrigger,
    },
    
    setup() {

      const user = ref(null);
      const role = ref('');
      const isMenuOpen = ref(false);
      const file = 'http://localhost:8003/'; // Adjust this URL as needed
      const router = useRouter();
      const productMenuOpen = ref(false);
  
      const translations = {
        profile: 'Profile',
        logout: 'Logout'
      };

      const updateUserRole = () => {
        role.value = user.value && user.value.id === 1 ? 'Admin' : 'Staff';
        // console.log(user?.value)
      };

      const linkClass = (path) => {
        return router.path === path ? 'bg-red-500 text-white' : 'text-red-500';
        // if (router.path === path) {
        //   return 'bg-red-500 text-white';
        // } else if (router.path === '/homepage' || router.path === '/product') {
        //   return 'text-red-500';
        // } else {
        //   return '';
        // }
      };
  
      onMounted(() => {

        const storedUser = localStorage.getItem('user');

        if (storedUser) {
          user.value = JSON.parse(storedUser);
          if (!user.value) {
            localStorage.clear();
            router.push('/auth/login');
          }
          else{
            updateUserRole()
          }
        }
      //  console.log(user.value);
      });

      // watch(user, (newValue) => {
      // if (newValue) {
      //   setUserRole(newValue.type_id);
      // }
      // });
      // watch(role, () => {
      //       role.value = localStorage.getItem('role');
      // });

      const toggleMenu = () => {
        isMenuOpen.value = !isMenuOpen.value;
      };
  
      const toggleProductMenu = () => {
        productMenuOpen.value = !productMenuOpen.value;
      };

      const myProfile = () => {
        router.push('/userAccount');
      };
  
      const logout = async () => {
        localStorage.clear();
        router.push('/');
      };
  
      return {
        user,
        role,
        isMenuOpen,
        file,
        toggleMenu,
        myProfile,
        logout,
        translations,
        linkClass,
        productMenuOpen,
        toggleProductMenu
      };
    }
  };
  </script>
  
  <style scoped>
    .router-link.bg-red-500 {
        background-color: red;
        color: white;
    }

    .router-link.text-red-500 {
        color: red;
    }
  </style>
  