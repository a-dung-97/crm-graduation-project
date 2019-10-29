<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request,
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request,
     *
     * @return array
     */
    public function rules()
    {
        return [
            "file" => 'required|mimes:csv,doc,docx,djvu,odp,ods,odt,pps,ppsx,ppt,pptx,pdf,ps,eps,rtf,txt,wks,wps,xls,xlsx,xps,
            aac,ac3,aiff,amr,ape,au,flac,m4a,mka,mp3,mpc,ogg,ra,wav,wma,
            bmp,exr,gif,ico,jp2,jpeg,pbm,pcx,pgm,png,ppm,psd,tiff,tga,
            7z,zip,rar,jar,tar,tar,gz,cab|max:4096'

        ];
    }
    public function messages()
    {
        return [
            'file.mimes' => 'Định dạng không được hỗ trợ',
            'file.max' => 'Chỉ được upload file có dung lượng tối đa 4MB'
        ];
    }
}
