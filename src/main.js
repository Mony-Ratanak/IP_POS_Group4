import { createApp } from "vue";
import App from "./App.vue";
import "./index.css";
// import axios from "axios";
import router from "./router";

const app = createApp(App);
// app.provide("axios", app.config.globalProperties.axios); // provide 'axios'
app.use(router);
app.mount("#app");
