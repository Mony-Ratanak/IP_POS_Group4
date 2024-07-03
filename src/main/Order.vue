<template>
    <div class="flex w-full bg-[#252836]">
        <div class="c h-[910px] w-[70%]  pl-[24px] pt-[24px]">

            <div class="flex w-full justify-between items-center h-[65px]">
                <div class="flex flex-col">
                    <h1 class="h2 font-bold text-[#e36751] text-3xl mb-2">
                        <span class=" text-white">Hello  </span>{{user?.name}}
                    </h1>
                    <p class="text-[16px] text-white">{{ currentDate }}</p>
                </div>

                <div class="relative max-w-sm items-center">
                    <Input v-model="searchQuery" type="text" placeholder="Search..." class="pl-12 h-[56px] rounded-xl bg-[#252836] text-xl border-[#393C49]" />
                    <span class="absolute start-0 inset-y-0 flex items-center justify-center px-4 ">
                        <Search class=" text-white"></Search>
                    </span>
                </div>
            </div>

            <Tabs :default-value="1">
                <TabsList>
                    <TabsTrigger v-for="dish in products" :key="dish?.id" :value="dish?.id">
                        <div
                        :class="{ 'text-[#e95338]  border-b-2 pb-2 border-y-[#e36751] w-[80px]' : dish.id === selectedDishId }"
                        class="flex  text-white font-normal text-[16px] items-center justify-center mt-7 cursor-pointer"
                        @click="selectDish(dish?.id)"
                        >
                        <p>{{ dish.name }}</p>
                        <!-- <hr class="mt-2 line" /> -->
                        </div>
                    </TabsTrigger>
                </TabsList>

                <div class="flex justify-between w-full items-center mt-6">
                    <h1 class="text-white text-[24px] font-medium">Choose Dishes</h1>
                    <div>
                        <DropdownMenu>
                        <DropdownMenuTrigger class="flex  p-2 gap-2 border w-[127px] rounded-md text-white items-center font-medium justify-center bg-[#1F1D2B] border-[#393C49]">
                            <ChevronDown />
                            Open
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuLabel>My Account</DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>Profile</DropdownMenuItem>
                            <DropdownMenuItem>Billing</DropdownMenuItem>
                            <DropdownMenuItem>Team</DropdownMenuItem>
                            <DropdownMenuItem>Subscription</DropdownMenuItem>
                        </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>

                <TabsContent v-for="dish in products" :key="dish?.id" :value="dish?.id">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 w-full mt-5 cursor-pointer">
                        <div
                            v-for="product in filteredProducts(dish)"
                            :key="product.id"
                            class="flex justify-center mt-5"
                        >
                            <div @click="addToOrder(product)"
                                class="h-[250px] w-[200px] bg-[#1F1D2B] text-white rounded-xl flex flex-col justify-center items-center gap-1 relative"
                            >
                                <div class="">
                                    <img
                                    :src="product.image ? fileUrl + product.image : null"
                                    alt="image"
                                    class="h-[140px] w-[140px] rounded-full mt-5 relative"
                                    />
                                </div>
                                <div class="text-center mt-2">
                                    <p class="w-full font-semibold">{{ product.name }}</p>
                                    <p class="font-normal">{{ product.unit_price }}$</p>
                                    <p class="text-[#3B5162]">{{ product.des }}</p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </TabsContent>

            </Tabs>         
        </div>

        <div class="mt-[24px] ml-4 bg-[#1F1D2B] w-[26%] rounded-lg">
            <h1 class="mt-5 ml-4 text-2xl text-white font-bold">
                Order #{{ orderNumber }}
            </h1>

            <div class="flex ml-4 mt-4 mr-4 text-white font-medium gap-2 items-center">
                <label for="customerId">Customer ID: </label>
                <input class=" w-20 p-1 rounded-md bg-[#1F1D2B] text-white border-[#393C49]" type="number" id="customerId" name="customerId">
            </div>

            <div class="flex ">
                <div class="flex h-[40px] w-[100px] bg-[#EA7C69] items-center justify-center text-xl font-medium text-white rounded-lg mt-5 ml-4">
                    Dine In
                </div>
                <div class="flex h-[40px] w-[100px] border border-[#EA7C69] items-center justify-center text-xl font-medium text-[#EA7C69] rounded-lg mt-5 ml-4">
                    Go To
                </div>
                <div class="flex h-[40px] w-[100px] border border-[#EA7C69] items-center justify-center text-xl font-medium text-[#EA7C69] rounded-lg mt-5 ml-4">
                    Delivery
                </div>
                
            </div>      

            <div class="flex justify-between ml-4 mt-5 mr-4 text-white font-medium">
                <p>Item</p>

                <div class="flex">
                    <p class=" mr-10">Qty</p>
                    <p>Price</p>
                </div>
            </div>

            <hr class="ml-4 mr-4 mt-5 bg-[#393C49]">
            
            <div class="ml-4 mt-5 h-[300px] overflow-y-auto">
                <div v-for="(product, index) in order" :key="index" class="flex justify-between text-white flex-col mb-2">
                    <div class="flex justify-between mb-2">
                        <div class="flex items-center">
                        <img :src="product.image ? fileUrl + product.image : null" alt="" class="h-[40px] w-[40px]">
                        
                        <div class="flex flex-col ml-3">
                            <p>{{ product.name }}</p>
                            <p>{{ product.unit_price }}</p>
                        </div>
                        </div>

                        <div class="flex items-center">
                            <div class="h-[48px] w-[48px] flex justify-center items-center rounded-lg mr-6 bg-[#2D303E]">
                                {{ product.quantity }}
                            </div>
                            <p class="mr-3 flex items-center justify-center w-[50px]">{{ (product.unit_price * product.quantity).toFixed(2) }}</p>
                            <!-- <button >X</button> -->
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="h-[48px] w-[330px] flex justify-start pl-3 items-center rounded-lg bg-[#2D303E]">
                            Just order now
                        </div>
                        <div class="h-[48px] w-[48px] flex justify-center items-center rounded-lg mr-3 bg-[#2D303E]">
                            <Trash2 @click="removeFromOrder(index)" class=" text-red-500 cursor-pointer"></Trash2>
                        </div>                      
                    </div>             
                </div>
            </div>

            <div class="ml-4 mt-5">

                <div class="flex items-center mt-2 justify-between">
                    <label for="discount" class="text-white mr-2">Discount: </label>
                    <input v-model.number="discount" type="number" id="discount" class="w-20 p-1 rounded-md bg-[#1F1D2B] text-white border-[#393C49]" />
                </div>
                <p class="text-white flex justify-between mr-14">
                    <span>Total Price: </span>
                    {{ totalPrice.toFixed(2) }}
                </p>
                <p class="text-white mt-2 flex justify-between mr-14">
                    <span>Total with Discount:</span>
                    {{ totalWithDiscount.toFixed(2) }}
                </p>
            </div>

            <div class="flex  justify-center items-center mt-2">
                <button @click="submitOrder" class="bg-[#EA7C69] text-white px-4 py-2 mt-4 ml-4 rounded-lg hover:bg-slate-400 w-[60%] "
                    >   
                    Submit Order
                </button> 
            </div>
                             
        </div>

        <!-- Dialog for Order Details -->
        <div v-if="isDialogVisible" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-[#1F1D2B] text-white p-6 rounded-lg shadow-lg w-[550px] ">
            <h2 class="text-2xl font-bold mb-4 text-center text-red-500">Order Details</h2>
            <div class="mb-4">
            <p><strong >Order Number:</strong> #{{ orderNumber }}</p>
            <p class="mt-1"><strong >Date:</strong> {{ currentDate }}</p>
            <p class="mt-1"><strong>Total Price:</strong> {{ totalPrice.toFixed(2) }}</p>
            <p class="mt-1"><strong>Total with Discount:</strong> {{ totalWithDiscount.toFixed(2) }}</p>

            <div class="mt-4">
                <ul>
                    <li v-for="(product, index) in order" :key="index">
                        {{ index + 1 }} : {{ product.name }} : {{ product.quantity }} x {{ product.unit_price }} = {{ (product.unit_price * product.quantity).toFixed(2) }}$
                    </li>
                </ul>
            </div>
            </div>
            <div class="flex justify-end">
                <button @click="closeDialog" class="bg-gray-500 text-white px-6 py-2 rounded-lg mr-2">
                    Close
                </button>
                <InvoiceDownloader v-if="orderPlaced" :receiptNumber="orderNumber" />
       
            </div>
        </div>
        </div>
    </div>
    
</template>
  
  
<script>
import axiosClient from "@/service/GlobalApi";
import InvoiceDownloader from "./InvoiceDownloader.vue";
import { ref, onMounted, computed } from "vue";
import { Input } from "@/components/ui/input";
import { Camera, ChevronDown, Component, Search,Trash,Trash2 } from "lucide-vue-next";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

export default {
    name: "Order Pages",
    components: {
        Input,
        Camera,
        ChevronDown,
        Component,
        Search,
        Trash2,
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuLabel,
        DropdownMenuSeparator,
        DropdownMenuTrigger,
        Tabs, TabsContent, TabsList, TabsTrigger,
        InvoiceDownloader
    },

    setup() {
        const products          = ref([]);
        const selectedDishId    = ref(null); // Define selectedDishId
        const user              = ref(null);
        const order             = ref([]);
        const discount          = ref(0);
        const isDialogVisible   = ref(false);
        const orderNumber       = ref(null); // Add this line
        const orderPlaced       = ref(false); // Add this line
        const currentDate       = ref(''); // Add this line
        const data              = ref([]); // Add this line
        const searchQuery       = ref('');

        const fetchData = async () => {
            try {
                const response = await axiosClient.get("/admin/pos/products");
                //console.log("Products fetched from server:", response.data);
                products.value = response.data;

                // Set selectedDishId to the ID of the first product type
                if (products.value.length > 0 && products.value[0].products.length > 0) {
                selectedDishId.value = products.value[0].id;

                }
            } catch (error) {
                console.error("There was an error!", error);
            }
        };

        onMounted(()=>
        {
            const userData = localStorage.getItem('user');
            if (userData) {
                user.value = JSON.parse(userData);
            }
            //console.log(user.value);
            fetchData();

            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const year = today.getFullYear();
            currentDate.value = `${day}-${month}-${year}`;


        });

        const addToOrder = (product) => {

            // console.log("Attempting to add product to order:", product);

            // Check if the product quantity is greater than 0
            if (product.quantity  > 0) {

                const existingProduct = order.value.find(item => item.id === product.id);

                if (existingProduct) {
                    // Increase the quantity of the existing product
                    existingProduct.quantity += 1;

                } else {
                    // Create a new object for the product with quantity set to 1
                    order.value.push({ ...product, quantity: 1 });
                }
                // Reduce the product quantity in the products list
                product.quantity  -= 1;
                // console.log("Product after adding to order:", product);

            } else {
                // You can add a message here to notify the user that the product is out of stock
                alert("Product is out of stock");
            }
        };

        const selectDish = (dishId) => {
            selectedDishId.value = dishId; // Set selectedDishId when a dish is selected
        };

        const totalPrice = computed(() => {
            return order.value.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
        });

        const totalWithDiscount = computed(() => {
            return totalPrice.value - discount.value;
        });

        const removeFromOrder = (index) => {

            // Restore the product quantity in the products list when removed from the order
            const product = order.value[index];
            const originalProduct = products.value.find(dish => dish.products.some(p => p.id === product.id)).products.find(p => p.id === product.id);
            originalProduct.available += product.quantity;
            order.value.splice(index, 1);
        };

        const submitOrder = async () => {

            const invalidProducts = order.value.filter(item => item.quantity > item.available);

            if (invalidProducts.length > 0) {
                // You can add a message here to notify the user that some products are out of stock
                alert("Some products are out of stock");
                return;
            }
            // TODO: reduce is used to transform the order.value array into an object where each key is the product ID and the value is the quantity.
            const orderDetails = order.value.reduce((acc, item) => {
                acc[item.id] = item.quantity;

                return acc;
            }, {});

            // console.log("Order details transformed for payload:", orderDetails); // Debugging

            // This additional array is for the receipt printing
            const detailedOrder = order.value.map(item => ({
                product_id: item.id,
                product_name: item.name,
                unit_price: item.unit_price,
                qty: item.quantity,
                total_price: item.unit_price * item.quantity,
            }));
            //console.log("Order details", detailedOrder); //
            const customerId = document.getElementById('customerId').value;

            const payload = {
                customer_id: customerId,
                cashier_id: user.value.id,
                total_price: totalWithDiscount.value,
                cart: JSON.stringify(orderDetails), // Include the cart field
                details: detailedOrder, // This is for printing the receipt
            };

            //console.log("Payload:", payload); // Debugging
            // console.log(cart);

            try {
                //console.log(payload);
                const response = await axiosClient.post("/admin/pos/order", payload);
                //console.log(response.data.message);
                orderNumber.value = response.data.order.receipt_number;
                orderPlaced.value = true;
                isDialogVisible.value = true;
            } catch (error) {
                console.error("There was an error submitting the order!", error);
            }
        };
        
        const closeDialog = () =>{
            isDialogVisible.value = false;
            // orderPlaced.value = true;
            // receiptNumber.value = response.data.receipt_number;
        };

        const filteredProducts = (dish) => {
            if (!searchQuery.value) return dish.products;
            return dish.products.filter(product =>
                product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        };

     
        return {
            products,
            user,
            selectedDishId, // Expose selectedDishId to the template
            selectDish, // Expose selectDish method to the template
            addToOrder,
            order,
            removeFromOrder,
            totalPrice,
            discount,
            totalWithDiscount,
            submitOrder,
            fileUrl : import.meta.env.VITE_APP_FILE_BASE_URL,
            orderPlaced: false,
            receiptNumber: '',
            isDialogVisible,
            closeDialog,orderNumber, currentDate, orderPlaced, data, searchQuery,
            filteredProducts,

        };
    },
};
</script>

<style scoped>
    
    .b{
        position: relative;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    
</style>