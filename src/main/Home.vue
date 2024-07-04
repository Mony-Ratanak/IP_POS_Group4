<template>
  <div class="dashboard bg-gray-800 text-white font-sans flex mt-2">

    <section>
      <div class="stats flex p-4 w-[1100px] justify-between ml-[15px]">

        <div class=" bg-gray-700 rounded-lg p-4  w-[330px] h-[150px] flex justify-start  items-center">
          <div class="icon bg-blue-500 rounded-full w-12 h-12 ml-8  flex items-center justify-center text-white ">
            <CircleDollarSign class=" text-white"></CircleDollarSign>
          </div>
          <div class=" text-center ml-6 ">
            <div class="text-xl text-center ">
              Total Revenue
            </div>
            <div class="text-2xl font-bold text-blue-500">
              ${{ totalRevenue.toFixed(2) }}
            </div>
          </div>        
        </div>
  
        <!--  -->
        <div class=" bg-gray-700  rounded-lg p-4  w-[330px] flex justify-start  items-center">
          <div class="icon bg-red-500  rounded-full w-12 h-12 ml-7 flex items-center justify-center">
            <BookMarked/>
          </div>
          <div class=" text-center ml-6">
            <div class="text-xl text-center">
              Total Dish Ordered
            </div>
            <div class="text-2xl font-bold text-red-500">
              {{ totalOrders }}
            </div>
          </div>        
        </div>

        <div class=" bg-gray-700 rounded-lg p-4  w-[330px] flex justify-start  items-center">
          <div class="icon bg-green-500 rounded-full w-12 h-12  ml-10 flex items-center justify-center">
            <Users />
          </div>
          <div class=" text-center ml-6">
            <div class="text-xl text-center ">
              Total Customers
            </div>
            <div class="text-2xl font-bold text-green-500">
              {{ totalCustomers }}
            </div>
          </div>        
        </div>
        
      </div>
      <div class=" content flex flex-wrap gap-4 p-4 ml-[15px] ">
        <div class="order-report bg-gray-700 rounded-lg p-4 w-[1070px] max-h-[603px] overflow-y-auto">
          <h2 class="text-xl font-bold mb-4 text-[#EA7C69]">Order Report</h2>
          <table class="w-full ">
            <thead >
              <tr>
                <th class="bg-gray-800 text-white flex py-2 ">Customer</th>
                <th class="bg-gray-800 text-white py-2 px-4">Menu</th>
                <th class="bg-gray-800 text-white py-2 px-4">Quantity</th>
                <th class="bg-gray-800 text-white py-2 px-4">Total Payment</th>
              </tr>
            </thead>
            <tbody class=" w-full " >
              <!-- <div  class="flex w-full"> -->
                <tr v-for="(item, products) in lastTenOrderProducts" :key="products" class=" w-full  border-b" >
                  <td class="flex items-center pt-3 pb-3 ">
                    <div class="customer-icon  bg-gray-800 rounded-full w-8 h-8 flex justify-center">
                      <img :src="item?.avatar ? fileUrl + item?.avatar : null"
                      style="width:35px; height: 35px; border-radius: 50%;" alt="image">
                    </div>
                    <span class="ml-2">{{ item.customer_name }}</span>
                  </td>
                  <td class=" text-center pt-3 ">
                    {{ item.product_name }}
                  </td>
                  <td class=" text-center  pt-3">
                    {{ item.qty }}
                  </td>
                  <td class=" text-center  pt-3">
                    <span class="status completed ">${{ item.unit_price }}</span>
                  </td>
                  <!-- <br class=" bg-black  ">     -->
                </tr>       
                  
              <!-- Other table rows -->
            </tbody>
          </table>
        </div>       
      </div>
    </section>

    <section>
      <div class="most-ordered bg-gray-700 rounded-lg p-4 w-[500px] mt-4 ml-3 ">
        
        <header class="flex  items-center justify-between">
          <h2 class="text-xl font-bold mb-4 text-[#EA7C69]  ">Most Ordered</h2>
          <div class="flex mb-4 border px-2 py-2 rounded-xl">
            <span class="mr-2">Filter Order</span>
            <ChevronDown ></ChevronDown>
          </div>
        </header>
        
        <div class="order-items flex flex-col gap-2 h-[230px] overflow-y-auto ">
          <div v-for="(product, index) in mostOrderedProducts" :key="index" class="order-item flex items-center gap-2 w-full">
            <img :src="product.image ? fileUrl + product.image : null" alt="" class="h-[54px] w-[54px] rounded-full ml-5 mt-1">
            <div class="flex flex-col ml-5">
              <span class=" text-xl ">{{ product.name }}</span>
              <span class="text-[#ABBBC2]">{{ product.total_ordered }} dishes ordered</span>
            </div>
            
          </div>
        </div>
        
        <footer class="items-center text-center">
          <button class="view-all w-[70%] hover:bg-gray-500 bg-purple-500 text-white px-4 py-2 rounded-md mt-5 font-bold " @click="gotoDetails">
            View All
          </button>
        </footer>
        
      </div>

      <div class="most-type-order bg-gray-700 rounded-lg p-4 w-[500px] mt-7 ml-3">
          <h2 class="text-xl font-bold mb-4 text-[#EA7C69]">Most Type of Order</h2>
          <div class="filter flex items-center justify-between ">
            <span>Today</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white stroke-current w-6 h-6">
              <path d="M19 11L12 18L5 11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="chart flex items-center gap-4 justify-center">
            <div class="flex justify-center h-fit w-full">
              <canvas ref="pieChart" width="280"  height="280"></canvas>

                <!-- <div class="chart-container " style="position: relative; height:fit-content; width:fit-content">
                </div> -->
            </div>

            <!-- <div class="pie-chart w-64  h-64 ">
              <svg width="100%" height="100%" viewBox="0 0 150 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="75" cy="75" r="60" stroke="#383838" stroke-width="4"  stroke-dasharray="301.59 75.40" stroke-dashoffset="" fill="none"/>
                <circle cx="75" cy="75" r="55" stroke="#D988F0" stroke-width="4" stroke-dasharray="231.59 75.40" stroke-dashoffset="" fill="none"/>
                <circle cx="75" cy="75" r="50" stroke="#FDC55C" stroke-width="4"/>
              </svg>
            </div>
            <div class="legend flex flex-col gap-1">
              <div class="legend-item flex items-center gap-2">
                <div class="color bg-purple-500 rounded-sm w-3 h-3"></div>
                <span>Dine In</span>
                <span>(20 Customers)</span>
              </div>
              <div class="legend-item flex items-center gap-2">
                <div class="color bg-purple-500 rounded-sm w-3 h-3"></div>
                <span>Dine In</span>
                <span>(20 Customers)</span>
              </div>
              <div class="legend-item flex items-center gap-2">
                <div class="color bg-purple-500 rounded-sm w-3 h-3"></div>
                <span>Dine In</span>
                <span>(20 Customers)</span>
              </div>
            </div> -->
          </div>
      </div>

    </section>
  </div>
</template>

<script>
// import Chart from 'chart.js/auto';
import axiosClient from "@/service/GlobalApi";
import Chart from 'chart.js/auto';
import { CircleDollarSign,  BookMarked, Users, ChevronDown } from "lucide-vue-next";
import { useRouter }  from 'vue-router';


export default {
  
  name: "Dashboard",

  components:{
    BookMarked,Users, CircleDollarSign, ChevronDown,
  },

  data() {
    
    return {

      totalRevenue: 0,
      totalOrders: 0,
      mostOrderedProducts: [],
      lastTenOrderProducts: [],
      totalCustomers:[],
      pieChart: null,
      pieChartData: [], // Initialize most ordered products array
      fileUrl:import.meta.env.VITE_APP_FILE_BASE_URL,
      router: useRouter(),
      
    };
  },

  mounted() {
    this.fetchDashboardData();
  },
  methods: {
    gotoDetails() {
      this.$router.push('/orderDetails');
    },

    async fetchDashboardData() {
      try {
        const response = await axiosClient.get('/admin/dashboard');

        this.totalRevenue = response.data.total_sale_today;
        this.totalOrders = response.data.total_orders;
        this.totalCustomers = response.data.total_customers;
        this.mostOrderedProducts = response.data.most_ordered_products;
        this.lastTenOrderProducts = response.data.lastTenOrderProducts;
        this.renderPieChart(response.data.mostOrderedCategory);

      } catch (error) {
        console.error('Error fetching dashboard data:', error);
        // Handle error scenario (e.g., show error message)
      }
    },

  // async fetchPieChartData() {
  //     try {
  //         // Simulating static data retrieval
  //         const staticData = [
  //         { label: 'Dine In', value: 200 },
  //         { label: 'To Go', value: 122 },
  //         { label: 'Delivery', value: 264 }
  //         ];

  //         this.pieChartData = staticData;
  //         this.renderPieChart(); // Render the pie chart with static data
  //     } catch (error) {
  //         console.error('Error fetching pie chart data:', error);
  //     }
  // },

  renderPieChart(mostOrderedCategory) {
      const ctx = this.$refs.pieChart.getContext('2d');
      const transformedData = mostOrderedCategory.map(item => ({
        label: item.product_type_name,
        value: item.count,
      }));
      this.pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: transformedData.map(data => data.label),
          datasets: [{
            data: transformedData.map(data => data.value),
            backgroundColor: [
              '#FF5733', // Orange Red
              '#FFD700', // Gold
              '#357EC7', // Cornflower Blue
              '#20B2AA', // Light Sea Green
              '#9932CC', // Dark Orchid
              '#FF6347', // Tomato
              '#00CED1', // Dark Turquoise
              '#FF8C00', // Dark Orange
              '#4682B4', // Steel Blue
              '#6A5ACD', // Slate Blue
            ],
            // Example colors
          }],
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
          padding: 0, // Adjust padding around the chart
        },
        plugins: {
          legend: {
            position: 'right', // Ensure legend is on the right side
          },
        },
      },
      });
    },
  // renderPieChart() {
  //   const ctx = this.$refs.pieChart.getContext('2d');
  //   this.pieChart = new Chart(ctx, {
  //     type: 'pie',
  //     data: {
  //       labels: this.pieChartData.map(data => data.label),
  //       datasets: [{
  //         data: this.pieChartData.map(data => data.value),
  //         backgroundColor: ['#FF5733', '#FFD700', '#357EC7'], // Example colors
  //       }],
  //     },
  //     options: {
  //       responsive: true,
  //       maintainAspectRatio: false,
  //       legend: {
  //         position: 'right',
  //       },
  //     },
  //   });
  // },
  },

  

};

</script>

<style scoped>
::-webkit-scrollbar{
  width: 2px;
}
::-webkit-scrollbar{
  background: #252836;
}
::-webkit-scrollbar-thumb{
  background: linear-gradient(#252836, #e36751)
}
</style>
