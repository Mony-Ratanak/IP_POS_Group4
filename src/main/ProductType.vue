<template>
    <div class="container-listing-user-header flex gap-2 justify-between items-center py-2 px-5 mt-5 ml-4 mr-4 rounded-2xl drop-shadow-lg shadow-inner bg-[#252836]">
      <div class="flex flex-1 items-center font-medium">
        <div class="flex items-center whitespace-nowrap">
          <HomeIcon class="text-red-500" />
        </div>
  
        <div class="flex items-center ml-1 whitespace-nowrap">
          <ChevronRight class="icon-size-4.5 text-red-500" />
          <span class="ml-1 text-gray-400 text-xl">Product Type</span>
        </div>
      </div>
  
      <div class="flex items-center gap-3">
        <div class="sm:flex flex justify-start items-center relative">
          <Input type="text" placeholder="Search..." v-model="key" @input="handleSearchInput" class="pl-12 h-[44px] w-[230px] rounded-md bg-[#252836] text-xl border-[#393C49]" />
          <span class="absolute left-4">
            <Search class="text-gray-400" />
          </span>
        </div>
  
        <div class="custom-button-flat">
          <Sheet>
            <SheetTrigger>
              <button class="w-11 h-11 rounded-md flex justify-center items-center bg-[#ef1515]">
                <Plus class="text-2xl font-bold text-white" />
              </button>
            </SheetTrigger>
            <SheetContent class="bg-[#1F1D2B]">
              <SheetHeader>
                <SheetTitle class="text-white text-center">Create New Product Type</SheetTitle>
                <SheetDescription class="text-white flex flex-col gap-4 items-center">
                  <div class="w-full">
                    <p class="mb-2">Upload your images</p>
                    <input type="file" @change="handleFileUpload" class="h-[44px] w-full rounded-lg pl-3 mt-3 text-white" />
                  </div>
                  <div class="w-full -mt-3">
                    <p class="mb-2">Enter name</p>
                    <input type="text" v-model="newProductType.name" placeholder="Enter name" class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                  </div>
                  <button type="button" class="h-[44px] w-[240px] bg-[#252836] rounded-full hover:bg-slate-600" @click="createProductType">Create</button>
                </SheetDescription>
              </SheetHeader>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </div>
  
    <div class="px-3 pt-5">
      <div v-if="isLoading" class="flex justify-center flex-col items-center">
        <p class="text-xl mb-4">Please wait! Requesting Data...</p>
        <span class="flex justify-center">
          <Loader class="animate-spin w-5 h-5 text-white" />
        </span>
      </div>
      <div v-if="data.length === 0 && !isLoading" class="flex flex-col justify-center items-center mb-4">
        <span class="text-2xl">No Data</span>
      </div>
      <Table class="w-[97%] ml-6 mt-5">
        <thead>
          <tr>
            <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">NO</th>
            <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Name</th>
            <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Image</th>
            <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Quantity</th>
            <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in data" :key="item.id" class="text-white">
            <td class="px-5 py-3 border-b border-gray-200">{{ index + 1 }}</td>
            <td class="px-5 py-3 border-b border-gray-200">{{ item.name }}</td>
            <td class="px-5 py-3 border-b border-gray-200">
              <img :src="item.image ? fileUrl + item?.image : null" alt="image" style="width: 45px; height: 45px;">
            </td>
            <td class="px-5 py-3 border-b border-gray-200">{{ item.n_of_products }}</td>
            <td class="px-5 py-5 border-b border-gray-200 flex justify-between items-center">
              {{ item.created_at }}
              <!-- Dropdown menu for future actions -->
                <DropdownMenu>
                    <DropdownMenuTrigger>
                        <button>
                            <MoreVertical class="icon-size-6 text-gray-400 hover:text-gray-500  cursor-pointer"></MoreVertical>
                        </button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent class="bg-[#252836] mr-16">
                        <DropdownMenuSeparator />
                        <DropdownMenuItem class="hover:bg-gray-500" @click="openUpdateDialog(item)" >
                            <div class="flex items-center cursor-pointer p-2 text-white">
                                <SquarePen class="icon-size-5 mr-2 " />
                                <span class="text-lg ">Update </span>
                            </div>
                        </DropdownMenuItem>
                        <DropdownMenuItem class="hover:bg-gray-500" @click="openDeleteDialog(item)">
                            <div  class="flex items-center cursor-pointer p-2">
                                <Trash2 class="icon-size-5 text-red-400 mr-2" />
                                <span class="text-lg text-red-400">Delete</span>
                            </div>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

            </td>
          </tr>
        </tbody>
      </Table>
    </div>

    <!-- edit production -->
    <Sheet v-model:open="openDialog">
      <SheetContent class="bg-[#1F1D2B]">
        <SheetHeader>
          <SheetTitle class="text-white text-center">Update Product Type</SheetTitle>
          <SheetDescription class="text-white flex flex-col gap-4 items-center">
            <div class="w-full">
              <p class="mb-2">Upload your images</p>
                <input type="file" @change="handleFileUpload" class="h-[44px] w-full rounded-lg pl-3 mt-3 text-white" />
            </div>
            <div class="w-full -mt-3">
              <p class="mb-2">Enter name</p>
              <input type="text" v-model="selectedProduct_type.name" placeholder="Enter name" class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
            </div>
            <button type="button" class="h-[44px] w-[240px] bg-[#252836] rounded-full hover:bg-slate-600" @click="updateProductType">Save</button>
          </SheetDescription>
        </SheetHeader>
      </SheetContent>
    </Sheet>

    <!-- delete product -->
    <AlertDialog v-model:open="deleteDialog" class="bg-zinc-500">
      <AlertDialogContent class="bg-[#1F1D2B] text-white">
        <AlertDialogHeader>
          <AlertDialogTitle>Confirm Deletion</AlertDialogTitle>
          <AlertDialogDescription>Are you sure you want to delete this product type?</AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel class=" hover:bg-slate-500">Cancel</AlertDialogCancel>
          <AlertDialogAction @click=" deletedProductType" class=" bg-green-600 border hover:bg-red-700">Delete</AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>

  </template>
  
  <script>
  import { Search, HomeIcon, ChevronRight, Plus, Loader ,MoreVertical, SquarePen, Trash2} from "lucide-vue-next";
  import { Input } from "@/components/ui/input";
  import { ref, onMounted } from 'vue';
  import axiosClient from "@/service/GlobalApi";
  import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger
  } from '@/components/ui/sheet';
  import {
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuLabel,
        DropdownMenuSeparator,
        DropdownMenuTrigger,
    } from '@/components/ui/dropdown-menu';
  import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
  } from '@/components/ui/alert-dialog';
  
  export default {
    name: 'Product Type',
    components: {
      Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger,SquarePen,Trash2,
      Input, Search, HomeIcon, ChevronRight, Plus, Loader,MoreVertical, DropdownMenu, DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuLabel,
        DropdownMenuSeparator,
        DropdownMenuTrigger,
        AlertDialog,
        AlertDialogAction,
        AlertDialogCancel,
        AlertDialogContent,
        AlertDialogDescription,
        AlertDialogFooter,
        AlertDialogHeader,
        AlertDialogTitle,
        AlertDialogTrigger,
    },
    
    setup() {

      const isLoading = ref(true);
      const data      = ref([]);
      const key       = ref('');
      const dataSource        = ref([]);
      const fileUrl   = import.meta.env.VITE_APP_FILE_BASE_URL;

      const newProductType = ref({
        name: '',
        image: null,
      });

      const selectedProduct_type = ref([]);
      const openDialog = ref(false);
      const deleteDialog = ref(false);
  
      const listing = async () => {
        isLoading.value = true;
        try {
          const response = await axiosClient.get('admin/products/types');
          // console.log(response);
          data.value = response.data;
        } catch (e) {
          console.error(e);
        } finally {
          isLoading.value = false;
        }
      };
  
      const createProductType = async () => {

        const formData = new FormData();
        formData.append('name', newProductType.value.name);        
        if (newProductType.value.image) {
          formData.append('image', newProductType.value.image);
        }
        isLoading.value = true;
        try {
          const res = await axiosClient.post('admin/products/types', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });

          // Clear the newProductType object
          newProductType.value.name = '';
          newProductType.value.image = null;

          isLoading.value = false;
          data.value = res.data;
          dataSource.value = data.value;

          listing();
        } catch (e) {
          console.error(e);
        }
      };

      const openUpdateDialog = (data) => {
        selectedProduct_type.value = data;
        openDialog.value = true;
      }

      const updateProductType = async () => {
        
        const productTypeID = selectedProduct_type.value.id;
        const formData = new FormData();

        formData.append('name', selectedProduct_type.value.name);

        for (const key in selectedProduct_type.value) {
          if (key === 'image' && selectedProduct_type.value[key].startsWith('data:image')) {
            formData.append('image', selectedProduct_type.value[key]);
          } else {
            formData.append(key, selectedProduct_type.value[key]);
          }
        }

        isLoading.value = true;

        try {

          const response = await axiosClient.post(`admin/products/types/${productTypeID}`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });

          isLoading.value = false;

          // Update the data array with the updated product type
          const updateProductIndex = data.value.findIndex(
            (item) => item.id === productTypeID          
          );
         
          if(updateProductIndex !== -1){
            data.value[updateProductIndex] = response.data;
            dataSource.value = [...data.value];
          }

          data.value.push(response.data);    
          listing();   
          
        }catch (e){
          console.error(e);
        }
      };

      const openDeleteDialog = (data) => {
        selectedProduct_type.value = data;
        deleteDialog.value = true;
      }

      const deletedProductType = async () => {

        isLoading.value = true;

        if (!selectedProduct_type.value || !selectedProduct_type.value.id) {
          console.error('Product not found');
          return;
        }
        try {
          await axiosClient.delete(`/admin/products/types/${selectedProduct_type.value.id}`);
          data.value = data.value.filter(product => product.id !== selectedProduct_type.value.id);
          deleteDialog.value = false;
          await listing();
        } catch (err) {
          console.error('Something went wrong:', err);
        }
      };
  
      const handleSearchInput = () => {
        // Implement search functionality here
      };
  
      const handleFileUpload = (event)=>{

        const file = event.target.files[0];
        // Update the newProductType.value.image property to the actual file object.
        if (file) {
          const reader = new FileReader();
          reader.onload = () => {
            newProductType.value.image = reader.result; // Only the base64 string
            // console.log(reader.result); 
            selectedProduct_type.value.image = reader.result;
          };
          reader.readAsDataURL(file);
        }
      }
  
      onMounted(() => {
        listing();
      });
  
      return {
        isLoading, data, key, newProductType, selectedProduct_type, listing, createProductType, handleSearchInput, handleFileUpload,
        fileUrl, openUpdateDialog, updateProductType,openDialog, openDeleteDialog,deleteDialog,deletedProductType
      };
    }
  };
  </script>
  
  <style scoped>
  /* Add your scoped styles here */
  </style>
  