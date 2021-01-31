<?php

namespace App\Vendor;

class PodcastFeedPerson
{
    public string $name;
    public string $email;
    public string $websiteUrl;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function create(array $data = []): self
    {
        return new static($data);
    }
}
