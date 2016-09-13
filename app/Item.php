<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'quantity', 'description',
    ];

    public $timestamps = true;

    /**
     * Overwrite the original create method to invalidate the cache by tag
     *
     * @param array $attributes
     * @return Model
     */
    public static function create(array $attributes = [])
    {
        // invalidate affected item caches
        Cache::tags(["item"])->flush();

        // call parent method
        return parent::create($attributes);
    }

    /**
     * Overwrite the original save method to invalidate the cache by tag
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // invalidate affected item caches
        Cache::tags(["item", "item_all"])->flush();

        // call parent method
        return parent::save($options);
    }

    /**
     * Offer a cached version of the all() method
     *
     * @return mixed
     */
    public static function c_all()
    {
        // tag all caches that should be invalidated if an item changes or gets added to the DB appropriately
        return Cache::tags(["item", "item_all"])->remember('items', env("CACHE_DURATION", $minutes = 1), function() {
            return Item::all();
        });
    }

    /**
     * Offer a cached custom method with fancy ordering filtering etc.
     *
     * @return mixed
     */
    public static function c_orderedByName()
    {
        // tag all caches that should be invalidated if an item changes or gets added to the DB appropriately
        return Cache::tags(["item", "item_all"])->remember('itemsOrderedByName', env("CACHE_DURATION", $minutes = 1), function() {
            return Item::orderBy("name")->get();
        });
    }

    /**
     * Offer a cached custom method with fancy ordering filtering etc.
     *
     * @return mixed
     */
    public static function c_orderedByDescription()
    {
        // tag all caches that should be invalidated if an item changes or gets added to the DB appropriately
        return Cache::tags(["item", "item_all"])->remember('itemsOrderedByDescription', env("CACHE_DURATION", $minutes = 1), function() {
            return Item::orderBy("description")->get();
        });
    }

}
