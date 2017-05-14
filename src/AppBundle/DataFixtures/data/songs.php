<?php
$bands = [
    'thebeatles' => [
        'id' => 94,
        'name' => 'The Beatles',
        'firstName' => null,
        'lastName' => null,
        'description' => 'The Beatles',
        'info' => 'The best band ever.'
    ],
];

$people = [
    'pavarotti' => [
        'id' => 614,
        'firstName' => 'Luciano',
        'lastName' => 'Pavarotti',
        'description' => 'Luciano Pavarotti',
        'info' => 'Just Pavarotti - the best.',
    ],
    'blackmore' => [
        'id' => 40,
        'firstName' => 'Ritchie',
        'lastName' => 'Blackmore',
        'description' => 'Ritchie Blackmore',
        'info' => "(born 14 April 1945) is an English guitarist and songwriter. He was one of the founder members of Deep Purple in 1968, playing jam-style hard-rock music which mixed guitar riffs and organ sounds. During his solo career, he established a heavy metal band called Rainbow which fused baroque music influences and elements of hard rock. Rainbow steadily moved to catchy pop-style mainstream rock. Later in life, he formed the traditional folk rock project Blackmore's Night transitioning to vocalist-centred sounds. As a member of Deep Purple, Blackmore was inducted into the Rock and Roll Hall of Fame in April 2016.",
    ],
    'gillan' => [
        'id' => 41,
        'firstName' => 'Ian',
        'lastName' => 'Gillan',
        'description' => 'Ian Gillan',
        'info' => "(born 19 August 1945) is an English singer and songwriter. He originally found commercial success as the lead singer and lyricist for Deep Purple.

Initially influenced by Elvis Presley, Gillan started and fronted several local bands in the mid-sixties, and eventually joined Episode Six when their original singer left. He first found widespread commercial success after joining Deep Purple in 1969. After an almost non-stop workload, during which time he recorded six albums in four years, and problematic relationships with other band members, particularly guitarist Ritchie Blackmore, Gillan resigned from the band in June 1973, having given a lengthy notice period to their managers.

After a short time away from the music business, he resumed his music career with solo bands the Ian Gillan Band and Gillan, before a year-long stint as the vocalist for Black Sabbath. He rejoined a reformed Deep Purple in 1984, but was fired in 1989. He rejoined the band for a second time in 1992 for their twenty-fifth anniversary, and following the recruitment of guitarist Steve Morse in 1994, has helped transform the group into a regular touring outfit, which he has fronted ever since.

In addition to his main work—performing with Deep Purple and other bands during the 1970s and 1980s—he sang the role of Jesus in the original recording of Andrew Lloyd Webber's rock opera Jesus Christ Superstar, performed in the charity supergroup Rock Aid Armenia, and engaged in a number of business investments and ventures, including a hotel, a motorcycle manufacturer, and music recording facilities at Kingsway Studios. More recently, he has performed solo concerts concurrently with his latter career in Deep Purple, and his work and affinity with Armenia, combined with his continued friendship with Tony Iommi since his brief time in Black Sabbath, has led him to form the supergroup WhoCares with Iommi. His solo career outside of Deep Purple was given a comprehensive overview with the Gillan's Inn box set in 2006."
    ],
//    , Ian Gillan, Roger Glover, Jon Lord, Ian Paice
    'jagger' => [
        'id' => 20,
        'firstName' => 'Mick',
        'lastName' => 'Jagger',
        'description' => 'Mick Jagger',
        'info' => "Sir Michael Philip \"Mick\" Jagger (born 26 July 1943) is an English singer and songwriter, the lead singer and one of the founder members of the Rolling Stones.

Jagger's career has spanned over 55 years, and he has been described as \"one of the most popular and influential frontmen in the history of rock & roll\". Jagger's distinctive voice and performance, along with Keith Richards' guitar style, have been the trademark of the Rolling Stones throughout the career of the band. Jagger gained press notoriety for his admitted drug use and romantic involvements, and was often portrayed as a countercultural figure.

In the late 1960s, Jagger began acting in films (starting with Performance and Ned Kelly), to mixed reception. In 1985, he released his first solo album, She's the Boss. In early 2009, Jagger joined the electric supergroup SuperHeavy. In 1989 he was inducted into the Rock and Roll Hall of Fame, and in 2004 into the UK Music Hall of Fame with the Rolling Stones. In 2003, he was knighted for his services to popular music.",
    ],
    'lennon' => [
        'id' => 10,
        'firstName' => 'John',
        'lastName' => 'Lennon',
        'description' => 'John Lennon',
        'info' => 'Member of The Beatles.',
    ],
    'mccartney' => [
        'id' => 11,
        'firstName' => 'Paul',
        'lastName' => 'Mc Cartney',
        'description' => 'Paul Mc Cartney',
        'info' => 'Member of The Beatles.',
    ],
    'sinatra' => [
        'id' => 1,
        'firstName' => 'Frank',
        'lastName' => 'Sinatra',
        'description' => 'Frank Sinatra',
        'info' => 'No explenation is required. If you do not know the guy, you are dummbass.',
    ],
    'paulanka' => [
        'id' => 4,
        'firstName' => 'Paul',
        'lastName' => 'Anka',
        'description' => 'Paul Anka',
        'info' => 'Legendary performer of Diana.'
    ],
    'lisaminelli' => [
        'id' => 3,
        'firstName' => 'Lisa',
        'lastName' => 'Minelli',
        'description' => 'Lisa Minelli',
        'info' => 'Daughter of Judy Garland. Great singer and actress. Watch the Cabaret!',
    ],
];

$song = [
    'id' => null,
    'genre' => null,
    'createdAt' => null,
    'title' => null,
    'lyrics' => null,
    'authors' => [],
    'artists' => [],
    [],
    'videos' => []
];

$songs = [
    [
        'id' => 110,
        'genre' => 'Rock',
        'createdAt' => '1972-01-01 00:00:00',
        'title' => 'Smoke on the Water',
        'lyrics' => "We all came out to Montreux
On the Lake Geneva shoreline
To make records with a mobile
We didn't have much time
Frank Zappa and the Mothers
Were at the best place around
But some stupid with a flare gun
Burned the place to the ground

Smoke on the water, a fire in the sky
Smoke on the water

They burned down the gambling house
It died with an awful sound
Funky Claude was running in and out
Pulling kids out the ground
When it all was over
We had to find another place
But Swiss time was running out
It seemed that we would lose the race

Smoke on the water, a fire in the sky
Smoke on the water

We ended up at the Grand Hotel
It was empty, cold and bare
But with the Rolling truck Stones thing just outside
Making our music there
With a few red lights, a few old beds
We made a place to sweat
No matter what we get out of this
I know, I know we'll never forget

Smoke on the water, a fire in the sky
Smoke on the water",
        'authors' => [
            $people['blackmore'],
            $people['gillan'],
        ],
        'artists' => [
            $people['blackmore'],
            $people['gillan'],
        ],
        [],
        'videos' => []
    ],
    [
        'id' => 103,
        'genre' => 'Rock',
        'createdAt' => '1979-10-11 15:10:00',
        'title' => 'Highway To Hell',
        'lyrics' => "Living easy, living free
Season ticket on a one-way ride
Asking nothing, leave me be
Taking everything in my stride
Don't need reason, don't need rhyme
Ain't nothing I would rather do
Going down, party time
My friends are gonna be there too

I'm on the highway to hell
On the highway to hell
Highway to hell
I'm on the highway to hell

No stop signs, speed limit
Nobody's gonna slow me down
Like a wheel, gonna spin it
Nobody's gonna mess me around
Hey Satan, paid my dues
Playing in a rocking band
Hey mama, look at me
I'm on my way to the promised land, whoo!

I'm on the highway to hell
Highway to hell
I'm on the highway to hell
Highway to hell

Don't stop me

I'm on the highway to hell
On the highway to hell
I'm on the highway to hell
On the highway
Yeah, highway to hell
I'm on the highway to hell
Highway to hell
Highway to hell

And I'm going down
All the way
Whoa!
I'm on the highway to hell",
        'authors' => [],
        'artists' => [],
        [],
        'videos' => []
    ],
    [
        'id' => 102,
        'genre' => 'Ballad',
        'createdAt' => '1979-10-11 15:10:00',
        'title' => 'Imagine',
        'lyrics' => "Imagine there's no heaven
It's easy if you try
No hell below us
Above us only sky

Imagine all the people
Living for today
Aha-ahh

Imagine there's no countries
It isn't hard to do
Nothing to kill or die for
And no religion too

Imagine all the people
Living life in peace
Yoohoo-ooh

You may say I'm a dreamer
But I'm not the only one
I hope someday you'll join us
And the world will be as one

Imagine no possessions
I wonder if you can
No need for greed or hunger
A brotherhood of man

Imagine all the people
Sharing all the world
Yoohoo-ooh

You may say I'm a dreamer
But I'm not the only one
I hope someday you'll join us
And the world will live as one",
        'authors' => [],
        'artists' => [],
        [],
        'videos' => []
    ],
    [
        'id' => 101,
        'genre' => 'Rock',
        'createdAt' => '2017-01-01 00:00:00',
        'title' => 'Satisfaction',
        'lyrics' => "I can't get no satisfaction, I can't get no satisfaction
'Cause I try and I try and I try and I try
I can't get no, I can't get no

When I'm drivin' in my car, and the man come on the radio
He's tellin' me more and more about some useless information
Supposed to fire my imagination
I can't get no, oh, no, no, no, hey, hey, hey
That's what I say

I can't get no satisfaction, I can't get no satisfaction
'Cause I try and I try and I try and I try
I can't get no, I can't get no

When I'm watchin' my tv and a man comes on and tell me
How white my shirts can be
But, he can't be a man 'cause he doesn't smoke
The same cigarettes as me
I can't get no, oh, no, no, no, hey, hey, hey
That's what I say

I can't get no satisfaction, I can't get girl reaction
'Cause I try and I try and I try and I try
I can't get no, I can't get no
When I'm ridin' round the world
And I'm doin' this and I'm signin' that
And I'm tryin' to make some girl, who tells me
Baby, better come back maybe next week
Can't you see I'm on a losing streak
I can't get no, oh, no, no, no, hey, hey, hey
That's what I say,

I can't get no, I can't get no
I can't get no satisfaction, no satisfaction
No satisfaction, no satisfaction",
        'authors' => [
            $people['jagger'],
        ],
        'artists' => [
            $people['jagger'],
        ],
        'audio' => [],
        'videos' => [
            [
                'id' => 600,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=VOgFZfRVaww',
                'artists' => [$people['lennon']],
                'info' => 'Official video',
            ],
        ]
    ],
    [
        'id' => 100,
        'createdAt' => '2016-10-21 00:00:00',
        'genre' => 'Ballad',
        'title' => 'Yesterday',
        'authors' => [
            $people['lennon'],
            $people['mccartney'],
        ],
        'artists' => [
            $people['sinatra'],
        ],
        'lyrics' => "Yesterday all my troubles seemed so far away.
Now it looks as though they're here to stay.
Oh, I believe in yesterday.

Suddenly I'm not half the man I used to be.
There's a shadow hanging over me.
Oh, yesterday came suddenly.

Why she had to go, I don't know, she wouldn't say.
I said something wrong, now I long for yesterday.

Yesterday love was such an easy game to play.
Now I need a place to hide away.
Oh, I believe in yesterday.

Why she had to go, I don't know, she wouldn't say.
I said something wrong, now I long for yesterday.

Yesterday love was such an easy game to play.
Now I need a place to hide away.
Oh, I believe in yesterday.

Mm mm mm mm mm mm mm",
        'audio' => [

        ],
        'videos' => [
            [
                'id' => 500,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=haWRUpPw_tI&autoplay=1',
                'artists' => [$bands['thebeatles']],
                'info' => 'Live performance',
            ],
            [
                'id' => 501,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=RjpzTys0s9g&autoplay=1',
                'artists' => [$people['mccartney']],
                'info' => 'Live performance',
            ],
        ],
    ],
    [
        'id' => 1,
        'createdAt' => '2016-10-21 00:00:00',
        'genre' => 'Ballad',
        'title' => 'My Way',
        'authors' => [
            $people['paulanka'],
        ],
        'artists' => [
            $people['sinatra'],
        ],
        'lyrics' => "And now, the end is near
And so I face the final curtain
My friend, I'll say it clear
I'll state my case, of which I'm certain

I've lived a life that's full
I've traveled each and every highway
But more, much more than this
I did it my way

Regrets, I've had a few
But then again, too few to mention
I did what I had to do
And saw it through without exemption

I planned each charted course
Each careful step along the byway
And more, much more than this
I did it my way

Yes, there were times, I'm sure you knew
When I bit off more than I could chew
But through it all, when there was doubt
I ate it up and spit it out
I faced it all and I stood tall
And did it my way

I've loved, I've laughed and cried
I've had my fill my share of losing
And now, as tears subside
I find it all so amusing

To think I did all that
And may I say - not in a shy way
Oh no, oh no, not me
I did it my way

For what is a man, what has he got
If not himself, then he has naught
To say the things he truly feels
And not the words of one who kneels
The record shows I took the blows
And did it my way

Yes, it was my way",
        "audio" => [],
        "videos" => [
            [
                'id' => 502,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=FSNidgTKsbE&autoplay=1',
                'artists' => [
                    $people['sinatra'],
                ],
                'info' => 'Live performance'
            ],
        ],
    ],
    [
        'id' => 2,
        'createdAt' => '2016-10-21 00:00:00',
        'genre' => 'Jazz',
        'title' => 'Fly Me To The Moon',
        'authors' => [
            $people['paulanka'],
        ],
        'artists' => [
            $people['sinatra'],
        ],
        'lyrics' => 'Fly me to the moon
Let me play among the stars
Let me see what spring is like on
A Jupiter and Mars
In other words, hold my hand
In other words, baby, kiss me

Fill my heart with song and let me sing for ever more
You are all I long for
All I worship and adore
In other words, please be true
In other words, I love you',
        "audio" => [],
        "videos" => [],
    ],
    [
        'id' => 3,
        'createdAt' => '2016-10-21 00:00:00',
        'genre' => 'Jazz',
        'title' => 'New York, New York',
        'authors' => [
            $people['paulanka'],
        ],
        'artists' => [
            $people['sinatra'],
            $people['lisaminelli'],
        ],
        'lyrics' => "Start spreadin' the news, I'm leavin' today
I want to be a part of it, New York, New York
These vagabond shoes are longing to stray
Right through the very heart of it, New York, New York

I want to wake up in a city that doesn't sleep
And find I'm king of the hill, top of the heap

These little town blues are melting away
I'll make a brand new start of it, in old New York
If I can make it there, I'll make it anywhere
It's up to you , New York, New York

New York, New York
I want to wake up in a city that never sleeps
And find I'm A-number-one, top of the list,
King of the hill, A-number-one

These little town blues are melting away
I'm gonna make a brand new start of it in old New York
A-a-a-nd if I can make it there, I'm gonna make it anywhere
It's up to you, New York, New York

New York",
        "audio" => [
            [
                'id' => 332,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=EUrUfJW1JGk&autoplay=1',
                'artists' => [
                    $people['sinatra'],
                ],
                'info' => 'Live performance'
            ],
            [
                'id' => 338,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=WcuxBf0frQE&autoplay=1',
                'artists' => [
                    $people['lisaminelli'],
                ],
                'info' => 'From Cabaret OMS'
            ],
        ],
        "videos" => [
            [
                'id' => 503,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=xMfz1jlyQrw&autoplay=1',
                'artists' => [
                    $people['sinatra'],
                ],
                'info' => 'Live performance'
            ],
            [
                'id' => 50,
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=N8hVOMAmY-s&autoplay=1',
                'artists' => [
                    $people['lisaminelli'],
                    $people['pavarotti'],
                ],
                'info' => 'Live performance'
            ]
        ],
    ]
];

return $songs;