<?php

namespace App\Traits;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait FilterTrait
{
    public function filter(){
        return QueryBuilder::for(Post::class)
                ->allowedFilters([
                    AllowedFilter::callback('item', function (Builder $query, $value) {
                        $query->where('content','like','%'.$value.'%')
                            ->orWhere('price','like','%'.$value.'%')
                            ->orWhereHas('worker',function (Builder $query) use($value){
                                $query->where('name','like','%'.$value.'%');
                            });
                    }),
                ]);
    }
}
