<?php

namespace App\Services;


class NameGenerator
{
    const adjectives = [
        'attractive', 'bald', 'beautiful',
        'chubby', 'clean', 'dazzling',
        'elegant', 'fancy',
        'fit', 'glamorous',
        'gorgeous', 'handsome', 'long',
        'magnificent', 'muscular', 'plain',
        'plump', 'quaint', 'scruffy',
        'shapely', 'short', 'skinny',
    ];

    const names = [
        'Bella', 'Coco', 'Max',
        'Buddy', 'Daisy', 'Lola',
        'Angel', 'Luna', 'Lucy',
        'Harle',
    ];

    public final function getNewName() : string
    {
        $adjective = self::adjectives[array_rand(self::adjectives)];
        $name = self::names[array_rand(self::names)];
        return sprintf('%s %s', ucfirst($adjective), ucfirst($name));
    }
}
