<?php

namespace Mixdinternet\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Venturecraft\Revisionable\RevisionableTrait;

class Category extends Model
{
    use SoftDeletes, RevisionableTrait, Sluggable;

    protected $revisionCreationsEnabled = true;

    protected $revisionFormattedFieldNames = [
        'name' => 'nome',
        'slug' => 'nome amigável',
        'description' => 'descrição',
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = ['status', 'name', 'description', 'type'];

    public $translatable = ['name', 'description', 'slug'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function __construct(array $attributes = [])
    {
       parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();
    }

    public function scopeSort($query, $fields = [])
    {
        if (count($fields) <= 0) {
            $fields = [
                'status' => 'asc',
                'name' => 'asc'
            ];
        }

        if (request()->has('field') && request()->has('sort')) {
            $fields = [request()->get('field') => request()->get('sort')];
        }

        foreach ($fields as $field => $order) {
            $query->orderBy($field, $order);
        }
    }

    public function scopeActive($query)
    {
        $query->where('status', 'active')->sort();
    }

    # revision
    public function identifiableName()
    {
        return $this->name;
    }
}
