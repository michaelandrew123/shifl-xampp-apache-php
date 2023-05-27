<?php

namespace App\Services;

use App\Models\File;
//use App\Interfaces\FileRepositoryInterface;
use App\Services\Service;
use App\Support\Facades\Codewolfy;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Intervention;


class FileService extends Service {
    public $filePath = null;
    public $intervenation = null;
    public $size = null;
    public $subFolder = null;
    public $resizePath = null;
    public $temFileName = null;
    public $storagePath = null;
    function __construct($request = null)
    {
        parent::__construct($request, new File());
    }

    public function setStoragePath($storagePath) {
        $this->storagePath  = $storagePath;

        return $this;
    }
    public function setTemFileName($temFileName) {
        $this->temFileName  = $temFileName;

        return $this;
    }
    public function setSubFolder($subFolder) {
        $this->subFolder  = $subFolder;

        return $this;
    }
    public function setSizeMedium() {
        $this->size  = 350;

        return $this;
    }
    public function setSizeThumbnail() {
        $this->size  = 100;

        return $this;
    }
    public function setSizeOriginal() {
        $this->size  = null;

        return $this;
    }
    public function setSizeMain() {
        $this->size  = 500;

        return $this;
    }
    // reuse assignment from evaluation to any role
    public function copy($fromModel, $toModel)
    {
        $fromFiles = $fromModel->files;

        $toModel = $toModel->refresh();

        foreach($fromFiles as $fromFile) {
            $toFile = new File();

            $toFile->user_id        =  $fromFile->user_id;
            $toFile->date           =  $fromFile->date;
            $toFile->title          =  $fromFile->title;
            $toFile->key            =  $fromFile->key;
            $toFile->description    =  $fromFile->description;
            $toFile->position       =  $fromFile->position;
            $toFile->src            =  $fromFile->src;
            $toFile->path           =  $fromFile->path;
            $toFile->mime           =  $fromFile->mime;
            $toFile->size           =  $fromFile->size;
            $toFile->size_type      =  $fromFile->size_type;
            $toFile->real_path      =  $fromFile->real_path;
            $toFile->name           =  $fromFile->name;
            $toFile->new_name       =  $fromFile->new_name;
            $toFile->fileable_id    =  $toModel->id;
            $toFile->fileable_type  =  $fromFile->fileable_type;
            $toFile->type           =  $fromFile->type;
            $toFile->available      =  $fromFile->available;
           // $toFile->created_at     =  $fromFile->created_at;
           // $toFile->updated_at     =  $fromFile->updated_at;

            $toFile->save();
        }

        return $this;
    }
    public function updateParams()
    {
        $files = [];

        foreach($this->request->params as $param) {
            $param = Codewolfy::toObjects($param);

            $file = File::find($param->id);

            $file->available = $param->available;
            $file->order = $param->order;

            $file->save();

            $files[] = $file;
        }

        $this->results = $files;

        return $this;
    }
    public function reset() {
        $this->img = null;
        $this->filePath = null;

        return $this;
    }
    public function resizeAndAddBlob($request) {
        $this->filePath = $this->storagePath . '/' . $this->temFileName;

//        dd($request->file->getRealPath());
//        dd($this->filePath);
        $this->intervenation = Intervention::make($request->file->getRealPath());

        // resize only if the size is provided
        if($this->size) {
            $this->intervenation->resize($this->size, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $response = $this->intervenation->save($this->filePath);

        $srcPath = last(explode("storage\app/public/", $this->filePath));

        // local upload save path to database
        if($response) {
            if($this->imageSizeType === 'main') {
                $this->model->base_src_main = $srcPath;
            }
            else if($this->imageSizeType === 'medium') {
                $this->model->base_src_medium  = $srcPath;
            }
            else if($this->imageSizeType === 'thumbnail') {
                $this->model->base_src_thumbnail = $srcPath;
            }
        }

//        dd($this->model);

//        $path = last(explode("storage\app/public/", $response->dirname));
//
//        $path .


        // $logo = file_get_contents($this->filePath);
        // $base64 = base64_encode($logo);
        // $file->blob = $base64;
        // $file->save();

        $this->result = $response;

        $this->resizePath = $response;

        return $this;
    }

    public function uploadToExternalStorage() {

        if($this->model->filesystem === 'oss') {



            $response = Storage::disk('oss')->putFile($this->subFolder, $this->resizePath->dirname . '/' . $this->temFileName);


//            dd($response);
            // echo " uplaod " . $response;
            if($response) {
                if($this->imageSizeType === 'main') {
                    $this->model->base_src_main = $response;
                }
                else if($this->imageSizeType === 'medium') {
                    $this->model->base_src_medium  = $response;
                }
                else if($this->imageSizeType === 'thumbnail') {
                    $this->model->base_src_thumbnail = $response;
                }
            }
        }

        return $this;
    }
    public function unlinkLocalFile() {
        if(isset($this->filePath) && $this->model->filesystem != 'public') {
            $path = last(explode("storage\app/public/", $this->filePath));
            Storage::disk('public')->delete($path);
        }

        return $this;
    }
    public function resizeThumbnail($request, $file, $result, $folder) {
        // todo - create folder thumbnail if not exist

        $this->filePath = storage_path('app/public/' . $folder  . '/thumbnail/' . $file->new_name);

        $this->intervenation = Intervention::make($request->file->getRealPath());
        $this->intervenation->resize(150, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $this;
    }
    public function checkOrCreateFolder($dirPath = null) {
        $this->result =  false;

        if (!file_exists($dirPath)) {
            $this->result = mkdir($dirPath, 0777, true);
        }

        return $this;
    }
    public function resizeMedium($request, $file, $result, $folder)  {
        // $this->checkOrCreateFolder( storage_path('app/public/' . $folder  . '/medium'));
        // todo - create folder medium if not exist

        $this->filePath = storage_path('app/public/' . $folder  . '/medium/' . $file->new_name);

        $this->intervenation = Intervention::make($request->file->getRealPath());
        $this->intervenation->resize(350, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $this;
    }
    public function resizeLarge() {}
    public function resizeOriginal() {}
    public function save() {
       $this->result = $this->intervenation->save($this->filePath);

       return $this;
    }
    public function destroyMultiple() {
        foreach($this->models as $model) {
            $this->init($model)->destroy();
        }

        return $this;
    }
    public function destroy() {
        // todo - remove the files


        $this->result = $this->delete()->getResult();

        return $this;
    }
    public function nextAction($nextAction = null) {
        if(! $nextAction) {
            $nextAction = $this->request->next_action;
        }

        if(isset($nextAction)) {
            switch ($nextAction) {
                case File::$NEXT_ACTION_APPEND_KEY_WITH_ID:
                        $this->model->key = $this->model->key . '_' . $this->model->id;
                        $this->model->save();
                    break;
                case File::$NEXT_ACTION_CREATE_NEW_SIGNATURE_ENTRY:
                        // // generate new entry for the model
                        // $this->newModel->fileable_type = $this->model->fileable_type;
                        // $this->newModel->fileable_id = $this->model->fileable_id;
                        // $this->newModel->save();
                        //
                        // $newModel  = $this->newModel->refresh();
                        //
                        // $newModel->key = 'pdi_signature_inspector_' . $newModel->id;
                        // $newModel->save();
                    break;
                default:
                    // do nothing
                    break;
            }
        }
    }
    public function setImageOriginal() {
        $path = $this->model->path;
        $folder = explode('/', $path)[0];
        $this->path = $folder . '/'. $this->model->new_name;

        $this->result = $this->path;

        return $this;
    }
    public function setImageMedium() {
        $path = $this->model->path;
        $folder = explode('/', $path)[0];
        $this->path = $folder . '/medium/'. $this->model->new_name;

        $this->result = $this->path;

        return $this;
    }
    public function setImageThumbnail() {
        $path = $this->model->path;
        $folder = explode('/', $path)[0];
        $this->path = $folder . '/thumbnail/'. $this->model->new_name;

        $this->result = $this->path;

        return $this;
    }

    public function convertBase64ToFile($request)
    {
//        $base64Format = $request->base64Format;

//        if($base64Format) {
            $base64File = $request->base64Format;

            // Get file data base64 string
            $fileData = base64_decode(Arr::last(explode(',', $base64File)));

            // Create temp file and get its absolute path
            $tempFile = tmpfile();
            $tempFilePath = stream_get_meta_data($tempFile)['uri'];

            // Save file data in file
            file_put_contents($tempFilePath, $fileData);

            $tempFileObject = new \Illuminate\Http\File($tempFilePath);

            $file = new UploadedFile(
                $tempFileObject->getPathname(),
                $tempFileObject->getFilename(),
                $tempFileObject->getMimeType(),
                0,
                false // Mark it as test, since the file isn't from real HTTP POST.
            );

            // Close this file after response is sent.
            // Closing the file will cause to remove it from temp director!
            app()->terminating(function () use ($tempFile) {
                fclose($tempFile);
            });

            // only for testing - todo - make sure to change this to a valid request or not
            // if(app()->runningUnitTests()) {
//            $request->file = $file;
            // }
            // else {
            //     $request->merge([
            //         'file' => $file,
            //     ]);
            // }

            $this->result = $file;

            return $this;
//            }
        }

        public function uploadFavIcon($request = null){

            $this->result = null;

            if ($request->hasFile('favicon')) {

                // delete first the existing
                $files = $this->company->files()->where('key', 'favicon')->get();

                foreach($files as $file) {
                    Storage::disk('oss')->delete($file->path);
                }
                $this->company->files()->where('key', 'favicon')->delete();

                // upload the new banner uploaded
                $fileRequest = $request->file('favicon');

                $path = strtolower('easimpt-pos/' . $this->company->id .  '-' . str_replace(" ", '_', $this->company->name) . '/favicon');

                $response = Storage::disk('oss')->putFile($path, $fileRequest);

                $file = $this->company->files()->create([
                    'key' => 'favicon',
                    'path' =>$response,
                    'src' => 'https://easimpt-storage-ph.oss-ap-southeast-6.aliyuncs.com/' . $response
                ]);

                $this->result = $file;
            }

            return $this;

        }
        public function uploadBanner($request = null) {
            $this->result = null;

            if ($request->hasFile('banner')) {

                // delete first the existing
                $files = $this->company->files()->where('key', 'banner')->get();

                foreach($files as $file) {
                    Storage::disk('oss')->delete($file->path);
                    $file->delete();
                }
                $this->company->files()->where('key', 'banner')->delete();



                // upload the new banner uploaded
                $banner = $request->file('banner');

                $bannerPath = strtolower('easimpt-pos/' . $this->company->id .  '-' . str_replace(" ", '_', $this->company->name) . '/banner');

                $response = Storage::disk('oss')->putFile($bannerPath, $banner);

                $file = $this->company->files()->create([
                    'key' => 'banner',
                    'path' =>$response,
                    'src' => 'https://easimpt-storage-ph.oss-ap-southeast-6.aliyuncs.com/' . $response
                ]);

                $this->result = $file;
            }

            return $this;
        }

}
