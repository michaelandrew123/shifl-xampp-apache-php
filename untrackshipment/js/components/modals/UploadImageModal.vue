<style>
    .drag-drop-img{
        display: flex;
        height: 200px;
        width: 400px;
        background: white;
        border: 1px dashed gray;
        border: 1px dashed gray;
        align-items: center;
        justify-content: center;
        margin: auto;
        text-align: center;
        border-radius: 4px;
    }
    div.file-listing{
        width: 400px;
        margin: auto;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    div.file-listing img{
        height: 100px;
    }
    div.remove-container{
        text-align: center;
    }

    div.remove-container a{
        color: red;
        cursor: pointer;
    }
    a.submit-button{
        display: block;
        margin: auto;
        text-align: center;
        width: 200px;
        padding: 10px;
        text-transform: uppercase;
        background-color: white;
        border: black;
        color: black;
        font-weight: bold;
        margin-top: 20px;
    }
    progress{
        width: 400px;
        margin: auto;
        display: block;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    input[type=file]{
        color:transparent;
    }











    
    .dropbox {
        outline: 2px dashed grey; /* the dash box */
        outline-offset: -10px;
        background: white;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px; /* minimum height */
        position: relative;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .input-file {
        opacity: 0; /* invisible but it's there! */
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }

    .dropbox:hover {
        background: #091e420a; /* when mouse over to the drop zone, change color */
    }

    .dropbox p {
        font-size: 1.2em;
        text-align: center;
    }
    .upld{

    }

</style>
<template>
    <modal @modal-close="handleClose">
        <div
                @submit.prevent="handleConfirm"
                slot-scope="props"
                class="bg-white rounded-lg shadow-lg overflow-hidden w-full"

        >
            <slot :uppercaseMode="uppercaseMode" :mode="mode">
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{ __('Please Upload a Screenshot of the Tracking site.') }}</heading>
                    <div v-if="successAlert"  style=" padding: 20px 0px; color: rgb(14 117 55); background-color: rgb(214 250 228); text-align: center;" class="mb-4 rounded-lg bg-success-100 py-5 text-base text-success-700"
                            role="alert">
                        Upload image successfully!
                    </div>
                    <div v-if="failedAlert"  style=" padding: 20px 0px; color: rgb(176 35 58); background-color: rgb(250 229 233); text-align: center;" class="mb-4 rounded-lg bg-success-100 py-5 text-base text-success-700"
                         role="alert">
                        Failed to Upload image!
                    </div>
                    <!--<div class="flex border-b border-40 w-6/12">-->
                        <!--<div class="w-full">-->

                            <!--<div id="file-drag-drop">-->
                                <!--<form ref="fileform" enctype="multipart/form-data">-->
                                    <!--<span class="drop-files">Drop the files here!</span>-->
                                <!--</form>-->
                            <!--</div>-->

                            <!--<div v-for="(file, key) in files" class="file-listing flex flex-col">-->
                                <!--<img class="preview" v-bind:ref="'preview'+parseInt( key )"/>-->
                                <!--<div> {{ file.name }} </div>-->
                            <!--</div>-->

                            <!--<div v-if="files.length" class="remove-container">-->
                                <!--<a class="remove" v-on:click="removeFile( key )">Remove</a>-->
                            <!--</div>-->
                            <!--<a class="submit-button bg-green-200 cursor-pointer" v-on:click="submitFiles()" v-show="files.length">Submit</a>-->
                            <!--<progress max="100" :value.prop="uploadPercentage" v-show="loadingImage"></progress>-->
                        <!--</div>-->
                    <!--</div>-->


                    <!--<div id="file-drag-drop" class="w-full">-->
                        <!--<form ref="fileform" class="w-full">-->
                            <!--<span class="drop-files">Drop the files here!</span>-->
                        <!--</form>-->
                    <!--</div>-->

                    <!--<div>-->

                    <!--</div>-->

                    <!--<div id="file-drag-drop"  >-->
                        <!--<form ref="fileform" enctype="multipart/form-data " class="w-full">-->
                            <!--<span class="drop-files">Drop the files here!</span>-->
                        <!--</form>-->


                    <!--</div>-->
                    <div id="file-drag-drop" class="w-full">






                        <div class="container">
                            <!--UPLOAD-->
                            <!--<form enctype="multipart/form-data" novalidate v-if="isInitial || isSaving">-->
                            <form novalidate v-if="isInitial || isSaving">
                                <h1 style="display: none;">Upload images</h1>
                                <div class="dropbox">
                                    <!--<input type="file" multiple :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length"-->
                                    <input type="file" :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length"
                                    accept="image/*" class="input-file">
                                    <p v-if="isInitial">
                                        Drag your image here to begin<br> or click to browse
                                    </p>
                                    <p v-if="isSaving">
                                        Uploading {{ fileCount }} files...
                                    </p>
                                </div>
                            </form>
                            <!--SUCCESS-->
                            <div class="flex flex-col justify-center items-center" v-if="isSuccess">
                                <!--<h2>Uploaded {{ uploadedFiles.length }} file(s) successfully.</h2>-->
                                <div class="list-unstyled">
                                    <p v-for="item in uploadedFiles" style="width: 400px; display: flex; justify-content: center; align-items: center; ">
                                        <img :src="item.url" class="img-responsive img-thumbnail" :alt="item.originalName">
                                    </p>
                                </div>
                                <div class="upld flex flex-row  w-full justify-center items-center " style="padding-top: 1.25rem; padding-bottom: 1.25rem;">
                                    <p >
                                        <a href="javascript:void(0)" @click="reset()" class="btn text-80 font-normal h-9 px-3 btn-link no-underline flex items-center">Clear</a>
                                    </p>
                                    <p>
                                        <a href="javascript:void(0)" @click="uploadImage()" class="btn text-80 font-normal h-9 px-3  btn-link no-underline flex items-center" :disabled="uploadDisabled ? true : false" >upload</a>
                                    </p>
                                </div>
                            </div>
                            <!--FAILED-->
                            <div v-if="isFailed" class="flex flex-col justify-center items-center">
                                <p>
                                    <a href="javascript:void(0)" @click="reset()">Try again</a>
                                </p>
                                <pre>{{ uploadError }}</pre>
                            </div>
                        </div>



                            <!--<div class="container">-->
                                <!--&lt;!&ndash;UPLOAD&ndash;&gt;-->
                                <!--<form enctype="multipart/form-data"  >-->
                                    <!--<h1> </h1>-->
                                    <!--<div class="dropbox">-->
                                        <!--<input type="file" multiple :name="uploadFieldName" :disabled="isSaving"-->
                                               <!--accept="image/*" class="input-file">-->
                                        <!--<p  >-->
                                            <!--Drag your file(s) here to begin<br> or click to browse-->
                                        <!--</p>-->
                                        <!--<p  >-->
                                            <!--Uploading {{ fileCount }} files...-->
                                        <!--</p>-->
                                    <!--</div>-->
                                <!--</form>-->
                            <!--</div>-->








<!-- FIRST TRY -->
                        <!--<div style="display: none;">-->
                        <!---->
                        <!---->
                            <!--<progress max="100" :value.prop="uploadPercentage" v-show="loadingImage"></progress>-->
    <!---->
                            <!--<div v-for="(file, key) in files" class="file-listing flex flex-col">-->
                                <!--<img class="preview" v-bind:ref="'preview'+parseInt( key )"/>-->
                                <!--{{ file.name }}-->
    <!---->
    <!---->
                                <!--<div v-if="files.length" class="remove-container">-->
                                    <!--<a class="remove" v-on:click="removeFile( key )">Remove</a>-->
                                <!--</div>-->
                            <!--</div>-->
    <!---->
    <!---->
                            <!--<form ref="fileform" enctype="multipart/form-data " class="w-full drag-drop-img">-->
                                <!--<span class="drop-files">Drop the files here!</span>-->
                            <!--</form>-->
    <!---->
    <!---->
    <!---->
                            <!--<div class="card-body mx-10">-->
                                <!--&lt;!&ndash;<div v-if="success != ''" class="alert alert-success" role="alert">&ndash;&gt;-->
                                    <!--&lt;!&ndash;{{success}}&ndash;&gt;-->
                                <!--&lt;!&ndash;</div>&ndash;&gt;-->
                                <!--<form @submit="formSubmit" enctype="multipart/form-data" class="flex justify-center items-center">-->
    <!---->
                                    <!--<input type="file" class="form-control w-[50%]" v-on:change="onImageChange">-->
    <!---->
                                <!--</form>-->
                            <!--</div>-->
    <!---->
    <!---->
    <!---->
                            <!--<a class="submit-button bg-green-200 cursor-pointer" v-on:click="submitFiles()" v-show="files.length">Submit</a>-->
                        <!---->
                        <!--</div>-->

                        <!--<a class="submit-button" v-on:click="submitFiles()" v-show="files.length ">Submit</a>-->
                    </div>

                    <!--<div v-for="(file, key) in files" class="file-listing flex flex-col">-->
                        <!--<img class="preview" v-bind:ref="'preview'+parseInt( key )"/>-->
                        <!--<div> {{ file.name }} </div>-->
                    <!--</div>-->

                    <!--<div v-if="files.length" class="remove-container">-->
                        <!--<a class="remove" v-on:click="removeFile( key )">Remove</a>-->
                    <!--</div>-->
                    <!--<a class="submit-button bg-green-200 cursor-pointer" v-on:click="submitFiles()" v-show="files.length">Submit</a>-->
                    <!--<progress max="100" :value.prop="uploadPercentage" v-show="loadingImage"></progress>-->
                </div>
            </slot>

            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button type="button" data-testid="cancel-button" dusk="cancel-general-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                    <!--<button id="confirm-delete-button" ref="confirmButton" data-testid="confirm-button" type="submit" class="btn btn-default btn-danger">{{ __(uppercaseMode) }}</button>-->
                </div>
            </div>
        </div>
    </modal>
</template>


<script>

    // swap as you need
    import { upload } from './../../extra/file-upload.fake.service'; // fake service
    // import { upload } from '../../extra/file-upload.service';   // real service
    import { wait } from './../../extra/utils';

    const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

    export default {
        name: "UploadImageModal",
        data(){
            return {
                modalSample: 'hello world',
                dragAndDropCapable: false,
                files: [],
                uploadPercentage: 0,
                loadingImage: false,
                uploadError: null,
                currentStatus: null,
                uploadFieldName: 'photos',
                formData1: [],
                uploadDisabled: false,
                successAlert: false,
                failedAlert: false
            }
        },
        computed: {
            isInitial() {
                return this.currentStatus === STATUS_INITIAL;
            },
            isSaving() {
                return this.currentStatus === STATUS_SAVING;
            },
            isSuccess() {
                return this.currentStatus === STATUS_SUCCESS;
            },
            isFailed() {
                return this.currentStatus === STATUS_FAILED;
            }
        },
        methods: {
            reset() {
                // reset form to initial state
                this.currentStatus = STATUS_INITIAL;
                this.uploadedFiles = [];
                this.uploadError = null;
                this.successAlert = false;
                this.failedAlert = false;
                this.uploadDisabled = false;
            },
            save(formData) {
                // upload data to the server
                this.currentStatus = STATUS_SAVING;

                console.log(formData);
                upload(formData)
                    .then(wait(1500)) // DEV ONLY: wait for 1.5s
                    .then(x => {
                        this.uploadedFiles = [].concat(x);

                        this.currentStatus = STATUS_SUCCESS;
                    })
                    .catch(err => {
                        this.uploadError = err.response;
                        this.currentStatus = STATUS_FAILED;
                    });

            },
            filesChange(fieldName, fileList) {
                // handle file changes
                const formData = new FormData();

                if (!fileList.length) return;

                // append the files to FormData
                Array
                    .from(Array(fileList.length).keys())
                    .map(x => {
                        formData.append(fieldName, fileList[x], fileList[x].name);
                    });
                    this.formData1 = formData;
                // save it
                this.save(formData);
            },
            uploadImage(){
                axios.post( '/nova-vendor/untracked-shipments/file-drag-drop/' + this.$route.params.id ,
                    this.formData1,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        // onUploadProgress: function( progressEvent ) {
                        //     this.loadingImage = true;
                        //     this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                        // }.bind(this)
                    }
                ).then(res => {
                    // Nova.success(
                    //     "Untracked Shipment Saved Successfully!"
                    // )
                    // if(shouldGoTodetail){
                    //     this.$router.push('/untracked-shipments/'+ this.$route.params.id)
                    // }else{
                    //     this.$router.push('/untracked-shipments/')
                    // }
                    console.log(res.data);
                    if(res.data.result === 'Success upload'){
                        this.successAlert = true;
                        this.uploadDisabled = true;
                        // this.currentStatus = STATUS_INITIAL;
                        // this.uploadedFiles = [];
                        // this.uploadError = null;
                    }else if(res.data.result === 'Only PNG and JPG are allowed'){
                        this.failedAlert = true;
                    }
                }).catch(err => {
                    console.log(err)
                });

            },
            handleClose() {
                this.$emit('close')
            },
            handleConfirm() {
                this.$emit('confirm')
            },
        },
        mounted() {
            this.reset();
        },
    }




















//FIRST TRY
    // export default {
    //     name: "UploadImageModal",
    //     data(){
    //         return {
    //             dragAndDropCapable: false,
    //             files: [],
    //             uploadPercentage: 0,
    //             loadingImage: false,
    //         }
    //     },
    //     methods: {
    //         /*
    //     Determines if the drag and drop functionality is in the
    //     window
    //   */
    //         determineDragAndDropCapable(){
    //             /*
    //               Create a test element to see if certain events
    //               are present that let us do drag and drop.
    //             */
    //             var div = document.createElement('div');
    //
    //             /*
    //               Check to see if the `draggable` event is in the element
    //               or the `ondragstart` and `ondrop` events are in the element. If
    //               they are, then we have what we need for dragging and dropping files.
    //
    //               We also check to see if the window has `FormData` and `FileReader` objects
    //               present so we can do our AJAX uploading
    //             */
    //             return ( ( 'draggable' in div )
    //                 || ( 'ondragstart' in div && 'ondrop' in div ) )
    //                 && 'FormData' in window
    //                 && 'FileReader' in window;
    //         },
    //         /*
    //           Gets the image preview for the file.
    //         */
    //         getImagePreviews() {
    //             /*
    //               Iterate over all of the files and generate an image preview for each one.
    //             */
    //             for (let i = 0; i < this.files.length; i++) {
    //                 /*
    //                   Ensure the file is an image file
    //                 */
    //                 if (/\.(jpe?g|png|gif)$/i.test(this.files[i].name)) {
    //                     /*
    //                       Create a new FileReader object
    //                     */
    //                     let reader = new FileReader();
    //
    //                     /*
    //                       Add an event listener for when the file has been loaded
    //                       to update the src on the file preview.
    //                     */
    //                     reader.addEventListener("load", function () {
    //                         this.$refs['preview' + parseInt(i)][0].src = reader.result;
    //                     }.bind(this), false);
    //
    //                     /*
    //                       Read the data for the file in through the reader. When it has
    //                       been loaded, we listen to the event propagated and set the image
    //                       src to what was loaded from the reader.
    //                     */
    //                     reader.readAsDataURL(this.files[i]);
    //                 } else {
    //                     /*
    //                       We do the next tick so the reference is bound and we can access it.
    //                     */
    //                     this.$nextTick(function () {
    //                         this.$refs['preview' + parseInt(i)][0].src = '/images/file.png';
    //                     });
    //                 }
    //             }
    //
    //         },
    //         /*
    //           Removes a select file the user has uploaded
    //         */
    //         removeFile( key ){
    //             this.files.splice( key, 1 );
    //         },
    //         /*
    //           Submits the files to the server
    //         */
    //         submitFiles(){
    //             /*
    //               Initialize the form data
    //             */
    //             let formData = new FormData();
    //
    //             /*
    //               Iteate over any file sent over appending the files
    //               to the form data.
    //             */
    //             for( var i = 0; i < this.files.length; i++ ){
    //                 let file = this.files[i];
    //
    //                 formData.append('files[' + i + ']', file);
    //             }
    //
    //             /*
    //               Make the request to the POST /file-drag-drop URL
    //             */
    //             axios.post( '/nova-vendor/untracked-shipments/file-drag-drop',
    //                 formData,
    //                 {
    //                     headers: {
    //                         'Content-Type': 'multipart/form-data'
    //                     },
    //                     onUploadProgress: function( progressEvent ) {
    //                         this.loadingImage = true;
    //                         this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
    //                     }.bind(this)
    //                 }
    //             ).then(function (response) {
    //                 console.log(response.data);
    //             })
    //                 .catch(function(){
    //                     console.log('FAILURE!!');
    //                 });
    //         },
    //
    //
    //
    //         handleClose() {
    //             this.$emit('close')
    //         },
    //         handleConfirm() {
    //             this.$emit('confirm')
    //         },
    //     },
    //     /**
    //      * Mount the component.
    //      */
    //     mounted() {
    //
    //
    //         /* Determine if drag and drop functionality is capable in the browser */
    //         this.dragAndDropCapable = this.determineDragAndDropCapable();
    //         /*
    //           If drag and drop capable, then we continue to bind events to our elements.
    //         */
    //
    //         console.log(this.$refs.fileform);
    //         if( this.dragAndDropCapable ){
    //             /*
    //               Listen to all of the drag events and bind an event listener to each
    //               for the fileform.
    //             */
    //             ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach( function( evt ) {
    //                 /*
    //                   For each event add an event listener that prevents the default action
    //                   (opening the file in the browser) and stop the propagation of the event (so
    //                   no other elements open the file in the browser)
    //                 */
    //
    //                 this.$refs.fileform.addEventListener(evt, function(e){
    //                     console.log('drag');
    //                     e.preventDefault();
    //                     e.stopPropagation();
    //                 }.bind(this), false);
    //             }.bind(this));
    //
    //             /*
    //               Add an event listener for drop to the form
    //             */
    //             this.$refs.fileform.addEventListener('drop', function(e){
    //                 /*
    //                   Capture the files from the drop event and add them to our local files
    //                   array.
    //                 */
    //                 console.log('drog');
    //                 for( let i = 0; i < e.dataTransfer.files.length; i++ ){
    //                     this.files.push( e.dataTransfer.files[i] );
    //                     this.getImagePreviews();
    //                 }
    //             }.bind(this));
    //         }
    //
    //
    //     },
    // }
</script>

<style scoped>
</style>

