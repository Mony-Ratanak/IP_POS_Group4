<template>

    <div>
      <button @click="print" :disabled="downloading">
        <span v-if="downloading">Downloading...</span>
        <span v-else>Download Invoice</span>
      </button>
    </div>

  </template>
  
  <script>
    import axiosClient from "@/service/GlobalApi";
    import FileSaver from 'file-saver';
  
  export default {
    name: 'InvoiceDownloader',
    props: {
      receiptNumber: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        downloading: false,
        // cart: '',
        // receiptNumber: null // Ensure this value is dynamically set as required
      };
    },
    
    methods: {

      async print() {
        try {

          if (!this.receiptNumber) {
            console.error('Receipt number is undefined.');
            return;
          }

          this.downloading = true;  
          
          // Call the print method from the printOrder service to request the invoice
          const response = await axiosClient.get(`/admin/sales/order-invoice/${this.receiptNumber}`);
          
          //console.log(this.receiptNumber);
          // Success case
          this.downloading = false;

          // Convert the base64 data to a Blob
          const blob = this.b64toBlob(response.data.file_base64, 'application/pdf');

          // Save the Blob as a PDF file
          FileSaver.saveAs(blob, 'Invoice-' + this.receiptNumber + '.pdf');
        } catch (error) {
            // Error case
            this.downloading = false;
            console.error(error);
        }
      },
  
      b64toBlob(b64Data, contentType = '', sliceSize = 512) {
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
      }, 

      // async getOrder(){
      //   try {

      //     const response = await axiosClient.get(`/admin/sales`);

      //     this.receiptNumber = response.data.data[0].receipt_number; // Set the receipt number from the first order

      //     //console.log(this.receiptNumber);
      //   } catch (error) {
      //     console.error('Error fetching orders:', error);
      // }
    //},
  }
}
  </script>
  
  <style scoped>
  button {
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    background-color: #ea7c69;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
  }
  
  button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  </style>
  