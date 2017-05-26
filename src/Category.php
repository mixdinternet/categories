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

    protected $fillable = ['status', 'name', 'description', 'image', 'type'];

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
        $this->hasAttachedFile('image', [
            'styles' => [
                'crop' => function ($file, $imagine) {
                    $image = $imagine->open($file->getRealPath());
                    if (request()->input('crop.image.w', 0) > 0 && request()->input('crop.image.y', 0) > 0) {
                        $image->crop(new \Imagine\Image\Point(request()->input('crop.image.x'), request()->input('crop.image.y'))
                            , new \Imagine\Image\Box(request()->input('crop.image.w'), request()->input('crop.image.h')));
                    }
                    return $image;
                }
            ],
            /*'default_url' => '/assets/img/avatar.png',*/
        ]);

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::bootStapler();
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'image' => $this->attachedFiles['image']->url()
            , 'image_crop' => $this->attachedFiles['image']->url('crop')
        ]);
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
