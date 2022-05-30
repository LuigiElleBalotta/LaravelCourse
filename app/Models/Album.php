<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

// LaravelIdeHelper genera questa documentazione in automatico
/**
 * App\Models\Album
 *
 * @property int $id
 * @property string $album_name
 * @property string $album_thumb
 * @property string|null $description
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $photos
 * @property-read int|null $photos_count
 * @method static \Database\Factories\AlbumFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereAlbumName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereAlbumThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read mixed $path
 * @property-read User $user
 */
class Album extends Model
{
    use HasFactory;

    // Se il nome della tabella non è uguale a quello della classe:
    // protected $table = 'Album';

    // Se la Primary Key della tabella non fosse "id":
    // protected $primaryKey = 'id';

    // Serve per evitare l'errore "Add [columnName] to fillable property to allow mass assignment on [App\Models\Album].";
    protected $fillable = ['album_name', 'album_thumb', 'description', 'user_id'];

    // Come prima, ma al contrario dichiaro i campi che voglio proteggere.
    protected $guarded = ['id'];

    // Per gestire la relazione delle tabelle del db la funzione deve avere lo stesso nome della tabella sul db.
    public function photos()
    {
        return $this->hasMany(Photo::class, 'album_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'album_category', 'album_id', 'category_id')
                    ->withTimestamps();
    }

    public function getPathAttribute() {
        $url = $this->album_thumb;
        if( stristr($this->album_thumb, 'http') === false ) {
            $url = 'storage/'.$this->album_thumb;
        }
        return $url;
    }
}
