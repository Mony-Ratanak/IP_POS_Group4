<template>
  <div>
    <div class="bg-[#252836]">
      <div
        class="container-listing-user-header flex gap-2 justify-between items-center py-2 px-5 mt-5 ml-4 mr-4 rounded-2xl drop-shadow-lg shadow-inner bg-[#252836]">
        <div class="flex flex-1 items-center font-medium">
          <div class="flex items-center whitespace-nowrap">
            <HomeIcon class="text-white" />
          </div>
          <div class="flex items-center ml-1 whitespace-nowrap">
            <ChevronRight class="icon-size-4.5 text-white" />
            <span class="ml-1 text-white text-xl">User</span>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="sm:flex flex justify-start items-center">
            <Input type="text" placeholder="Search..." v-model="key" @input="handleSearch"
              class="pl-12 h-[44px] rounded-md bg-[#252836] text-xl border-[#393C49]" />
            <span class="absolute px-4">
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
                <SheetHeader>
                  <SheetTitle class="text-white text-center">Create New User</SheetTitle>
                  <SheetDescription class="text-white flex flex-col gap-4 items-center">
                    <div class="w-full">
                      <p class="mb-2">Upload your avatar</p>
                      <input type="file" @change="handleFileUpload"
                        class="h-[44px] w-full rounded-lg mt-3 pl-3 text-white" />
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Enter your name</p>
                      <input type="text" v-model="newUser.name" placeholder="Enter your name"
                        class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Enter your email</p>
                      <input type="email" v-model="newUser.email" placeholder="Enter email"
                        class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <div class="w-full">
                      <p class="mb-2">User Type</p>
                      <select v-model="newUser.type_id" class="h-[44px] w-full rounded-lg bg-[#252836] pl-3">
                        <option value="" disabled>Select User Type</option>
                        <option v-for="(type) in userTypes" :key="type.value" :value="type.value" class="">{{ type.text }}</option>
                      </select>
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Enter your phone</p>
                      <input type="text" v-model="newUser.phone" placeholder="Enter phone number"
                        class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <div class="w-full">
                      <p class="mb-2">Enter your password</p>
                      <input type="password" v-model="newUser.password" placeholder="Enter password"
                        class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
                    </div>
                    <button type="button" class="h-[44px] w-[240px] bg-[#252836] rounded-full hover:bg-slate-600"
                      @click="createUser">Create</button>
                  </SheetDescription>
                </SheetHeader>
              </SheetContent>
            </Sheet>

          </div>
        </div>
      </div>

      <div class="container-listing-user-body pt-3 pl-3 pr-6">
        <div v-if="isSearching">
          <div class="min-w-26 w-full flex flex-col items-center overflow-hidden min-h-30 p-2">
            <p class="text-xl mb-4 text-white">Please wait! request Data</p>
            <div class="lds-spinner">
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
            </div>
          </div>
        </div>

        <div v-if="data?.length === 0 && !isSearching" class="flex flex-col justify-center items-center mb-4">
          <i class="icon-size-24" data-heroicon-name="toc"></i>
          <span class="text-2xl"><span>No Data</span></span>
        </div>

        <div class="container-listing-user-content rounded-lg flex-col mx-2 cursor-pointer mt-2"  >
          <div class="" scrollbar>
            <table class="w-full">
              <thead>
                <tr class="flex justify-between font-medium text-xl text-gray-400 mb-3 border-b pb-4">
                  <th>NO</th>
                  <th>Name Account</th>
                  <th>Content</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th aria-label="row actions"></th>
                </tr>
              </thead>
              <tbody class="text-white">
                <tr v-for="(element, index) in dataSource" :key="element.id" class="">
                <tr class="flex items-center border-b pb-1 mb-1">
                  <td class="w-[280px] text-[#EA7C69]">{{ index + 1 }}</td>
                  <td class="w-[395px]" @click="openViewDialog(element)">
                    <div class="flex flex-row justify-start items-center">

                      <div class="flex justify-center items-center pr-1">
                        <img :src="element?.avatar ? FILE_URL + element?.avatar : null"
                          style="width:35px; height: 35px; border-radius: 50%;" alt="image">
                      </div>

                      <div class="flex flex-col items-start ml-1">
                        <p class="text-[#EA7C69]">{{ element?.name }}</p>
                        <p class="text-gray-400 text-base">{{ element?.type?.name }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="w-[330px]">
                    <div class="flex flex-col justify-start items-start">
                      <p>{{ element?.phone }}</p>
                      <p class="text-gray-500">{{ element?.email }}</p>
                    </div>
                  </td>

                  <!-- :checked="element?.is_active === 1" @change="blockUser($event, element.id)" -->
                  <td class="w-[305px]">
                    <label class="switch">
                      <input type="checkbox" :checked="element?.is_active === 1" @change="blockUser($event, element.id)">
                      <span class="slider"></span>
                    </label>
                    <!-- <Switch :check="element?.is_active === 1" @change="blockUser($event, element.id)"
                    ></Switch> -->
                  </td>
                  
                  <td class="w-[260px]">
                    <div class="flex flex-col justify-center items-start">
                      <p><span class="text-gray-400 text-base">created at:</span> {{ formatDate(element?.created_at) }}
                      </p>
                      <p><span class="text-gray-400 text-base">edited at:</span> {{ formatDate(element?.updated_at) }}
                      </p>
                    </div>
                  </td>

                  <td>
                    <DropdownMenu>
                      <DropdownMenuTrigger>
                        <button>
                          <EllipsisVertical class="icon-size-6"></EllipsisVertical>
                        </button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent class="bg-[#252836] mr-14">
                        <DropdownMenuSeparator />
                        <DropdownMenuItem class="hover:bg-gray-500" @click="openEditDialog(element)">
                          <div  class="flex items-center cursor-pointer p-2">
                            <Eye class="icon-size-5 text-blue-500 mr-2" />
                            <span class="text-lg text-blue-500">Edit</span>
                          </div>
                        </DropdownMenuItem>

                        <DropdownMenuItem class="hover:bg-gray-500" @click=" openChangePasswordDialog(element)" >
                          <div class="flex items-center cursor-pointer p-2">
                            <Key class="icon-size-5 mr-2 text-gray-500" />
                            <span class="text-lg text-gray-500">CPassword</span>
                          </div>
                        </DropdownMenuItem>

                        <DropdownMenuItem class="hover:bg-gray-500" @click="openDeleteDialog(element)">
                          <div  class="flex items-center cursor-pointer p-2">
                            <Trash2 class="icon-size-5 text-red-400 mr-2" />
                            <span class="text-lg text-red-400">Delete</span>
                          </div>
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>

                  </td>
                </tr>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- next page -->
        <div class="flex items-center justify-center gap-4 mt-4">
            <button @click="previousPage" :disabled="page === 1" class="w-11 h-11 rounded-md flex justify-center items-center bg-[#ef1515]" :class="{ 'opacity-50 cursor-not-allowed': page === 1 }">
                <ChevronLeft class="text-2xl font-bold text-white" />
            </button>
            <button @click="nextPage" :disabled="page * limit >= total" class="w-11 h-11 rounded-md flex justify-center items-center bg-[#ef1515]" :class="{ 'opacity-50 cursor-not-allowed': page * limit >= total }">
                <ChevronRight class="text-2xl font-bold text-white" />
            </button>
        </div>
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
            <AlertDialogAction @click="deleteUser(selectedElement?.id)" class="hover:bg-slate-600 border bg-red-300">Delete</AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      <!-- Edit User Sheet -->
      <Sheet v-model:open="isEditDialogOpen">
        <SheetContent class="bg-[#1F1D2B]">
          <SheetHeader>
            <SheetTitle class="text-white text-center">Edit User</SheetTitle>
            <SheetDescription class="text-white flex flex-col gap-4 items-center">
              <div class="w-full">
                <p class="mb-2">Upload your avatar</p>
                <input type="file" @change="handleEditFileUpload"
                  class="h-[44px] w-full rounded-lg mt-3 pl-3 text-white" />
              </div>
              <div class="w-full -mt-3">
                <p class="mb-2">Enter your name</p>
                <input type="text" v-model="selectedElement.name" placeholder="Enter your name"
                  class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
              </div>
              <div class="w-full">
                <p class="mb-2">Enter your email</p>
                <input type="email" v-model="selectedElement.email" placeholder="Enter email"
                  class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
              </div>
              <div class="w-full">
                <p class="mb-2">User Type</p>
                <select v-model="selectedElement.type_id" class="h-[44px] w-full rounded-lg bg-[#252836] pl-3">
                  <option value="" disabled>Select User Type</option>
                  <option v-for="(element) in userTypes" :key="element.id" :value="element.value">{{ element.text }}</option>
                </select>
              </div>
              <div class="w-full">
                <p class="mb-2">Enter your phone</p>
                <input type="text" v-model="selectedElement.phone" placeholder="Enter phone number"
                  class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
              </div>
              <div class="w-full">
                <p class="mb-2">Enter your password</p>
                <input type="password" v-model="selectedElement.password" placeholder="Enter password"
                  class="h-[44px] w-full rounded-lg bg-[#252836] pl-3" />
              </div>
              <button type="button" class="h-[44px] w-[240px] bg-[#252836] rounded-full hover:bg-slate-600"
                @click="updateUser">Update</button>
            </SheetDescription>
          </SheetHeader>
        </SheetContent>
      </Sheet>

      <!-- change password -->
      <Dialog  v-model:open="isChPasswordDialogOpen">
        <DialogContent class="sm:max-w-[425px] bg-[#1F1D2B] text-white">
          <DialogHeader>
            <DialogTitle>Person</DialogTitle>
            <DialogDescription>
              Make changes to your password here. Click save when you're done.
            </DialogDescription>
          </DialogHeader>

          <div class="grid gap-4 py-4">
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="newPassword" class="text-right">
                New Password
              </Label>
              <Input id="newPassword" type="password" v-model="newPassword" class="col-span-2 w-[285px] bg-[#252836] " />
            </div>
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="confirmPassword" class="text-right">
                Confirm Password
              </Label>
              <Input id="confirmPassword" type="password" v-model="confirmPassword" class="col-span-2 w-[285px] bg-[#252836]" />
            </div>
          </div>

          <DialogFooter @click="changePassword">
            <Button type="submit" class=" px-5 py-3 bg-white hover:bg-gray-500 text-amber-800 ">
              Save changes
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      <!-- view user -->
      <Dialog v-model:open="isViewDialogOpen">
        <DialogContent class="sm:max-w-[700px] bg-[#1F1D2B] text-white">
          <!-- <DialogTitle class="text-white">Person Information</DialogTitle> -->
          <div class="flex justify-center items-center">

            <div class="w-[40%] ml-16">

              <img :src="selectedElement?.avatar ? FILE_URL + selectedElement?.avatar : null"
                  style="width:200px; height: 200px; border-radius: 50%;" alt="image">
            </div>

            <div class="w-[60%] flex gap-5 flex-col ml-7">
              <div class="flex gap-4 items-center">
                <Label class="text-gray-500">Name:</Label>
                <p>{{ selectedElement?.name }}</p>
              </div>
              <div class="flex gap-4 items-center">
                <Label class="text-gray-500">Email:</Label>
                <p>{{ selectedElement?.email }}</p>
              </div>
              <div class="flex gap-4 items-center">
                <Label class="text-gray-500">Phone:</Label>
                <p>{{ selectedElement?.phone }}</p>
              </div>
              <div class="flex gap-4 items-center">
                <Label class="text-gray-500">User Type:</Label>
                <p>{{ selectedElement?.type.name }}</p>
              </div>
              <div class="flex gap-4 items-center">
                <Label class="text-gray-500">User_id:</Label>
                <p>{{ selectedElement?.id }}</p>
              </div>
            </div>
          </div>
        </DialogContent>
      </Dialog>

    </div>
  </div>
</template>


<script>
import { ref, onMounted } from 'vue';
import axiosClient from "@/service/GlobalApi";
import { Input } from "@/components/ui/input";
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
import { Search, HomeIcon, ChevronRight, Plus, Eye, Key, Trash2, EllipsisVertical, Loader,ChevronLeft } from "lucide-vue-next";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
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
import { User } from 'lucide';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';


export default {
  name: 'User',
  components: {
    Input, Switch, Search, HomeIcon, ChevronRight, Plus, Eye, Key, Trash2, EllipsisVertical,Loader,
    Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger,
    DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger,
    AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger,
    Label,Button, Dialog, DialogContent ,DialogDescription,DialogFooter,DialogHeader,DialogTitle,DialogTrigger, ChevronLeft
  },  

  data() {

    return {
      data: [],
      dataSource: null,
      total: 0,
      page: 1,
      limit: 10,
      key: '',
      searchKey: '',
      menuVisible: null,
      FILE_URL: import.meta.env.VITE_APP_FILE_BASE_URL,

      newUser: {
        name: "",
        email: "",
        type_id: "",
        phone: "",
        password: "",
        image: null // Ensure this is set to null initially
      },

      userTypes: [
        { value: 1, text: "Admin" },
        { value: 2, text: "Staff" },
        { value: 3, text: "Customer" },
      ],

      isSearching: false,
      isDeleteDialogOpen: false,
      isEditDialogOpen: false,
      isChPasswordDialogOpen:false,

      isViewDialogOpen: false,

      // selectedElement: null,
      element: {
        id: null, // Ensure id is initialized to null
        name: "",
        email: "",
        type_id: "",
        phone: "",
        password: "",
        image: null
      },

      selectedElement: null,
      newPassword: '',
      confirmPassword: ''
    };
  },
  methods: {

    async listing(limit, page) {
      
      this.isSearching = true;
      try {
        const response = await axiosClient.get(`/admin/users?limit=${limit}&page=${page}&key=${this.key}`);
        this.data = response.data.data;
        this.total = response.data.total;
        this.page = response.data.current_page;
        this.dataSource = this.data;
       // console.log(this.data);
      } catch (error) {
        console.error("Error fetching users:", error);
      } finally {
        this.isSearching = false;
      }
    },

    handleSearch() {
      this.searchKey = this.key;
      this.listing(this.limit, this.page);
    },

    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          this.newUser.image = reader.result;
        };
        reader.readAsDataURL(file);
      }
      console.log(event)
    },

    handleEditFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          this.selectedElement.image = reader.result;
        };
        reader.readAsDataURL(file);
      }
    },

    async createUser() {
      try {
        const formData = new FormData();
        for (const key in this.newUser) {
          if (key === 'image' && this.newUser[key]) {
            formData.append('image', this.newUser[key]);
          } else {
            formData.append(key, this.newUser[key]);
          }
        }
        const response = await axiosClient.post("/admin/users", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        this.data.push(response.data);
        this.newUser = { name: "", email: "", type_id: "", phone: "", password: "", image: null };
        await this.listing(this.limit, this.page);
        // alert("User created successfully.");

      } catch (error) {
        if (error.response && error.response.status === 422) {
          alert("Unprocessable Content: Please check the input fields.");
        } else {
          alert("Failed to create user.");
        }
        console.error("Error creating user:", error);
      }
    },

    previousPage() {
      if (this.page > 1) {
        this.page -= 1;
        this.listing(this.limit, this.page);
      }
    },

    nextPage() {
      if (this.page < Math.ceil(this.total / this.limit)) {
        this.page += 1;
        this.listing(this.limit, this.page);
      }
      //console.log(this.page)
    },

    openDeleteDialog(data) {
      this.selectedElement    = data;
      // this.userIdToDelete = userId;
      this.isDeleteDialogOpen = true;
      // this.isViewDialogOpen   = true;
    },

    

    openViewDialog(data) {
      this.selectedElement = data;
      this.isViewDialogOpen = true;
    },

    openEditDialog(user) {
      this.selectedElement = user;
      this.isEditDialogOpen = true;
    },

    openChangePasswordDialog(user) {
      this.selectedElement = user;
      this.isChPasswordDialogOpen = true;
    },

    closeChangePasswordDialog() {
      this.isChPasswordDialogOpen = false;
    },

    closeDeleteDialog() {
      this.isDeleteDialogOpen = false;
    },

    closeEditDialog() {
      this.isEditDialogOpen = false;
    },

    async viewUser (userId){

      const response = await axiosClient.get(`/admin/users/${userId}`);
      console.log(response);
      this.data = response.data;
      this.isViewDialogOpen = false;

    },

    async deleteUser(userId) {
      
      try {
        const response = await axiosClient.delete(`/admin/users/${userId}`);

        if (response.status === 200) {
          this.data = this.data.filter(user => user?.id !== userId); // Update 'data' instead of 'dataSource'
          this.dataSource = this.data; // Update 'dataSource' accordingly
        }
      } catch (error) {
        alert('Failed to delete user.');
        console.error('Error deleting user:', error);
      } finally {
        this.isDeleteDialogOpen = false;
      }
    },

    async blockUser(event, userId) {
      try {
        const status = event.target.checked ? 1 : 0;
        const response = await axiosClient.post(`/admin/users/block/${userId}`, { status });

        // console.log(response);
        if (response.status === 200) {
          this.data = this.data.map(user => {
            if (user.id === userId) {
              user.is_active = status;
            }
            return user;
          });

          this.dataSource = this.data;
          alert("User status updated successfully.");
        }else{
          alert("Failed to update user status.");
        }
      } catch (error) {
        console.log("Error updating user status")
        alert("Failed to update user status.");
      }
    },

    async changePassword() {
      try{

        // Ensure this.element.id is defined
        if (!this.selectedElement.id) {
          throw new Error('User ID is missing');
        }

        // Create formData object with new and confirm password
        const formData = new FormData();
        formData.append('password', this.newPassword);
        formData.append('confirm_password', this.confirmPassword);

        const response = await axiosClient.post(`/admin/users/${this.selectedElement.id}/change-password`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        alert("updated password successfully.");
        
        await this.listing(this.limit, this.page);
        this.closeChangePasswordDialog = false;

      }catch (error){
        if (error.response && error.response.status === 422) {
          alert("Unprocessable Content: Please check the input fields.");
        } else {
          alert("Failed to update user.");
        }
        console.error("Error updating user:", error);
      };
    },

    async updateUser() {
      try {

        // Ensure this.element.id is defined
        if (!this.selectedElement.id) {
          throw new Error('User ID is missing');
        }

        const formData = new FormData();

        for (const key in this.selectedElement) {
          if (key === 'image' && this.selectedElement[key].startsWith('data:image')) {
            formData.append('image', this.selectedElement[key]);
          } else {
            formData.append(key, this.selectedElement[key]);
          }
        }

        const response = await axiosClient.post(`/admin/users/${this.selectedElement.id}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });


        // Update local data upon successful response
        this.data = this.data.map(user => user.id === this.selectedElement.id ? response.data : user);
        this.dataSource = this.data;
        await this.listing(this.limit, this.page);
        //alert("User updated successfully.");
        this.isEditDialogOpen = false;
        //console.log(this.dataSource)

      } catch (error) {
        if (error.response && error.response.status === 422) {
          alert("Unprocessable Content: Please check the input fields.");
        } else {
          alert("Failed to update user.");
        }
        console.error("Error updating user:", error);
      }
    },

    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
  },

  mounted() {
    this.listing(this.limit, this.page);
  },
};
</script>

<style>
  /* Optional: Styling for the switch toggle */
  .switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 20px;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 12px;
    width: 12px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }

  input:checked + .slider {
    background-color: #ff8b3e;
  }

  input:checked + .slider:before {
    transform: translateX(20px);
  }
</style>