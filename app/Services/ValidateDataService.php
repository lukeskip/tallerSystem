<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;

class ValidateDataService {
    
    protected $validatedData;

    public function __construct($request, $rules) {
        $this->validateData($request, $rules);
    }

    protected function validateData($request, $rules) {
        $validator = Validator::make($request, $rules);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $fieldErrors = [];
            foreach ($errors->messages() as $field => $messages) {
                $fieldErrors[$field] = $messages;
            }
            $this->validatedData = ['status' => false, 'errors' => $fieldErrors];
        } else {
            $cleanedData = $validator->validated();
            $this->validatedData = ['status' => true, 'data' => $cleanedData];
        }
    }

    public function getValidatedData() {
        return $this->validatedData;
    }
}
