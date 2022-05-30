<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Album;

class AlbumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /*
        $albumId = $this->route()->album;
        if(!$albumId) {
            return true;
        }

        $album = $albumId instanceof Album ? $albumId : Album::findOrFail($albumId);

        if( Gate::denies('manage-album', $album )) {
            return false;
        }
        */

        // La parte commentata non serve più perchè è la Policy ora che esegue i controlli.

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->route()->album; // "album" e non "id" perchè per la rotta il parametro si chiama "album"


        $ret = [
            'album_name' => ['required'],
            // 'description' => 'required',
            // 'user_id' => 'required'
        ];

        if( $id ) {
            $ret["album_name"][] = Rule::unique('albums')->ignore($id);
        }
        else {
            $ret["album_thumb"] = "required|image";
            $ret["album_name"][] = Rule::unique('albums');
        }
        return $ret;
    }

    public function messages() {
        $messages = [
            'album_name.required' => 'Il campo album name è obbligatorio',
            'album_name.unique' => 'Il campo album name esiste già',
            'name.required' => 'Il campo Nome è obbligatorio.',
            'description.required' => 'Il campo Descrizione è obbligatorio.',
            'album_thumb.required' => 'Il campo Immagine è obbligatorio.',
        ];

        return $messages;
    }
}
