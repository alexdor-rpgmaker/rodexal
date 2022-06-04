<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastEpisode extends Model
{
    use HasFactory;
    use Sluggable, SluggableScopeHelpers;

    const PODCAST_PAGE_URL = 'https://anchor.fm/alex-dor1';
    const PODCAST_FEED_URL = 'https://anchor.fm/s/4bfdd7fc/podcast/rss';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'created_at',
        'audio_url',
        'season',
        'number',
        'duration_in_seconds',
        'author_id',
        'poster_id'
    ];

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

    public function session(): int
    {
        return 2020 + $this->season;
    }

    public function seasonAndSession(): string
    {
        return "$this->season ({$this->session()})";
    }

    public function getRouteKeyName(): string
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
