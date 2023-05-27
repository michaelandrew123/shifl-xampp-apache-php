 <template>
    <div>
        <!--<div class="col-md-12">-->
                <div class="form-group"  v-if="displayField">
                    <input  @paste="onPaste" type="text" class="file-paste form-control" placeholder="Copy paste file here..">
                </div>

                <div v-show="showUploadImageNow" class="flex items-center justify-center w-full h-screen text-center" style=""  >
                    <div style="padding: 20px;background-color: rgb(247, 247, 247);margin-bottom: 18px;" class="p-12 bg-gray-100 border border-gray-300" @dragover="onDragOver" @dragleave="onDragLeave" @drop="onDrop">
                        <input  type="file" multiple name="fields[assetsFieldHandle][]" id="assetsFieldHandle" style="display:none"
                               class="w-px h-px opacity-0 overflow-hidden absolute" @change="onSelectFiles" ref="fileInput" accept="*" />
                               <!--class="w-px h-px opacity-0 overflow-hidden absolute" @change="onSelectFiles" ref="fileInput" accept=".pdf,.jpg,.jpeg,.png" />-->

                        <label for="assetsFieldHandle" class="block cursor-pointer">
                            <div>

                                <span v-if="dragging">
                                    Drop your images/files..
                                </span>
                                <span v-else>
                                    Upload or drop your images/files..
                                </span>
                                <i class="fa fa-upload" aria-hidden="true"></i>
                            </div>
                        </label>
                    </div>
                </div>
            <div class="card-columns" v-if="showImagesPreview">
                <div class="card"  v-for="(upload, index) in file.uploads" style="text-align: center; padding: 6px 6px 6px;;  border: 1px solid #d6d6d6;" >
                    <img class="card-img-top"  :src="upload.preview" alt="Card image cap" style="height: auto;max-width: 100%;">
                    <div class="card-body" style="padding:0px;">

                        <span > {{upload.name_short }}</span> <br>

                        <h5 class="card-title">
                            <button type="button" class="btn-danger" @click="onRemoveFile(index, upload)" style=" border: none; font-size: 14px;border-radius: 21px;"  title="Remove">
                                 <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </h5>
                        <div class="col-md-12 mt-2">
                            <div class="progress" v-if="upload.progress > -1  " style="height: 4px;" >
                                <div  class="progress-bar bg-info" role="progressbar" v-bind:style="'width:' + upload.progress + '%'" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div v-if="upload.progress == 100" style="color: green;font-size: 12px;margin-top: -5px; padding-top: 4px;" >
                            Uploaded
                        </div>
                        <div v-if="upload.progress == 99" style="color: #219eb1;font-size: 12px;margin-top: -5px; padding-top: 4px;" >
                            Finalizing..
                        </div>
                        <div v-if="upload.progress < 99" style="color: #00805c;font-size: 12px;margin-top: -5px; padding-top: 4px;" >
                            {{ upload.progress }}
                        </div>
                    </div>
                </div>
            </div>
        <!--</div>-->
    </div>
</template>

<script>
    /**
     * TODO - Add a way to accept other files as well instead of just images
     * TODO - Add a way to read the following
     *
     * PDF, .ZIP, IMAGES, PPK, JPG, Docx, Docs and more other platform
     *
     * This way we can read, download them and use for work
     *
     * TODO - Add the entire process to the snippets for the future references
     */
    import helpers from "./../../mixin/helpers";

    // import VueUploadComponent from 'vue-upload-component'

    export default {

        // TODO - Set props default value if empty
        props: ["showImagesPreview", 'displayField'],

        components: {
            // Comment
            // FileUpload: VueUploadComponent
        },
        mixins: [
            helpers
        ],
        data: function() {
            return {
                showUploadImageNow: false,
                showTextArea:false,
                file: {
                    uploads: [],
                },
                dragging: false,
                files:{},

                acceptAsFiles: false,
                acceptAsImages: false,
                acceptAll: true,

            }
        },
        created: function() {
            // _this.displayField = false;
        },
        methods : {
                initialized: function(model) {
                    let _this = this;

                    if(model) {
                        _this.file.uploads = model.files;

                        // generate output
                        _this.file.uploads.map(function(v,i){
                            _this.file.uploads[i].output = v;
                            _this.file.uploads[i].progress = 100;
                            // _this.file.uploads[i].preview = "/" + v.path;
                        });

                        _this.showUploadImage();

                        console.log(" file uploaded ", _this.file);

                    }
                },
                initializedClear: function(data) {
                    console.log("clear files uploads");

                    let _this = this;

                    _this.file = {
                        uploads: [],
                    };

                    _this.forceUpdate();
                },
                initializedSetPaste: function(evt, data) {
                    let _this = this;

                    if(data) {
                        if (data.files) {
                            // TODO - Change files to file from the comment section component
                            _this.file = data.files;
                        }
                    }

                   _this.onPaste(evt);
                },
                showUploadImage:function() {
                    let _this = this;
                    let show = _this.showUploadImageNow;

                    _this.showUploadImageNow = ! show;
                },
                onPaste: function(evt) {
                    let _this = this;

                    var items = evt.clipboardData.items;

                    for (var i = 0; i < items.length; i++) {
                        if (items[i].type.indexOf("image") == -1) continue;
                        var blob = items[i].getAsFile();

                        _this.addBlob(blob)
                    }
                },
                onDrop: function(evt) {
                    let _this = this;

                    evt.preventDefault()

                    let files = evt.dataTransfer.files;

                    // console.log(" files now ", files);

                    _this.addFiles(files);

                    _this.dragging = false;
                },
                /**
                 *
                 *
                 *ACCESS STYLE: event.currentTarget.classList.add('bg-gray-100');
                 */
                onDragLeave(evt) {
                    // console.log("drag leave...");

                    let _this = this;

                    _this.dragging = false;

                    // // Clean up
                    // event.currentTarget.classList.add('bg-gray-100');
                    // event.currentTarget.classList.remove('bg-green-300');


                },
                /**
                 *
                 *
                 *
                 * ACCESS STYLE: event.currentTarget.classList.remove('bg-gray-100');
                 *
                 * @param evt
                 */
                onDragOver(evt) {
                    let _this = this;

                    evt.preventDefault();

                    // console.log("drag over....");

                    _this.dragging = true;
                },
                onSelectFiles() {
                    let _this = this;
                    let files = [...this.$refs.fileInput.files];

                    _this.addFiles(files);
                },
                onRemoveFile:function(index, upload) {
                    let _this = this;

                    if(upload.output) {

                        // console.log( _this.file.uploads);

                        _this.$http.delete('/api/files/' + upload.output.uuid );


                        // only if currently in progress, and still request is allowed to be cancelled
                        if(_this.file.uploads[index].ajaxRequest)  {
                            _this.file.uploads[index].ajaxRequest.cancel();
                        }
                    }

                    _this.file.uploads.splice(index, 1);

                    let uploads = _this.file.uploads;

                    // reindex - move to method
                    uploads.map(function(v, i){
                       _this.file.uploads[i].index = i;
                    });

                    // send files data update
                    _this.resendDataToParent();

                    _this.forceUpdate();

                },
                /**
                 * Create files to be displayed in frontend
                 *
                 *
                 *
                 * @param files
                 */
                addFiles: function(files) {
                    let _this = this;

                    // console.log(" files ", files);

                    if(_this.acceptAll == true) {
                        // proceed to all files to be accepted
                    }
                    else if(_this.acceptAsImages == true) {
                        files = _this.onlyImagesFilter(files);
                    }
                    else if(_this.acceptAsFiles == true) {
                        files = _this.onlyFilesFilter(files);
                    }




                    // console.log(" files ", files);

                    for (var i = 0; i < files.length; i++) {
                        let preview = URL.createObjectURL(files[i]);
                        let file = files[i];



                        // add default icon when not image
                        let defaultIcon = _this.getFileIcon(file);
                        if(defaultIcon) {
                            preview = defaultIcon
                        }

                        // create a shortname for display purpose

                        file.name_short = _this.limitString(file.name, 10);







                        // console.log("preview ", preview)

                        file.preview = preview;

                        _this.addFileUpload(file);
                    }

                    _this.resendDataToParent();

                    _this.forceUpdate();
                },
                /**
                 * Add blob images
                 *
                 *
                 * @param blob
                 */
                addBlob: function(file){
                    let _this = this;

                    let preview;

                    const reader = new FileReader();

                    reader.readAsDataURL(file);
                    reader.onloadend = function() {
                        preview = reader.result;

                        file.preview = preview;

                        _this.addFileUpload(file);
                    }
                },
                addFileUpload: function(file) {
                    let  _this = this;


                    if(! file.progress) {
                        file.progress = 0;
                    }

                    file.ajaxRequest = axios.CancelToken.source();

                    _this.file.uploads.push(file);

                    let index = _this.file.uploads.length - 1;

                    file.index = index;

                    _this.uploadFile(file, index);
                },
                uploadFile: function(file, index) {
                    let _this = this;

                    if(! file) {
                        file = _this.file;
                    }


                    console.log(" index ", file.index)

                    // if(! file.output) {
                        let headers = {
                            // 'Authorization' : 'Bearer ' + this.access_token
                        };

                        const config = {
                            headers: { 'content-type': 'multipart/form-data' }
                        };

                        let attributes = new FormData();

                        attributes.append('file', file);

                        let url = '/api/files';

                        _this.$http.post(
                            url,
                            attributes,
                            {
                                headers,
                                cancelToken: file.ajaxRequest.token,
                                onUploadProgress: function( progressEvent ) {

                                    console.log("  progressEvent.total  " + progressEvent.loaded + "/ progressEvent.total  " + progressEvent.total)

                                    let uploadPercentage = parseInt(Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ));

                                    if(uploadPercentage > 0) {
                                        uploadPercentage -= 1;
                                    }

                                    _this.file.uploads[index].progress = uploadPercentage;

                                    //console.log(" index " + index + " = percentage = " + uploadPercentage);

                                    _this.$forceUpdate();
                                }
                            }
                        ).then(function (response) {
                            // console.log(" response ", response);

                            let output = response.data.data.file;

                            // TODO - Change files to something singular
                            _this.file.uploads[index].output = output;

                            _this.resendDataToParent();

                            _this.$forceUpdate();


                        }).finally(function (response) {

                            // console.log(" successfully uploaded", index);

                            _this.file.uploads[index].progress = 100;

                            _this.$forceUpdate();

                        }).catch(function (error) {
                        });
                    // } else {
                    //     console.log(" withoutput already");
                    // }
                },
                deleteFile: function(file) {
                },
                resendDataToParent: function() {
                    let _this = this;

                    _this.sendParentEmit('onFileUpdate', {
                        file: _this.file
                    });
                },

                // onlyImagesFilter: function(files) {
                //     let _this = this;
                //
                //     let data = [];
                //
                //     let counter = 0;
                //
                //     for (var i = 0; i < files.length; i++) {
                //         if (files[i].type.indexOf("image") == -1) continue;
                //
                //         data[counter] = files[i];
                //
                //         counter++;
                //     }
                //
                //     return data;
                // },
                // onlyFilesFilter: function(files) {
                //     let _this = this;
                //
                //     let data = [];
                //
                //     let counter = 0;
                //
                //     for (var i = 0; i < files.length; i++) {
                //         if (files[i].type.indexOf("image") == -1) continue;
                //
                //         data[counter] = files[i];
                //
                //         counter++;
                //     }
                //
                //     return data;
                // },


        }
    }
</script>
