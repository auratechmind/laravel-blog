<?php namespace App\Modules\Blog\Components;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Foundation\Http\FormRequest;


class PostRequest extends FormRequest{

        /**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if($this->user()->can_post())
		{
			return true;
		}
		return false;
	}

        /**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'title' => 'required|unique:posts|max:255',
                        'category' => 'required',
			'title' => array('Regex:/^[A-Za-z0-9 ]+$/'),
			'body' => 'required',
		];
	}	
}
?>
