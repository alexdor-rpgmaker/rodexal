<?php

namespace App\Vendor;

use Carbon\Carbon;
use Spatie\Feed\FeedItem;

class PodcastFeedItem extends FeedItem
{
    protected Carbon $createdAt;
    protected string $shortDescription;
    protected string $description;
    protected int $season;
    protected int $number;
    protected string $duration;
    protected string $imageUrl;
    protected string $explicit;
    protected string $enclosureUrl;
    protected PodcastFeedPerson $podcastAuthor;

    public static function create(array $data = []): self
    {
        return new static($data);
    }
}
