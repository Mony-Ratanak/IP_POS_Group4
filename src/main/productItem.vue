<template>
  <div class="container-listing-products-section">
    <div class="container-listing-user-header flex gap-2 justify-between items-center py-2 px-5 mt-5 ml-4 mr-4 rounded-2xl drop-shadow-lg shadow-inner    bg-[#252836]">
      <div class="flex flex-1 items-center font-medium">
        <div class="flex items-center whitespace-nowrap">
          <HomeIcon class=" text-white"/>
        </div>

        <div class="flex items-center ml-1 whitespace-nowrap">
          <ChevronRight class="icon-size-4.5 text-white"/>
          <span class="ml-1 text-white text-xl">Product Item</span>
        </div>
      </div>

      <!-- <div class="custom-select-header hidden sm:flex">
        <div class="min-w-60 max-w-60">
          <input v-model="key" placeholder="លេខកូដ ឬឈ្មោះ" @keyup.enter="listing" class="h-[44px] rounded-md pl-2 bg-[#252836]  text-xl border"/>
        </div>
      </div> -->

      <div class="flex items-center gap-3"> 
        <div class="sm:flex flex  justify-start items-center">
          <Input  type="text" placeholder="Search..." v-model="key" @input="handleSearchInput" class="pl-12 h-[44px] w-[230px] rounded-md bg-[#252836] text-xl border-[#393C49]" />
            <span class="  absolute px-4 ">
              <Search class="text-gray-400" />
            </span>
        </div>

        <div class="custom-botton-flat">
          <Sheet>
            <SheetTrigger>
              <button class="w-11 h-11 rounded-md flex justify-center items-center bg-[#ef1515]">
                <Plus class="text-2xl font-bold text-white" />
              </button>
            </SheetTrigger>
            <SheetContent class="bg-[#1F1D2B]">
              <SheetHeader >
                <SheetTitle class="text-white text-center">Create New Product</SheetTitle>
                  <SheetDescription class="text-white flex flex-col gap-4 items-center">
                    <div class="w-full">
                      <p class="mb-2">Upload your images</p>
                      <input type="file" @change="handleFileUpload"  
                      class="h-[44px] w-full rounded-lg pl-3 mt-3 text-white" />
                    </div>
                    <div class="w-full -mt-3">
                      <p class="mb-2">Enter name</p>
                      <input type="text" v-model="newProduct.name" placeholder="Enter name"
                      class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Enter code</p>
                      <input type="text" v-model="newProduct.code"  placeholder="Enter code"
                        class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Select type</p>
                      <select v-model="newProduct.type_id" class="h-[44px] w-full rounded-lg bg-[#252836] pl-3">
                        <option value="" disabled >Select Product Type</option>
                        <option v-for="(type) in products_type" :key="type.id" :value="type.id">{{ type.name }}</option>
                      </select>
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Enter price</p>
                        <input type="text" v-model="newProduct.unit_price" placeholder="Enter price"
                        class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Enter Description</p>
                        <input type="text" v-model="newProduct.des" placeholder="Enter des"
                        class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <button type="button" class="h-[44px] w-[240px] bg-[#252836] rounded-full hover:bg-slate-600  "
                      @click="createProduct"
                      >Create
                    </button>
                  </SheetDescription>
              </SheetHeader>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </div>

    <div class=" px-3 pt-5">
      
      <div v-if="isLoading" class="flex justify-center flex-col items-center">
        <p class="text-xl mb-4">Please wait! request Data</p>
        <span class="flex justify-center">
            <Loader class="animate-spin w-5 h-5 text-white" />
        </span>
      </div>

      <div v-if="data.length === 0 && !isLoading" class="flex flex-col justify-center items-center mb-4">
        <span class="text-2xl">No Data</span>
      </div>

      <div class="">
        <table :items="dataSource" class="w-full flex-col">
          <thead class=" ">
            <tr class="w-full">
              <th v-for="column in displayedColumns" :key="column" class="text-[#EA7C69] text-lg pb-3">{{ column }}</th>
            </tr>
          </thead>
          <tbody class=" w-full flex-col text-white">

            <tr v-for="(row, index) in dataSource" :key="row.id" class="flex-col items-center justify-center text-center border-b">
              <td class=" text-center py-4 ">{{ index + 1 }}</td>

              <td class=" text-center ">{{ row.code }}</td>
              <td class="flex justify-center items-center mt-1 ">
                <img :src="row.image ? fileUrl + row?.image : null" alt="image" style="width: 45px; height: 45px;">
              </td>
              <td>{{ row.name }}</td>
              <td>{{ row.type.name }}</td>
              <!-- <td>{{ row.unit_price | currency('៛') }}</td><td>{{ row.created_at | formatDate }}</td> -->
              <td>{{ row.unit_price}} $</td>
              <td class="pl-6">{{ row.created_at }}</td>
                  
              <td>
                <DropdownMenu>
                  <DropdownMenuTrigger>
                    <button>
                      <EllipsisVertical class="icon-size-6"></EllipsisVertical>
                    </button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent class="bg-[#252836] mr-14">
                    <DropdownMenuSeparator />
                      <DropdownMenuItem class="hover:bg-gray-500" @click="openViewDialog(row)">
                        <div  class="flex items-center cursor-pointer p-2">
                          <Eye class="icon-size-5 text-blue-500 mr-2" />
                          <span class="text-lg text-blue-500">View Product</span>
                        </div>
                      </DropdownMenuItem>

                      <DropdownMenuItem class="hover:bg-gray-500" @click=" openEditDialog(row)" >
                        <div class="flex items-center cursor-pointer p-2">
                          <SquarePen class="icon-size-5 mr-2 text-gray-500" />
                          <span class="text-lg text-gray-500">Edit Product</span>
                        </div>
                      </DropdownMenuItem>

                      <DropdownMenuItem class="hover:bg-gray-500" @click="openDeleteDialog(row)">
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
        </table>

          <!-- next page -->
          <div class="mt-4 mb-8">
            <nav class="block">
              <ul class="pagination">
                <li class="page-item">
                  <button class="page-link" @click="previousPage">Previous</button>
                </li>
                <li class="page-item">
                  <button class="page-link" @click="nextPage">NextPage</button>
                </li>
              </ul>
            </nav>
          </div>

          <!-- <div class="container-listing-products-paginator min-h-11 max-h-11" :class="{ 'flex-custom': data.length > 0 && !isLoading }">
            <div :length="total" :page.sync="page" :items-per-page="limit" @input="onPageChanged"></div>
          </div> -->
        </div> 
      </div> 
    <Sheet v-model:open="openDialog">
      <SheetContent class="bg-[#1F1D2B]">
        <SheetHeader >
        <SheetTitle class="text-white text-center">Edit Product</SheetTitle>
          <SheetDescription class="text-white flex flex-col gap-4 items-center">
            <div class="w-full">
              <p class="mb-2">Upload your images</p>
              <input type="file" @change="handleFileEdit"  
              class="h-[44px] w-full rounded-lg pl-3 mt-3 text-white" />
            </div>
            <div class="w-full">
              <p class="mb-2">Enter name</p>
              <input type="text" v-model="selectedProduct.name" placeholder="Enter name"
              class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
            </div>
            <div class="w-full">
              <p class="mb-2">Enter code</p>
              <input type="text" v-model="selectedProduct.code"  placeholder="Enter code"
              class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
            </div>
            <div class="w-full">
              <p class="mb-2">Select type</p>
              <select v-model="selectedProduct.type_id" class="h-[44px] w-full rounded-lg bg-[#252836] pl-3">
                <option value="" disabled >Select Product Type</option>
                <option v-for="(type) in products_type" :key="type.id" :value="type.id">{{ type.name }}</option>
              </select>
            </div>
            <div class="w-full">
              <p class="mb-2">Enter price</p>
              <input type="text" v-model="selectedProduct.unit_price" placeholder="Enter price"
              class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
            </div>
            <div class="w-full">
              <p class="mb-2">Enter Description</p>
              <input type="text" v-model="selectedProduct.des" placeholder="Enter des"
              class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
            </div>
            <button type="button" class="h-[44px] w-[240px] bg-[#252836] rounded-full hover:bg-slate-600  "
              @click="updateProduct"
              >Save
            </button>
          </SheetDescription>
        </SheetHeader>
      </SheetContent>
    </Sheet> 
    
    <!-- delete product -->
    <AlertDialog v-model:open="deleteDialog" class="bg-zinc-500">
      <AlertDialogContent class="bg-[#1F1D2B] text-white">
        <AlertDialogHeader>
          <AlertDialogTitle>Confirm Deletion</AlertDialogTitle>
          <AlertDialogDescription>Are you sure you want to delete this product?</AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel class=" hover:bg-slate-500">Cancel</AlertDialogCancel>
          <AlertDialogAction @click=" deletedProduct" class=" bg-green-600 border hover:bg-red-700">Delete</AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>

    <!-- view product -->
    <AlertDialog v-model:open="viewDialog" class="bg-zinc-500">
      <AlertDialogContent class="bg-[#1F1D2B] text-white">
        <AlertDialogHeader >
          <AlertDialogTitle class=" text-center">Details Information</AlertDialogTitle>
        </AlertDialogHeader>
        <AlertDialogDescription>
          <div class="flex">
             <div class=" w-[40%] items-center flex justify-center">
              <img :src="selectedProduct.image ? fileUrl + selectedProduct?.image : null" alt="image" style="width: 250px; height: 250px;">
             </div>
             <div class=" w-[60%] ">
                <div class="px-3 py-3   items-center text-center text-xl">{{ selectedProduct.name }}</div>
                <div class="px-3 py-3  items-center text-center text-xl">{{ selectedProduct.code }}</div>
                <div class="px-3 py-3  items-center text-center text-xl">{{ selectedProduct.type.name }}</div>
                <div class="px-3 py-3  items-center text-center text-xl">{{ selectedProduct.unit_price }}</div>
                <div class="px-3 py-3  items-center text-center text-xl">{{ selectedProduct.des }}</div>
             </div>
          </div>
        </AlertDialogDescription>
        <AlertDialogFooter>
          <AlertDialogCancel class=" hover:bg-slate-500">Cancel</AlertDialogCancel>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </div>
</template>

<script>

  import { Search, HomeIcon, ChevronRight, Plus, MoreVertical, Eye, Key,LockKeyhole, Trash2, EllipsisVertical, SquarePen, Loader } from "lucide-vue-next";
  import { Input }                from "@/components/ui/input";
  import { ref, onMounted }       from 'vue';
  import { useRouter, useRoute }  from 'vue-router';
  import { format }               from 'date-fns';
  import axiosClient              from "@/service/GlobalApi";
  import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
  } from '@/components/ui/dropdown-menu';
  import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
  } from '@/components/ui/sheet';

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
    name: 'productItem',
    components:{
      Search, HomeIcon, ChevronRight, Plus, MoreVertical, Eye, Key, Trash2, EllipsisVertical,LockKeyhole,SquarePen,Loader,
        Input,
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuLabel,
        DropdownMenuSeparator,
        DropdownMenuTrigger,
        Sheet,
        SheetContent,
        SheetDescription,
        SheetHeader,
        SheetTitle,
        SheetTrigger,
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

    setup(){
      const fileUrl     = import.meta.env.VITE_APP_FILE_BASE_URL;
      const isLoading   = ref(true);
      const data        = ref([]);
      const total       = ref(0);
      const limit       = ref(10);
      const page        = ref(1);
      const key         = ref('');
      const products_type     = ref([]);
      const products_type_id  = ref(0);
      const dataSource        = ref([]);
      const newProduct        = ref({
        name: '',
        code: '',
        type_id: '',
        image: null,
        unit_price: '',
        des: ''
      });
      const router            = useRouter();
      const route             = useRoute();

      // TODO: select products by id
      const selectedProduct = ref(null);
      //const selectedProduct = ref(null);

      const openDialog = ref(false);
      const deleteDialog = ref(false);
      const viewDialog = ref(false);

      const displayedColumns = ref(['N.O', 'Code', 'Image', 'Name', 'Type', 'Unit Price', 'Created At']);

      onMounted( async () => {
        listing(limit.value, page.value);
        productsTypes();  // Wait for productsTypes to complete

      });

      const productsTypes = async() =>{
        try{
          const res = await axiosClient.get('/admin/products/types',{
            headers: {
              'Content-Type':'multipart/form-data'
            }
          });

          products_type.value = res.data;
          // console.log(products_type.value);
          // console.log(res);


        } catch(err){
          console.error('Something went wrong:', err)
        }
      }

      const handleFileUpload = (event)=>{

        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = () => {
            newProduct.value.image = reader.result;
          };
          reader.readAsDataURL(file);

          //console.log(reader.result);
        }
      }

      // const onFileChange = (event)=>{
      //   const file = event.target.files[0];
      //   if(file){
      //     selectedProduct.value = file;
      //     handleFileEdit(file);
      //   }
      // }

      const handleFileEdit = (event)=>{

        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = () => {
            selectedProduct.value.image = reader.result;
            console.log(reader.result); // Log after the result is ready
          };
          reader.readAsDataURL(file);
        }

      }

      const createProduct = async() =>{

          const formData = new FormData();
          formData.append('name', newProduct.value.name);
          formData.append('code', newProduct.value.code);
          formData.append('type_id', newProduct.value.type_id);
          formData.append('des', newProduct.value.des);
          formData.append('image', newProduct.value.image);
          formData.append('unit_price', newProduct.value.unit_price);

          isLoading.value = true;

        try{

          const res = await axiosClient.post('/admin/products', formData, {
            headers: {
              'Content-Type':'multipart/form-data'
            }
          });
          isLoading.value = false;

          // Update the data immediately without calling listing
          data.value.push(res.data);
          // dataSource.value = [...data.value];

          // Optionally, update total and other pagination values if necessary
          // total.value += 1;

        await listing(limit.value, page.value);
          //alert("product created successfully.");
         // clearNewProductForm();
          
        }catch(err){
          console.error('Something went wrong:', err)
        }

      }

      const clearNewProductForm = ()=>{
        newProduct.value = {
          name: '',
          code: '',
          type_id: '',
          image: null,
          unit_price: '',
          des: ''
        };
      }

      // TODO: function for listing all the products
      const listing = async (_limit = 10, _page = 1)=>{

        const params = {
          limit: _limit,
          page  : _page
        };

        if(key.value)
        {
          params.key = key.value;
        }
        if(products_type_id.value)
        {
          params.type = products_type_id.value;
        }

        if (page.value != 0) {
          params.page = page.value;
        }

        isLoading.value = true;

        // TODO: this function is for connecting to api
        try{

          const res = await axiosClient.get('/admin/products', {params});
          isLoading.value = false;

          data.value = res.data.data;

          dataSource.value = data.value;
          total.value = res.data.total;

          page.value = res.data.correct_page;
          // limit.value = res.data.per_page;

        } catch(err){
          isLoading.value = false;
          console.error('Something went wrong:', err)
        }
      };

      const updateProduct = async () => {

        const productId = selectedProduct.value.id;

        if (!productId) {
          throw new Error('Product not found');
        }

        const formData = new FormData();

        //selectedProduct.value[key] instanceof File before appending the image property to the FormData, you ensure that the image is only sent if it exists and is a File. This should resolve the issue with the image not being saved to the database when updating a product.
        for (const key in selectedProduct.value) {
          if (key === 'image' && selectedProduct.value[key].startsWith('data:image')) {
            formData.append(key, selectedProduct.value[key]);
          } else {
            formData.append(key, selectedProduct.value[key]);
          }
        }

        try{
          
          const res = await axiosClient.post(`/admin/products/${productId}`, formData, {
            headers: {
              'Content-Type':'multipart/form-data'
            }
          });

          // Update the data in the local array
          const updateProductIndex = data.value.findIndex(
            (item) => item.id === productId
          );
         
          if(updateProductIndex !== -1){
            data.value[updateProductIndex] = res.data;
            dataSource.value = [...data.value];
          }

          openDialog.value = false;
          await listing(limit.value, page.value);
          //alert("product updated successfully.");

        }catch(err){
          console.error('Something went wrong:', err)
        }
      }

      // TODO: this function for changes to the new page
      const onPageChanged = (event)=>{
        limit.value = event.itermPerPage;
        page.value = event.page;
        listing(limit.value, page.value);
      };

      // TODO: this function for search product by name or code
      const handleSearchInput = (event) => {
        key.value = event.target.value;
        listing(limit.value, 1); // Reset to first page on new search
      };

      // TODO: this function for back to previous
      const previousPage = ()=>{
        if(page.value > 1){
          page.value -= 1;
          listing(limit.value, page.value);
        }
      }

      // TODO: this function for next page
      const nextPage = ()=>{
        if(page.value < Math.ceil(total.value / limit.value)){
          page.value += 1;
          listing(limit.value, page.value);
          console.log(page.value); // Log page.value after updating
        }
        // console.log(page.value);
      }

      const openEditDialog = (product) => {
        selectedProduct.value = product;
        openDialog.value = true;

        //console.log(product); //
      }

      const openDeleteDialog = (product) => {
        selectedProduct.value = product;
        deleteDialog.value = true;
      }

      const openViewDialog = (product) => {
        selectedProduct.value = product;
        viewDialog.value = true;
      }

      const viewProduct = async () =>{
        if (!selectedProduct.value || !selectedProduct.value.id) {
          console.error('Product not found');
          return;
        }
        try {
          const res = await axiosClient.get(`/admin/products/${selectedProduct.value.id}`);
          viewDialog.value = false;
          selectedProduct.value = res.data;
        } catch (err) {
          console.error('Something went wrong:', err);
        }
      }
      
      const deletedProduct = async () => {

        if (!selectedProduct.value || !selectedProduct.value.id) {
          console.error('Product not found');
          return;
        }
        try {
          await axiosClient.delete(`/admin/products/${selectedProduct.value.id}`);
          data.value = data.value.filter(product => product.id !== selectedProduct.value.id);
          deleteDialog.value = false;
          await listing(limit.value, page.value);
        } catch (err) {
          console.error('Something went wrong:', err);
        }
      };

      return {
        fileUrl,isLoading,data,total,limit,
        page,key,products_type,products_type_id,dataSource,
        listing,onPageChanged,displayedColumns,handleSearchInput,
        productsTypes,handleFileUpload,createProduct,newProduct,
        clearNewProductForm,previousPage,nextPage,openDialog,openEditDialog,
        updateProduct,selectedProduct, deletedProduct,openDeleteDialog,
        handleFileEdit,deleteDialog,viewDialog, viewProduct, openViewDialog
      };
    },
    
    filters: {
      currency(value, symbol) {
        if (!value) return '';
        return `${symbol}${parseFloat(value).toFixed(2)}`;
      },
      formatDate(value) {
        if (!value) return '';
        return format(new Date(value), 'yyyy-MM-dd');
      },
    },
  }
</script>

<style scoped>

  table {
      width: 100%;
  }


  .flex-custom {
      display: flex ;
      justify-content: center;
  }

</style>