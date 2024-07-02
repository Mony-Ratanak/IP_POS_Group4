<template>
    <header class="container-listing-user-header flex gap-2 justify-between items-center py-2 px-5 mt-5 ml-4 mr-4 rounded-2xl drop-shadow-lg shadow-inner bg-[#252836]">
        <div class="flex flex-1 items-center font-medium">
            <div class="flex items-center whitespace-nowrap">
                <HomeIcon class="text-red-500" />
            </div>
            <div class="flex items-center ml-1 whitespace-nowrap">
                <ChevronRight class="icon-size-4.5 text-red-500" />
                <span class="ml-1 text-white text-xl">Order Details</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="sm:flex flex justify-start items-center">
                <Input type="text" placeholder="Search..." v-model="key" @input="handleSearchInput" class="pl-12 h-[44px] w-[230px] rounded-md bg-[#252836] text-xl border-[#393C49]" />
                <span class="absolute px-4">
                    <Search class="text-gray-400" />
                </span>
            </div>
            <div class="custom-button-flat" @click="addToOrder">
                <button class="w-11 h-11 rounded-md flex justify-center items-center bg-[#ef1515] file:">
                    <Plus class="text-2xl font-bold text-white" />
                </button>
            </div>
        </div>
    </header>

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
                    <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Receipt Number</th>
                    <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Cahier</th>
                    <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Total Price</th>
                    <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Customer_id</th>
                    <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Customer_name</th>
                    <th class="px-5 py-3 text-left text-sm text-gray-400 uppercase border-b border-gray-200">Date</th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="(item, index) in data" :key="item.id" class=" text-white">
                    <td class="px-5 py-3 border-b border-gray-200">{{ index + 1 }}</td>
                    <td class="px-5 py-3 border-b border-gray-200"># {{ item.receipt_number }}</td>
                    <td class="px-5 py-3 border-b border-gray-200">{{ item.cashier?.name }}</td>
                    <td class="px-5 py-3 border-b border-gray-200">{{ item.total_price }} $</td>
                    <td class="px-5 py-3 border-b border-gray-200">{{ item.customer_id }}</td>
                    <td class="px-5 py-3 border-b border-gray-200">{{ item.customer_name }}</td>

                    <td class="px-5 py-3 border-b border-gray-200 flex justify-between items-center">
                        {{ item.ordered_at}}

                        <DropdownMenu>
                            <DropdownMenuTrigger>
                                <button>
                                    <MoreVertical class="icon-size-6 text-gray-400 hover:text-gray-500  cursor-pointer"></MoreVertical>
                                </button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent class="bg-[#252836] mr-16">
                                <DropdownMenuSeparator />

                                <DropdownMenuItem class="hover:bg-gray-500" @click="downloadInvoice(item.receipt_number)" >
                                    <div class="flex items-center cursor-pointer p-2 text-white">
                                        <Printer class="icon-size-5 mr-2 " />
                                        <span class="text-lg ">Print</span>
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

    <!-- delete user -->
    <AlertDialog v-model:open="isDeleteDialogOpen" >
        <AlertDialogContent class=" bg-[#1F1D2B]  text-white ">
          <AlertDialogHeader>
            <AlertDialogTitle>Confirm Deletion</AlertDialogTitle>
            <AlertDialogDescription>Are you sure you want to delete this user?</AlertDialogDescription>
          </AlertDialogHeader>

          <AlertDialogFooter>
            <AlertDialogCancel @click="closeDeleteDialog" class="hover:bg-slate-600">Cancel</AlertDialogCancel>
            <AlertDialogAction @click="deleteOrder(selectedElement?.id)" class="hover:bg-slate-600 border bg-red-300">Delete</AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>

    <div class="flex items-center justify-center gap-4 mt-4">
        <button @click="gotoPreviousPage" :disabled="page === 1" class="w-11 h-11 rounded-md flex justify-center items-center bg-[#ef1515]" :class="{ 'opacity-50 cursor-not-allowed': page === 1 }">
            <ChevronLeft class="text-2xl font-bold text-white" />
        </button>
        <button @click="gotoNextPage" :disabled="page * limit >= total" class="w-11 h-11 rounded-md flex justify-center items-center bg-[#ef1515]" :class="{ 'opacity-50 cursor-not-allowed': page * limit >= total }">
            <ChevronRight class="text-2xl font-bold text-white" />
        </button>
    </div>

</template>

<script>

    import { useRouter } from 'vue-router';
    import { Input } from "@/components/ui/input";
    import { Search, HomeIcon, ChevronRight, Plus, Loader,ChevronLeft, MoreVertical, Trash2,Printer } from "lucide-vue-next";
    import axiosClient from "@/service/GlobalApi";
    import { ref, onMounted, watch } from 'vue';
    import FileSaver from 'file-saver';


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
        name: "OrderDetails",

        components: {
            Search,
            Trash2,
            HomeIcon,
            Printer,
            ChevronRight,
            Plus,
            Loader,
            Input,
            ChevronLeft,
            MoreVertical,
            DropdownMenu,
            DropdownMenuContent,
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

        setup() 
        {
            const fileUrl = import.meta.env.VITE_APP_FILE_BASE_URL;
            const isLoading = ref(true);
            const data = ref([]);
            const total = ref(0);
            const limit = ref(10);
            const page = ref(1);
            const key = ref('');
            const isDeleteDialogOpen = ref(false);
            const selectedElement = ref(null);
            const router = useRouter();

            
            const closeDeleteDialog = () => {
                isDeleteDialogOpen.value = false;
            };

            const openDeleteDialog = (item) => {
                selectedElement.value = item;
                isDeleteDialogOpen.value = true;
            };


            const listing = async () => {
                isLoading.value = true;
                const res = await axiosClient.get('/admin/sales', {
                    params: {
                        page: page.value,
                        limit: limit.value,
                        key: key.value,
                    }
                });
                data.value = res.data.data;
                total.value = res.data.total;
                isLoading.value = false;
            };

            const deleteOrder = async () => {
                try {
                    const res = await axiosClient.delete(`/admin/sales/${selectedElement.value.id}`);

                    if (res.status === 200) {
                        data.value = data.value.filter(item => item.id !== selectedElement.value.id);
                        //alert('Order deleted successfully');
                    } else {
                        alert('Failed to delete order');
                    }
                } catch (error) {
                    console.error('Error deleting order:', error);
                    alert('Failed to delete order');
                }

                closeDeleteDialog();
            };

            const addToOrder = async () => {
                router.push('/order')
            }

            const handleSearchInput = () => {
                page.value = 1; // Reset page to 1 when searching
                listing();
            };

            const gotoNextPage = () => {
                if (page.value * limit.value < total.value && data.value.length > 0) {
                    page.value++;
                    listing();
                }
            };

            const gotoPreviousPage = () => {
                if (page.value > 1 && data.value.length > 0) {
                    page.value--;
                    listing();
                }
            };

            const downloadInvoice = async (receiptNumber) =>{
                try {
                    if (!receiptNumber) {
                        console.error('Receipt number is undefined.');
                        return;
                }
                    const response = await axiosClient.get(`/admin/sales/order-invoice/${receiptNumber}`);
                    const blob = b64toBlob(response.data.file_base64, 'application/pdf');
                    FileSaver.saveAs(blob, 'Invoice-' + receiptNumber + '.pdf');
                } catch (error) {
                    console.error('Error downloading invoice:', error);
                }
            };

            const b64toBlob = (b64Data, contentType = '', sliceSize = 512) => {
                const byteCharacters = atob(b64Data);
                const byteArrays = [];

                for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                    const slice = byteCharacters.slice(offset, offset + sliceSize);
                    const byteNumbers = new Array(slice.length);
                    for (let i = 0; i < slice.length; i++) {
                        byteNumbers[i] = slice.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    byteArrays.push(byteArray);
                }

                const blob = new Blob(byteArrays, { type: contentType });
                return blob;
            };

            onMounted(() => {
                listing();
            });

            return {
                fileUrl,
                isLoading,
                data,
                total,
                limit,
                page,
                key,
                listing,
                handleSearchInput,
                gotoNextPage,
                gotoPreviousPage,
                isDeleteDialogOpen,
                selectedElement,
                closeDeleteDialog,
                deleteOrder,
                openDeleteDialog,
                router,
                addToOrder,
                downloadInvoice,
                b64toBlob,
            };
        }
    };
</script>

<style scoped>
/* Add your custom styles here */
</style>
