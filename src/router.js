import { createRouter, createWebHistory } from "vue-router";
import LoginComponent from "./main/Auth/login.vue"; // Adjust the import as necessary
import OrderComponent from "./main/Order.vue"; // Adjust the import as necessary
import layout from "./main/layout.vue";
import user from "./main/user.vue";
import HomePage from "./main/Home.vue";
import productItem from "./main/productItem.vue";
import userAccount from "./main/userAccount.vue";
import orderDetails from "./main/OrderDetail.vue";
import ProductType from "./main/ProductType.vue";

const routes = [
  {
    name: "Login",
    path: "/",
    component: LoginComponent,
  },
  {
    name: "",
    path: "/",

    component: layout,

    children: [
      {
        name: "Homepage",
        path: "homepage",
        component: HomePage,
      },
      {
        name: "OrderPage",
        path: "order",
        component: OrderComponent,
      },

      {
        name: "OrderDetails",
        path: "orderDetails",
        component: orderDetails,
      },

      {
        name: "User",
        path: "user",
        component: user,
      },

      {
        name: "ProductItem",
        path: "productItem",
        component: productItem,
      },
      {
        name: "ProductType",
        path: "productType",
        component: ProductType,
      },

      {
        name: "UserAccount",
        path: "userAccount",
        component: userAccount,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
