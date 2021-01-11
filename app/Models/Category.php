<?php
namespace App\Models;

use Validator;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

        protected $table = "categories";

        /*
        |----------------------------------------------------------------
        |   Validation rules
        |----------------------------------------------------------------
        
            public $rules = array(

                'name'          => 'required',
                'slug'          => 'unique:products',
                'price'         => 'required'
            );

            */

        /*
        |----------------------------------------------------------------
        |   Validate data for add & extend & update records
        |----------------------------------------------------------------
        
        public function validate($data,$type){
           
                $ruleType = $this->rules;

            $validator = Validator::make($data,$ruleType);

            if($validator->fails()){
                return $validator;
            }
        }*/


        /*
        |----------------------------------------------------------------
        |   Data Relation
        |----------------------------------------------------------------
        

        public function catgeory(){
            return $this->belongsTo('App\Catgeory');
        } */

        public function desc()
        {
            return $this->hasMany(catDesc::class);
        }
 }

