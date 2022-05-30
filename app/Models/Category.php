<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $category_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Album[] $albums
 * @property-read int|null $albums_count
 * @method static \Database\Factories\CategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $user
 * @method static Builder|Category getCategoriesByUserId(\App\Models\User $user)
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'user_id'];

    // Con la proprieta "with" possiamo precaricare in automatico le relazioni che specifichiamo nell'array.
    // protected $with = ['albums'];

    public function albums() {
        return $this->belongsToMany(Album::class)
                    ->withTimestamps();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeGetCategoriesByUserId(Builder $builder, User $user) {
        $builder->whereUserId($user->id)->withCount('albums')->orderBy('category_name');
    }
}
