<?php

namespace App;

use App\Former\Member;
use App\Vendor\PodcastFeedItem;
use App\Vendor\PodcastFeedPerson;

use Spatie\Feed\Feedable;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PodcastEpisode extends Model implements Feedable
{
    use HasFactory;
    use Sluggable, SluggableScopeHelpers;

    protected $dates = ['created_at'];

    //  TODO : Add with migration when member and podcast are in the same connection
    //  $table->foreign('author_id')->references('id')->on('member')->onDelete('cascade');
    //  public function author()
    //  {
    //
    //      return $this->belongsTo('App\Former\Member', 'author_id');
    //  }

    public function poster()
    {
        return $this->belongsTo('App\Former\Member', 'poster_id');
    }

    public function duration(): string
    {
        $minutes = floor($this->duration_in_seconds / 60);
        $seconds = floor($this->duration_in_seconds % 60);
        return sprintf("%d:%02d", $minutes, $seconds);
    }

    public function toFeedItem(): PodcastFeedItem
    {
        $author = Member::find($this->author_id);
        $podcastAuthor = PodcastFeedPerson::create([
            'name' => $author->pseudo,
            'email' => $author->mail,
            'websiteUrl' => '' // TODO
        ]);
        return PodcastFeedItem::create([
            'id' => $this->id,
            'title' => $this->title,
            'number' => $this->number,
            'summary' => $this->description,
            'description' => $this->description,
            'shortDescription' => $this->description, // TODO
            'author' => $author->pseudo,
            'podcastAuthor' => $podcastAuthor,
            'publicationDate' => $this->created_at,
            'updated' => $this->updated_at,
            'guid' => route('podcast.show', $this),
            'link' => route('podcast.show', $this),
            'imageUrl' => '', // TODO
            'enclosureUrl' => $this->audio_url,
            'enclosureType' => 'audio/mpeg',
            'enclosureLength' => 0, // TODO Bytes of file
            'duration' => $this->duration(),
            'explicit' => 'no',
        ]);
    }

    public function getAllFeedItems()
    {
        return self::orderByDesc('created_at')->limit(20)->get();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
