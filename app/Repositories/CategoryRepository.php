<?php
namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository{

    public function getCategories($filter=[]){
        if($filter){
            $filter = (object) $filter;
        }
        $categories = Category::query()->latest('id');
        $params = [];
        if(isset($filter->search_txt) && !empty($filter->search_txt)){
            $categories = $categories->where('name','LIKE','%'.$filter->search_txt.'%');
            $params['search_txt'] = $filter->search_txt;
        }
        if(isset($filter->paginate)){
            $categories = $categories->paginate($filter->paginate);
            $params['paginate'] = $filter->paginate;
            if (!empty($params)) {
                $categories = $categories->appends($params);
            }
            return $categories;
        }
        return $categories->get();
    }

    public function getCategoryById($id){
        return Category::findOrFail($id);
    }

    public function createCategory($request){
        try{
            DB::beginTransaction();
            Category::create([
                'name' => $request -> name
            ]);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Category has been Created Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateCategory($id,$request){
        $category = $this->getCategoryById($id);
        try{
            DB::beginTransaction();
            $category->update([
                'name' => $request -> name
            ]);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Category has been Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function destroyCategory($id){
        $category = $this->getCategoryById($id);
        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Category has been Deleted Successfully'
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }
}
