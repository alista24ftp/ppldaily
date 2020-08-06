<?php
namespace App\Infrastructure;

use Str;
use Illuminate\Support\Facades\Http;

class ImageHandler
{
    // only allow images with following extensions
    protected $allowed_exts = ['png', 'jpg', 'jpeg', 'gif'];

    public function save($file, $folder, $file_prefixes, $date_prefix=false)
    {
        // file extension (since images copy-paste from clipboard might remove extension)
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // Abort if uploaded file is not an image (extension not a part of allowed_ext)
        if(!in_array($extension, $this->allowed_exts)){
            return false;
        }

        // create saved directory pattern, such as: uploads/images/payment_proofs/201709/21
        // directory name separation allows for faster searching
        $folder_name = "uploads/images/$folder" . ($date_prefix ? '/' . date("Ym/d", time()) : "");

        // absolute upload path, `public_path()` gets `public` directory's absolute path
        // eg. /home/vagrant/Code/invoicemanager/public/uploads/images/payment_proofs/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // concat filename (adding prefix to improve readability, prefix can correspond to model ID)
        // eg. 1_1493521050_7BVc9v9ujP.png (<invoice_no>_time()_random(10).png)
        $prefix_str = '';
        foreach($file_prefixes as $prefix) {
            $prefix_str = $prefix_str . $prefix . '_';
        }
        $filename = $prefix_str . time() . '_' . Str::random(10) . '.' . $extension;

        // Move image to target location path
        $file->move($upload_path, $filename);

        return [
            'path' => "/$folder_name/$filename",
        ];
    }

    public function download($src, $src_host, $dest_folder="downloads/images")
    {
        try{
            $transformed_src = $this->transformImageName($src, $src_host);
            $extension = strtolower(pathinfo($transformed_src, PATHINFO_EXTENSION));
            if(!in_array($extension, $this->allowed_exts)) return null;
            $response = Http::get($transformed_src);
            if($response->successful()){
                $img = $response->body();
                $dest_path = "/$dest_folder";
                $img_name = time() . '_' . Str::random(10) . '.' . $extension;
                $full_dest_path = public_path() . $dest_path;
                $full_img_name = "$full_dest_path/$img_name";
                if(!file_exists($full_dest_path) || !is_dir($full_dest_path)){
                    if(!mkdir($full_dest_path, 0777, true)){
                        return null;
                    }
                }
                $img = file_put_contents($full_img_name, $img);
                return boolval($img) ? ['path'=>"$dest_path/$img_name"] : null;
            }
            return null;
        }catch(\Exception $e){
            return null;
        }
    }

    public function transformImageName($img_name, $img_host)
    {
        if(empty($img_name)) return '';
        $new_name = trim($img_name);
        if(empty($new_name) || strlen($new_name) < 3) return "";
        if(strtolower(substr($new_name, 0, 4)) == "http") return $new_name;
        if($new_name[0] == '/' && $new_name[1] == '/') return "http:$new_name";
        if($new_name[0] == '/') return $img_host . $new_name;
        return "$img_host/$new_name";
    }
}
