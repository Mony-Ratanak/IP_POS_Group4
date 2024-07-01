<template>
    <div class="bg-[#252836] drop-shadow-md w-full h-56 shadow-inner shadow-inherit mt-2 relative flex justify-center">
        <div class="absolute bg-[#1F1D2B] h-auto mb-4 w-[96%] mt-5 rounded-xl">
            <Tabs default-value="account" class="w-full" v-model="activeTab">
                <TabsList class="grid w-full grid-cols-2 border items-center h-12">
                    <TabsTrigger value="account" class="text-white hover:bg-slate-500" :class="{ 'bg-[#2b2936]': activeTab === 'account' }">
                        Account
                    </TabsTrigger>
                    <TabsTrigger value="password" class="text-white hover:bg-slate-500" :class="{ 'bg-[#2b2936]': activeTab === 'password' }">
                        Password
                    </TabsTrigger>
                </TabsList>
                <TabsContent value="account">
                    <Card class="w-[100%]">
                        <CardHeader>
                            <CardTitle class="text-white items-center text-center">Account</CardTitle>
                            <CardDescription class="text-white items-center text-center">
                                Make changes to your account here. Click save when you're done.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-2 flex text-white">
                            <div class="w-[30%] items-center flex justify-center flex-col">
                                <img :src="avatarPreview" class="w-64 h-64 mb-2" alt="avatar">
                                <input type="file" @change="onFileChange" class="ml-20">
                            </div>
                            <div class="w-[70%]">
                                <div class="space-y-1 mb-2">
                                    <Label for="name">Name</Label>
                                    <Input id="name" type="text" class="bg-[#1F1D2B] w-full" v-model="user.name" />
                                </div>
                                <div class="space-y-1 mb-2">
                                    <Label for="phone">Phone</Label>
                                    <Input id="phone" type="text" class="bg-[#1F1D2B] w-full" v-model="user.phone" />
                                </div>
                                <div class="space-y-1">
                                    <Label for="email">Email</Label>
                                    <Input id="email" type="text" class="bg-[#1F1D2B] w-full" v-model="user.email" />
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end">
                            <Toaster />
                            <!--   -->
                            <Button @click="updateUser" class="px-3 py-5 bg-slate-800 text-white hover:bg-slate-500" 
                            >
                            Save changes
                            </Button>
                        </CardFooter>
                    </Card>
                </TabsContent>
                <TabsContent value="password">
                    <Card class="w-[100%]">
                        <CardHeader>
                            <CardTitle class="text-white items-center text-center">Password</CardTitle>
                            <CardDescription class="text-white items-center text-center">
                                Change your password here. After saving, you'll be logged out.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-2 text-white">
                            <div class="space-y-1">
                                <Label for="current">Current password</Label>
                                <Input id="current" type="password" class="bg-[#1F1D2B] w-full" v-model="passwords.old_password" />
                            </div>
                            <div class="space-y-1 mb-2">
                                <Label for="new">Current password</Label>
                                <Input id="new" type="password" class="bg-[#1F1D2B] w-full" v-model="passwords.new_password" />
                            </div>
                            <div class="space-y-1 mb-2">
                                <Label for="confirm">New password</Label>
                                <Input id="confirm" type="password" class="bg-[#1F1D2B] w-full" v-model="passwords.confirm_password" />
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end">
                            <Button class="px-3 py-5 bg-slate-800 text-white hover:bg-slate-500" @click="changePassword">Save password</Button>
                        </CardFooter>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
    </div>
</template>

<script>
    import { Button } from '@/components/ui/button';
    import {
        Card,
        CardContent,
        CardDescription,
        CardFooter,
        CardHeader,
        CardTitle,
    } from '@/components/ui/card';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import {
        Tabs,
        TabsContent,
        TabsList,
        TabsTrigger,
    } from '@/components/ui/tabs';

    import { User } from 'lucide-vue-next';
    import axiosClient from "@/service/GlobalApi";

    import { useToast } from '@/components/ui/toast/use-toast';
    import { Toaster } from '@/components/ui/toast';


    export default {

        name: "userAccount",
        components: {
            Button, Input, Label, Card, User,
            CardContent,
            CardDescription,
            CardFooter,
            CardHeader,
            CardTitle,
            Tabs,
            TabsContent,
            TabsList,
            TabsTrigger,
            Toaster,
        },

        data() {
            return {
                activeTab: 'account',
                FILE_URL: import.meta.env.VITE_APP_FILE_BASE_URL,
                defaultAvatar: 'assets/icons/images.png',
                selectedElement: null,

                user: {
                    name: "",
                    phone: "",
                    email: "",
                    avatar: null
                },
                avatarPreview: '',

                selectedFile: null,
                passwords: {
                    old_password: "",
                    new_password: "",
                    confirm_password: "",
                },
            }
        },

        mounted() {
            //this.toast = useToast(); // Initialize toast in mounted hook
            this.getUserProfile();
        },

        methods: {

            async getUserProfile() {
                try {
                    const response      = await axiosClient.get('/profile');
                    this.user           = response.data;
                    this.avatarPreview  = this.FILE_URL + this.user.avatar;
                    this.selectedFile   = null;  // Reset selectedFile to null
                    this.user.avatar    = null;  // Reset base64 string to null

                } catch (error) {
                    console.error('Error fetching user profile:', error);
                }
            },

            // TODO: this is functionality for the changes passwords
            async changePassword() {
                try {
                    const response = await axiosClient.post('/profile/change-password', this.passwords);
                    //alert(response.data.message);
                    if (response.data.status === 'Success') {
                        localStorage.clear();
                        this.$router.push('/');
                    }
                } catch (error) {
                    console.error('Error changing password:', error);
                }
            },

            onFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.selectedFile = file; // Update this line to set selectedFile
                    this.updateAvatarPreview(file);
                    this.convertFileToBase64(file);
                }
            },

            // TODO: review this when the user changes their image
            updateAvatarPreview(file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.avatarPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            // TODO: convert file to base 64 for update image to database
            convertFileToBase64(file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.user.avatar = e.target.result;  // Set the base64 string to user.avatar
                };
                reader.readAsDataURL(file);
            },

            // TODO: this function is for updating the user 
            async updateUser() {

                try {
                    const formData = new FormData();
                    formData.append('name',     this.user.name);
                    formData.append('phone',    this.user.phone);
                    formData.append('email',    this.user.email);

                    if (this.user.avatar) {  // Check if avatar base64 string is set
                        formData.append('avatar', this.user.avatar);  // Append the base64 string
                    }

                    const response = await axiosClient.post(`/profile`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    });

                    const resData = response.data.data;

                    this.user = {
                        ...this.user,
                        ...resData
                    };

                    this.avatarPreview = this.FILE_URL + this.user.avatar;

                    localStorage.setItem('user', JSON.stringify(this.user));

                    // alert(response.data.message);
                    // Reload the page to update the data
                    window.location.reload();

                } catch (error) {
                    console.error('Error updating user profile:', error);

                }
            },

        },
        watch: {
            user: {
                handler() {
                    // Reload the page automatically when the user data changes
                    // this.$router.go(0);
                },
                deep: true, // Enable deep watching to detect changes in nested properties
            },
        },

    }
</script>
