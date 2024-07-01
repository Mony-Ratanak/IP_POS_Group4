// src/utils/axiosClient.js
import axios from "axios";

//console.log("API Base URL:", import.meta.env.VITE_APP_API_BASE_URL);

const getToken = () => {

  const storage = localStorage.getItem("accessToken") || null;
  return storage;
  
};

const axiosClient = axios.create({

    baseURL: import.meta.env.VITE_APP_API_BASE_URL,
    headers: { "Content-Type": "application/json" },
    withCredentials: true,
});

axiosClient.interceptors.request.use(

  (config) => {
    const token = getToken();
    // console.log(token);
    if (token) {
      config.headers["Authorization"] = `Bearer ${token}`;
    }else{
      console.error("Authorization Token not found. Please log in.");
    }
    return config;
  },
  
  (error) => {
    console.error("Request error:", error);
    return Promise.reject(error);
  }
);

export default axiosClient;
