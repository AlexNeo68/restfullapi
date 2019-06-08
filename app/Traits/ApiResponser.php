<?php 
  namespace App\Traits;

  use Illuminate\Support\Collection;
  use Illuminate\Database\Eloquent\Model;

  trait ApiResponser {

    private function successResponse($data, $code)
    {
      return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
      return response()->json(['error' => $message, 'code' => $code], $code);
    }
    
    protected function showAll(Collection $collection, $code = 200)
    {
      
      if($collection->isEmpty()){
        return $this->successResponse(['data' => $collection], $code);
      }

      $transformer = $collection->first()->transformer;
      
      $collection = $this->filterData($collection, $transformer);
      $collection = $this->sortData($collection, $transformer);
      
      $collection = $this->transformerApply($collection, $transformer);
      return $this->successResponse($collection, $code);
      
    }

    protected function showOne(Model $instance, $code = 200)
    {
      if(!$instance){
        return $this->successResponse(['data' => $instance], $code);
      }
      $transformer = $instance->transformer;
      $instance = $this->transformerApply($instance, $transformer);

      return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
      return $this->successResponse(['message' => $message], $code);
    }

    protected function transformerApply($source_data, $transformer)
    {
      return fractal($source_data, new $transformer)->toArray();
    }

    protected function sortData(Collection $collection, $transformer)
    {
      if(request()->has('sort_by')){
        $attribute = $transformer::originalAttribute(request()->sort_by);
        $collection = $collection->sortBy->{$attribute};
      }
      return $collection;
    }

    protected function filterData(Collection $collection, $transformer)
    {
      foreach(request()->query() as $query => $value){
        $attribute = $transformer::originalAttribute($query);
        if(isset($attribute, $value)){
          $collection = $collection->where($attribute, $value);
        }
      }
      return $collection;
    }


  }